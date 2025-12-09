<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class MembershipDiscount extends Model
{
    protected $fillable = ['discount_percent','starts_at','ends_at'];

    protected $casts = [
        'starts_at' => 'date',
        'ends_at'   => 'date',
        'discount_percent' => 'float',
    ];

    public function scopeActive($q)
    {
        return $q->where('starts_at','<=',now())->where('ends_at','>=',now());
    }
}
