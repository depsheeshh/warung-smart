<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\Product;
use App\Models\User;

class OrderCreatedNotification extends Notification
{
    use Queueable;
    public $customer;
    public $product;

    public function __construct(User $customer, Product $product)
    {
        $this->customer = $customer;
        $this->product  = $product;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toDatabase($notifiable)
    {
        return [
            'message' => "{$this->customer->name} memesan produk {$this->product->name}.",
            'user_id' => $this->customer->id,
            'product_id' => $this->product->id,
        ];
    }
}
