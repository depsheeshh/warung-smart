<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\MembershipSubscription;

class ExpireMembershipSubscriptions extends Command
{
    protected $signature = 'membership:expire-subscriptions';
    protected $description = 'Set expired status for subscriptions whose ends_at has passed';

    public function handle()
    {
        $count = MembershipSubscription::shouldExpire()->update(['status' => 'expired']);

        $this->info("Expired subscriptions updated: {$count}");
    }
}
