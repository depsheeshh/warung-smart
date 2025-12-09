<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
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
}
