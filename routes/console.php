<?php

use App\Models\User;
use App\Models\Product;
use App\Models\Debt;
use App\Models\MembershipDiscount;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;
use App\Notifications\LowStockNotification;
use App\Notifications\DiscountExpiredNotification;
use App\Notifications\SupplierLowStockNotification;
use App\Notifications\DebtOverdueNotification;

// Command bawaan Laravel
Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Scheduler untuk membership expired & stok menipis
Schedule::call(function () {
    // 1. Cek discount membership expired
    $expired = MembershipDiscount::where('ends_at', '<', now())->get();
    if ($expired->count()) {
        $admins = User::role('admin')->get();
        foreach ($admins as $admin) {
            $admin->notify(new DiscountExpiredNotification());
        }
    }

    // 2. Cek stok produk menipis
    $lowStockProducts = Product::where('stock', '<', 5)->get();
    foreach ($lowStockProducts as $product) {
        $admins = User::role('admin')->get();
        foreach ($admins as $admin) {
            $admin->notify(new LowStockNotification($product));
        }
    }
    $lowStockProducts = Product::where('stock','<',5)->get();
        foreach ($lowStockProducts as $product) {
            if ($product->supplier) {
                $product->supplier->notify(new SupplierLowStockNotification($product));
            }
        }

        $expiredSubs = \App\Models\MembershipSubscription::where('ends_at','<',now())->get();
            foreach ($expiredSubs as $sub) {
                $sub->user->notify(new \App\Notifications\MembershipExpiredNotification());
            }
    $overdueDebts = Debt::where('status','unpaid')
                        ->whereDate('due_date','<',now())
                        ->get();

    foreach ($overdueDebts as $debt) {
        $debt->update(['status'=>'overdue']);
        $debt->customer->notify(new DebtOverdueNotification($debt));
    }

})->daily(); // jalan sekali sehari
