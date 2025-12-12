<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SupplierPrice extends Model
{
    protected $fillable = ['supplier_id','product_id','price','date'];

    protected $casts = [
        'price' => 'decimal:2',
        'date'  => 'date',
    ];

    public function supplier()
    {
        return $this->belongsTo(User::class, 'supplier_id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    // Dapatkan harga terbaru untuk pasangan supplier-product
    public static function latestFor($supplierId, $productId)
    {
        return static::where('supplier_id', $supplierId)
            ->where('product_id', $productId)
            ->orderByDesc('date')
            ->first();
    }
}
