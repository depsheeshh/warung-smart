<?php

namespace App\Http\Controllers\Admin;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Notifications\OrderAcceptedNotification;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Order::with(['product.supplier','customer'])->paginate(10);
        return view('admin.orders.index', compact('orders'));
    }

    public function accept(Order $order)
    {
        if (in_array($order->status, ['accepted','rejected'])) {
            return back()->with('warning', 'Status pesanan sudah final. Tidak bisa diubah lagi.');
        }

        if ($order->status === 'pending') {
            $order->update(['status' => 'accepted']);
        }

        $order->customer->notify(new OrderAcceptedNotification($order));

        return back()->with('success', 'Pesanan diterima.');
    }

    public function reject(Request $request, Order $order)
    {
        if (in_array($order->status, ['accepted','rejected'])) {
            return back()->with('warning', 'Status pesanan sudah final. Tidak bisa diubah lagi.');
        }

        $request->validate([
            'rejection_reason' => 'required|string|max:500',
        ]);

        if ($order->status === 'pending') {
            DB::transaction(function() use ($order, $request) {
                $order->product->increment('stock', $order->quantity);
                $order->update([
                    'status' => 'rejected',
                    'rejection_reason' => $request->rejection_reason,
                ]);
            });
        }

        return back()->with('success', 'Pesanan ditolak dengan alasan: '.$request->rejection_reason);
    }
}
