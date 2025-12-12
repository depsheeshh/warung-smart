<?php

namespace App\Http\Controllers\Admin;

use App\Models\Order;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Notifications\OrderAcceptedNotification;

class OrderController extends Controller
{
    public function index()
    {
        // Pastikan eager load ke product.supplier dan customer
        $orders = Order::with(['product.supplier','customer'])->paginate(10);
        return view('admin.orders.index', compact('orders'));
    }

    public function accept(Order $order)
    {
        // Jika sudah final (accepted/rejected), hentikan
        if (in_array($order->status, ['accepted','rejected'])) {
            return back()->with('warning', 'Status pesanan sudah final. Tidak bisa diubah lagi.');
        }

        if ($order->status === 'pending') {
            $order->update(['status' => 'accepted']);
        }

        $order->customer->notify(new OrderAcceptedNotification($order));

        return back()->with('success', 'Pesanan diterima.');
    }

    public function reject(Order $order)
    {
        // Jika sudah final (accepted/rejected), hentikan
        if (in_array($order->status, ['accepted','rejected'])) {
            return back()->with('warning', 'Status pesanan sudah final. Tidak bisa diubah lagi.');
        }

        if ($order->status === 'pending') {
            DB::transaction(function() use ($order) {
                // Kembalikan stok produk
                $order->product->increment('stock', $order->quantity);

                // Update status
                $order->update(['status' => 'rejected']);
            });
        }
        return back()->with('success', 'Pesanan ditolak.');
    }
}
