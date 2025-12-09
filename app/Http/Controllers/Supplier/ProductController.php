<?php

namespace App\Http\Controllers\Supplier;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProductController extends Controller
{
    public function index()
    {
        // Hanya produk milik supplier yang login
        $products = Product::where('supplier_id', Auth::id())
            ->latest('id')
            ->paginate(10);

        return view('supplier.products.index', compact('products'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'=>'required|string|max:255',
            'description'=>'nullable|string',
            'price'=>'required|numeric|min:0',
            'stock'=>'required|integer|min:0',
            'image'=>'nullable|image|mimes:jpg,jpeg,png|max:3048',
        ]);

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('products','public');
        }

        $validated['supplier_id'] = Auth::id();
        // Produk dari supplier default pending
        $validated['status'] = 'pending';

        Product::create($validated);

        return back()->with('success','Produk berhasil ditambahkan, menunggu persetujuan admin');
    }

}
