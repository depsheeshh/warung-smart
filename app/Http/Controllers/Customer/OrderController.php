<?php

namespace App\Http\Controllers\Customer;

use App\Models\User;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;
use App\Models\MembershipDiscount;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Notifications\OrderCreatedNotification;
use App\Notifications\SupplierOrderNotification;

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

        $user     = Auth::user();
        $quantity = $validated['quantity'];

        $basePrice       = $product->price;
        $discountPercent = 0;

        if ($user && method_exists($user, 'isPremium') && $user->isPremium()) {
            $activeDiscount = MembershipDiscount::active()
                ->orderByDesc('discount_percent')
                ->first();

            if ($activeDiscount) {
                $discountPercent = $activeDiscount->discount_percent;
            }
        }

        $unitPrice  = $basePrice * (1 - $discountPercent / 100);
        $totalPrice = $unitPrice * $quantity;

        // simpan order ke variabel
        $order = null;
        DB::transaction(function() use ($user, $product, $quantity, $unitPrice, $totalPrice, $discountPercent, &$order) {
            $order = Order::create([
                'customer_id'     => $user->id,
                'product_id'      => $product->id,
                'quantity'        => $quantity,
                'status'          => 'pending',
                'unit_price'      => $unitPrice,
                'total_price'     => $totalPrice,
                'discount_percent'=> $discountPercent,
            ]);

            $product->decrement('stock', $quantity);
        });

        $message = 'Pesanan berhasil dibuat dengan harga Rp ' . number_format($unitPrice, 0, ',', '.');
        if ($discountPercent > 0) {
            $message .= ' (Diskon membership ' . $discountPercent . '% aktif)';
        }

        // Notifikasi admin
        $admins = User::role('admin')->get();
        foreach ($admins as $admin) {
            $admin->notify(new OrderCreatedNotification($user, $product));
        }

        // Notifikasi supplier
        $supplier = $product->supplier; // pastikan relasi product->supplier ada
        if ($supplier && $order) {
            $supplier->notify(new SupplierOrderNotification($order));
        }

        return back()->with('success', $message);
    }

}
