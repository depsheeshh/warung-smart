<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class MembershipRequestedNotification extends Notification
{
    use Queueable;
     public $customer;
    /**
     * Create a new notification instance.
     */
    public function __construct($customer)
    {
        $this->customer = $customer;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via($notifiable)
    {
        return ['database']; // simpan ke DB
    }

    /**
     * Get the mail representation of the notification.
     */
   public function toDatabase($notifiable)
    {
        return [
            'message' => "{$this->customer->name} mengajukan membership Premium.",
            'user_id' => $this->customer->id,
        ];
    }
}
