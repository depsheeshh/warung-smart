<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Debt;
use Illuminate\Support\Facades\Auth;

class DebtController extends Controller
{
    public function index()
    {
        // Ambil kasbon milik customer yang sedang login
        $debts = Debt::where('customer_id', Auth::id())
            ->with('product') // eager load produk
            ->orderByDesc('created_at')
            ->paginate(10);

        return view('customer.debts.index', compact('debts'));
    }
}
