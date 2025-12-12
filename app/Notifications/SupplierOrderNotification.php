<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use App\Models\Order;

class SupplierOrderNotification extends Notification
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
            'message' => "Pesanan baru untuk produk {$this->order->product->name} (jumlah {$this->order->quantity}).",
            'order_id' => $this->order->id,
            'product_id' => $this->order->product_id,
        ];
    }
}
