<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Debt extends Model
{
    protected $fillable = [
        'customer_id','product_id','amount','status','due_date','notes'
    ];

    public function customer()
    {
        return $this->belongsTo(User::class,'customer_id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
