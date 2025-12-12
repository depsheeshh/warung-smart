<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'customer_id',
        'product_id',
        'quantity',
        'status',
        'unit_price',
        'total_price',
    ];

    protected $casts = [
        'unit_price'  => 'float',
        'total_price' => 'float',
        'quantity'    => 'integer',
    ];


    public function customer()
    {
        return $this->belongsTo(User::class,'customer_id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
    // Supplier tidak langsung, ambil via product
    public function supplier()
    {
        return $this->product ? $this->product->supplier : null;
    }

    public function getUnitPriceAttribute($value)
    {
        return $value ?? $this->product->price;
    }

    public function getTotalPriceAttribute($value)
    {
        return $value ?? $this->quantity * $this->unit_price;
    }


    protected static function booted()
    {
        static::updating(function ($order) {
            // Jika status awal sudah final, jangan izinkan perubahan status
            if (in_array($order->getOriginal('status'), ['accepted','rejected'])
                && $order->isDirty('status')) {
                // Batalkan perubahan status
                throw new \RuntimeException('Status pesanan sudah final.');
            }
        });
    }
}
