<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MembershipPlan extends Model
{
    protected $fillable = [
        'name','price','duration','is_active',
    ];

    protected $casts = [
        'price' => 'integer',
        'is_active' => 'boolean',
    ];

    public function subscriptions()
    {
        return $this->hasMany(MembershipSubscription::class);
    }

    // Scope aktif
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
