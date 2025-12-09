<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Order::with(['product.supplier','customer'])->paginate(10);
        return view('admin.orders.index', compact('orders'));
    }


    public function accept(Order $order)
    {
        $order->update(['status' => 'accepted']);
        return back()->with('success', 'Pesanan diterima.');
    }

    public function reject(Order $order)
    {
        $order->update(['status' => 'rejected']);
        return back()->with('success', 'Pesanan ditolak.');
    }
}

