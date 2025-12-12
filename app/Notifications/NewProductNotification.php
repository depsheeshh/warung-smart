<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use App\Models\Product;

class NewProductNotification extends Notification
{
    use Queueable;
    public $product;

    public function __construct(Product $product)
    {
        $this->product = $product;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toDatabase($notifiable)
    {
        return [
            'message' => "Produk baru tersedia: {$this->product->name}.",
            'product_id' => $this->product->id,
        ];
    }
}
