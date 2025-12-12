<?php

namespace App\Notifications;

use App\Models\SupplierSchedule;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class SupplierDelayedNotification extends Notification
{
    use Queueable;

    public function __construct(public SupplierSchedule $schedule) {}

    public function via($notifiable)
    {
        return ['database']; // atau ['mail','database']
    }

    public function toArray($notifiable)
    {
        return [
            'type' => 'supplier_delayed',
            'message' => "Supplier {$this->schedule->supplier->name} terlambat. Jadwal: {$this->schedule->expected_date->format('d M Y')}, datang: {$this->schedule->actual_date?->format('d M Y')}",
        ];
    }
}
