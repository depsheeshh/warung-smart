<?php

namespace App\Notifications;

use App\Models\SupplierPrice;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class PriceIncreasedNotification extends Notification
{
    use Queueable;

    public function __construct(
        public SupplierPrice $current,
        public ?SupplierPrice $previous,
        public float $percentIncrease
    ) {}

    public function via($notifiable)
    {
        return ['database']; // atau ['mail','database']
    }

    public function toArray($notifiable)
    {
        return [
            'type' => 'price_increase',
            'message' => "Harga naik: {$this->current->product->name} dari {$this->current->supplier->name} naik {$this->percentIncrease}% (Rp "
            . number_format((float)($this->previous?->price ?? 0), 0, ',', '.')
            . " → Rp "
            . number_format((float)$this->current->price, 0, ',', '.')
            . ")",
        ];
    }
}
