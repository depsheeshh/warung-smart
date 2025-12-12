<?php

namespace App\Http\Controllers\Supplier;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Order::whereHas('product', function($q){
            $q->where('supplier_id', Auth::id());
        })->with('product','customer')->paginate(10);

        return view('supplier.orders.index', compact('orders'));
    }
}

