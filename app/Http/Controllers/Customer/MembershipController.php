<?php

namespace App\Http\Controllers\Customer;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\MembershipSubscription;
use App\Models\MembershipDiscount;
use App\Notifications\MembershipRequestedNotification;
use App\Models\User;

class MembershipController extends Controller
{
    public function index()
    {
        $subs = MembershipSubscription::where('user_id', Auth::id())
                                  ->orderByDesc('created_at')
                                  ->first();

        $membershipDiscounts = MembershipDiscount::all();

        return view('customer.membership.index', compact('subs','membershipDiscounts'));
    }

    public function subscribe(Request $request)
    {
        MembershipSubscription::create([
            'user_id' => Auth::id(),
            'status'  => 'pending',
            'starts_at' => null,
            'ends_at'   => null,
        ]);

        // Kirim notifikasi ke semua admin
        $admins = User::role('admin')->get();
        foreach ($admins as $admin) {
            $admin->notify(new MembershipRequestedNotification(Auth::user()));
        }

        return redirect()->route('customer.membership.index')
                     ->with('success','Pengajuan membership dikirim, tunggu verifikasi admin.');
    }

    public function cancel(MembershipSubscription $subscription)
    {
        if ($subscription->user_id !== Auth::id()) {
            abort(403, 'Tidak bisa membatalkan pengajuan orang lain.');
        }

        if ($subscription->status !== 'pending') {
            return back()->with('warning', 'Pengajuan sudah diproses, tidak bisa dibatalkan.');
        }

        $subscription->update(['status' => 'cancelled']);
        return back()->with('success', 'Pengajuan membership berhasil dibatalkan.');
    }
}
