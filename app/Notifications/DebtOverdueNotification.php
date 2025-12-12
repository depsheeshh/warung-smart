<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use App\Models\Debt;

class DebtOverdueNotification extends Notification
{
    use Queueable;
    public $debt;

    public function __construct(Debt $debt)
    {
        $this->debt = $debt;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toDatabase($notifiable)
    {
        return [
            'message' => "Kasbon Rp {$this->debt->amount} sudah jatuh tempo!",
            'debt_id' => $this->debt->id,
        ];
    }
}
