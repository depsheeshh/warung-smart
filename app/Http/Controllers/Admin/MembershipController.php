<?php

namespace App\Http\Controllers\Admin;

use Carbon\Carbon;
use App\Http\Controllers\Controller;
use App\Models\MembershipSubscription;
use App\Notifications\MembershipApprovedNotification;

class MembershipController extends Controller
{
    public function index()
    {
        $pending = MembershipSubscription::with('user')->pending()->get();
        $active  = MembershipSubscription::with('user')->active()->get();
        return view('admin.membership.index', compact('pending','active'));
    }

    public function approve(MembershipSubscription $subscription)
    {
        $subscription->update([
            'status'    => 'active',
            'starts_at' => Carbon::today(),
            'ends_at'   => Carbon::today()->addMonth(), // contoh durasi 1 bulan
        ]);

        // update membership type user
        $subscription->user->update(['membership_type' => 'premium']);

        // kirim notifikasi ke user (customer)
        $subscription->user->notify(new MembershipApprovedNotification());


        return back()->with('success','Membership diaktifkan.');
    }

    public function cancel(MembershipSubscription $subscription)
    {
        $subscription->update(['status'=>'cancelled']);
        return back()->with('success','Pengajuan membership dibatalkan.');
    }

    public function downgrade($userId)
    {
        $user = \App\Models\User::findOrFail($userId);
        $user->update(['membership_type'=>'basic']);
        MembershipSubscription::where('user_id',$userId)->where('status','active')
            ->update(['status'=>'expired','ends_at'=>now()]);
        return back()->with('success','Membership diturunkan ke Basic.');
    }
}
