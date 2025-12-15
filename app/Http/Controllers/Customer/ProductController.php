<?php

namespace App\Http\Controllers\Customer;

use App\Models\User;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;
use App\Models\MembershipDiscount;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class ProductController extends Controller
{
    public function index()
    {
        // Gunakan paginate agar bisa pakai firstItem(), lastItem(), links()
        $products = Product::where('status','active')
            ->orderByDesc('created_at')
            ->paginate(12);

        return view('customer.products.index', compact('products'));
    }

    public function whatsapp(Request $request)
{
    $orders = $request->input('orders', []);
    $user = Auth::user();
    $createdOrders = [];
    $total = 0;

    foreach ($orders as $productId => $qty) {
        $qty = (int) $qty;
        if ($qty > 0) {
            $product = Product::findOrFail($productId);

            // Validasi stok
            if ($qty > $product->stock) {
                return back()->with('warning', "Stok {$product->name} tidak mencukupi.");
            }

            // Hitung harga (dengan diskon membership jika ada)
            $basePrice = $product->price;
            $discountPercent = 0;
            if ($user->isPremium()) {
                $activeDiscount = MembershipDiscount::active()->orderByDesc('discount_percent')->first();
                if ($activeDiscount) {
                    $discountPercent = $activeDiscount->discount_percent;
                }
            }
            $unitPrice = $basePrice * (1 - $discountPercent / 100);
            $subtotal = $unitPrice * $qty;
            $total += $subtotal;

            // Simpan order
            $order = Order::create([
                'customer_id' => $user->id,
                'product_id' => $product->id,
                'quantity' => $qty,
                'status' => 'pending',
                'unit_price' => $unitPrice,
                'total_price' => $subtotal,
                'discount_percent' => $discountPercent,
            ]);
            $product->decrement('stock', $qty);

            $createdOrders[] = [
                'name' => $product->name,
                'qty' => $qty,
                'base' => $basePrice,
                'unit' => $unitPrice,
                'subtotal' => $subtotal,
                'discount' => $discountPercent,
            ];
        }
    }

    if (empty($createdOrders)) {
        return back()->with('warning','Silakan isi jumlah produk yang ingin dipesan.');
    }

    // Generate pesan WhatsApp
    $message = "Halo Admin, saya *{$user->name}* ingin memesan produk berikut:\n\n";
    foreach ($createdOrders as $item) {
        $message .= "- {$item['name']} x{$item['qty']} @ Rp "
                  . number_format($item['unit'], 0, ',', '.')
                  . " = Rp " . number_format($item['subtotal'], 0, ',', '.');
        if ($item['discount'] > 0) {
            $message .= " (Diskon {$item['discount']}%)";
        }
        $message .= "\n";
    }
    $message .= "\nTotal Belanja: *Rp " . number_format($total, 0, ',', '.') . "*\n";
    $message .= "Mohon konfirmasi dan proses pesanan saya ya 🙏";

    $admin = User::role('admin')->first();
    $phone = $admin ? $admin->phone : '6281234567890';

    $waUrl = "https://wa.me/{$phone}?text=" . urlencode($message);
    return redirect()->away($waUrl);
}

}
