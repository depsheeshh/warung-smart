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
        'price_snapshot',
    ];

    protected $casts = [
        'price_snapshot' => 'float',
        'quantity'       => 'integer',
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
}
