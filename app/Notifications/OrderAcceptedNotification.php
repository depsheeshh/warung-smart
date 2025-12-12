<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use App\Models\Order;

class OrderAcceptedNotification extends Notification
{
    use Queueable;
    public $order;

    public function __construct(Order $order)
    {
        $this->order = $order;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toDatabase($notifiable)
    {
        return [
            'message' => "Pesanan Anda untuk produk {$this->order->product->name} telah diterima oleh admin.",
            'order_id' => $this->order->id,
        ];
    }
}
