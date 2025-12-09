<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MembershipSubscription extends Model
{
    protected $fillable = ['user_id','status','starts_at','ends_at'];

    protected $casts = [
        'starts_at' => 'datetime',
        'ends_at'   => 'datetime',
    ];


    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // 🔍 Scope untuk status
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeExpired($query)
    {
        return $query->where('status', 'expired');
    }

    public function scopeCancelled($query)
    {
        return $query->where('status', 'cancelled');
    }
    public function scopeShouldExpire($query)
    {
        return $query->where('status','active')
                    ->whereDate('ends_at','<', now());
    }


    // 🔍 Helper: apakah subscription sudah expired
    public function isExpired(): bool
    {
        return $this->ends_at && $this->ends_at->isPast();
    }
}
