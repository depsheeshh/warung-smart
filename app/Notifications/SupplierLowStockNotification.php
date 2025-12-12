<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use App\Models\Product;

class SupplierLowStockNotification extends Notification
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
            'message' => "Stok produk {$this->product->name} yang Anda supply hampir habis (sisa {$this->product->stock}).",
            'product_id' => $this->product->id,
        ];
    }
}
