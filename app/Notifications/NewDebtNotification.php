<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use App\Models\Debt;

class NewDebtNotification extends Notification
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
            'message' => "Kasbon baru Rp {$this->debt->amount} ditambahkan. Jatuh tempo: {$this->debt->due_date}",
            'debt_id' => $this->debt->id,
        ];
    }
}
