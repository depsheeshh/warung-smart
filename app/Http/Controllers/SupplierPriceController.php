<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Product;
use Illuminate\Http\Request;
use App\Models\SupplierPrice;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Notification;
use App\Notifications\PriceIncreasedNotification;

class SupplierPriceController extends Controller
{
    /**
     * Admin melihat histori harga (filter produk/supplier)
     */
    public function index(Request $request)
    {
        // Jika role admin → tampilkan semua harga dengan filter
        if (Auth::user()->hasRole('admin')) {
            $query = SupplierPrice::with(['supplier', 'product'])->orderByDesc('date');

            if ($request->filled('product_id')) {
                $query->where('product_id', $request->product_id);
            }
            if ($request->filled('supplier_id')) {
                $query->where('supplier_id', $request->supplier_id);
            }

            return view('admin.prices.index', [
                'prices'   => $query->paginate(15),
                'products' => Product::select('id', 'name')->orderBy('name')->get(),
                'suppliers'=> User::role('supplier')->select('id', 'name')->orderBy('name')->get(),
            ]);
        }

        // Jika role supplier → tampilkan histori harga miliknya
        if (Auth::user()->hasRole('supplier')) {
            $prices = SupplierPrice::with('product')
                ->where('supplier_id', Auth::id())
                ->orderByDesc('date')
                ->paginate(10);

            return view('supplier.prices.index', compact('prices'));
        }

        // fallback jika bukan admin/supplier
        abort(403, 'Unauthorized');
    }

    /**
     * Supplier menginput/update harga
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'product_id'  => ['required', 'exists:products,id'],
            'price'       => ['required', 'numeric', 'min:0'],
            'date'        => ['required', 'date'],
        ]);

        $supplierId = Auth::id();

        // Pastikan produk memang milik supplier yang login
        $product = Product::where('id', $validated['product_id'])
            ->where('supplier_id', $supplierId)
            ->first();

        if (!$product) {
            return back()->withErrors(['product_id' => 'Produk tidak valid atau bukan milik Anda.']);
        }

        $price = SupplierPrice::create([
            'supplier_id' => $supplierId,
            'product_id'  => $product->id,
            'price'       => $validated['price'],
            'date'        => $validated['date'],
        ]);

        // Bandingkan dengan harga sebelumnya dari supplier ini untuk produk ini
        $previous = SupplierPrice::where('supplier_id', $supplierId)
            ->where('product_id', $product->id)
            ->where('id', '<', $price->id)
            ->orderByDesc('date')
            ->first();

        if ($previous && $price->price > $previous->price) {
            $percentIncrease = (($price->price - $previous->price) / max($previous->price, 1)) * 100;

            // Threshold kenaikan signifikan (misal 10%)
            if ($percentIncrease >= 10) {
                $admins = User::role('admin')->get();
                Notification::send($admins, new PriceIncreasedNotification($price, $previous, $percentIncrease));
            }
        }

        return back()->with('success', 'Harga produk berhasil dicatat.');
    }

}
