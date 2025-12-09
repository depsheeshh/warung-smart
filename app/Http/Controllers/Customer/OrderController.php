<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\MembershipDiscount;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Order::where('customer_id', Auth::id())
                       ->with(['product'])
                       ->orderByDesc('created_at')
                       ->paginate(10);

        return view('customer.orders.index', compact('orders'));
    }

    public function store(Request $request, Product $product)
    {
        $validated = $request->validate([
            'quantity' => 'required|integer|min:1|max:' . max(1, (int)$product->stock),
        ]);

        $user = Auth::user();

        // Harga dasar & final
        $basePrice  = $product->price;
        $finalPrice = $basePrice;

        if ($user && method_exists($user, 'isPremium') && $user->isPremium()) {
            $discount = MembershipDiscount::active()->orderByDesc('discount_percent')->first();
            if ($discount) {
                $finalPrice = $basePrice * (1 - ($discount->discount_percent / 100));
            }
        }

        DB::transaction(function() use ($user, $product, $validated, $finalPrice) {
            // Buat order dengan snapshot harga final
            Order::create([
                'customer_id'   => $user->id,
                'product_id'    => $product->id,
                'quantity'      => $validated['quantity'],
                'status'        => 'pending',
                'price_snapshot'=> $finalPrice,
            ]);

            // Opsional: kurangi stok saat pesanan dibuat (atau saat accepted, tergantung kebijakan)
            // $product->decrement('stock', $validated['quantity']);
        });

        return back()->with('success', 'Pesanan berhasil dibuat dengan harga Rp ' . number_format($finalPrice, 0, ',', '.'));
    }
}
