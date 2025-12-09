<?php

namespace App\Http\Controllers\Customer;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\MembershipSubscription;

class MembershipController extends Controller
{
    public function index()
    {
        $subs = Auth::user()->currentSubscription();
        return view('customer.membership.index', compact('subs'));
    }

    public function subscribe(Request $request)
    {
        MembershipSubscription::create([
            'user_id' => Auth::id(),
            'status'  => 'pending',
        ]);

        return back()->with('success','Pengajuan membership dikirim, tunggu verifikasi admin.');
    }
}
