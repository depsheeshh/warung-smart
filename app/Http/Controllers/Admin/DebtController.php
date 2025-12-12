<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Debt;
use App\Models\User;
use App\Notifications\NewDebtNotification;
use App\Notifications\DebtPaidNotification;

class DebtController extends Controller
{
    public function index()
    {
        $debts = Debt::with('customer','product')->orderByDesc('created_at')->paginate(10);
        return view('admin.debts.index', compact('debts'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'customer_id' => 'required|exists:users,id',
            'amount'      => 'required|numeric|min:1',
            'product_id'  => 'nullable|exists:products,id',
            'due_date'    => 'nullable|date',
            'notes'       => 'nullable|string',
        ]);

        $debt = Debt::create($validated);

        // Notifikasi ke customer
        $debt->customer->notify(new NewDebtNotification($debt));

        return back()->with('success','Kasbon berhasil dicatat.');
    }

    public function update(Request $request, Debt $debt)
    {
        $validated = $request->validate([
            'status' => 'required|in:unpaid,overdue,paid',
        ]);

        $debt->update([
            'status' => $validated['status'],
        ]);

        return back()->with('success','Status kasbon berhasil diperbarui.');
    }


    public function markAsPaid(Debt $debt)
    {
        $debt->update(['status'=>'paid']);
        $debt->customer->notify(new DebtPaidNotification($debt));
        return back()->with('success','Kasbon ditandai lunas.');
    }
}
