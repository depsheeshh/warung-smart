<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::paginate(10);
        return view('admin.products.index', compact('products'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'=>'required|string|max:255',
            'description'=>'nullable|string',
            'price'=>'required|numeric|min:0',
            'stock'=>'required|integer|min:0',
            'image'=>'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('products','public');
        }

        // Produk dari admin langsung aktif
        $validated['status'] = 'active';

        Product::create($validated);

        return back()->with('success','Produk berhasil ditambahkan dan langsung aktif');
    }


    public function update(Request $request, Product $product)
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

        $product->update($validated);
        return redirect()->route('admin.products.index')->with('success','Produk berhasil diperbarui');
    }

    public function destroy(Product $product)
    {
        $product->delete();
        return redirect()->route('admin.products.index')->with('success','Produk berhasil dihapus');
    }

    public function approve(Product $product)
    {
        $product->update(['status' => 'active']);
        return back()->with('success','Produk berhasil disetujui');
    }
}
