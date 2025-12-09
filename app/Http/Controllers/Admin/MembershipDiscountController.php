<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MembershipDiscount;
use Illuminate\Http\Request;

class MembershipDiscountController extends Controller
{
    public function index()
    {
        $discounts = MembershipDiscount::orderByDesc('starts_at')->paginate(10);
        return view('admin.membership_discounts.index', compact('discounts'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'discount_percent' => 'required|numeric|min:0|max:100',
            'starts_at'        => 'required|date',
            'ends_at'          => 'required|date|after:starts_at',
        ]);

        MembershipDiscount::create($validated);

        return redirect()->route('admin.membership_discounts.index')
                         ->with('success','Diskon membership berhasil ditambahkan.');
    }

    public function update(Request $request, MembershipDiscount $membershipDiscount)
    {
        $validated = $request->validate([
            'discount_percent' => 'required|numeric|min:0|max:100',
            'starts_at'        => 'required|date',
            'ends_at'          => 'required|date|after:starts_at',
        ]);

        $membershipDiscount->update($validated);

        return redirect()->route('admin.membership_discounts.index')
                         ->with('success','Diskon membership berhasil diperbarui.');
    }

    public function destroy(MembershipDiscount $membershipDiscount)
    {
        $membershipDiscount->delete();
        return back()->with('success','Diskon membership dihapus.');
    }
}
