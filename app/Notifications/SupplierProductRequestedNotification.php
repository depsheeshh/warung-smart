<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use App\Models\Product;
use App\Models\User;

class SupplierProductRequestedNotification extends Notification
{
    use Queueable;

    public $supplier;
    public $product;

    public function __construct(User $supplier, Product $product)
    {
        $this->supplier = $supplier;
        $this->product  = $product;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toDatabase($notifiable)
    {
        return [
            'message' => "Supplier {$this->supplier->name} menambahkan produk {$this->product->name} untuk dipasok ke Warung Pa Usman.",
            'supplier_id' => $this->supplier->id,
            'product_id'  => $this->product->id,
        ];
    }
}
