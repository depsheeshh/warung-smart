<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'phone','address',
        'membership_type'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

     // Membership: 1 user punya banyak subscription
    // Relasi: 1 user bisa punya banyak subscription
    public function membershipSubscriptions()
    {
        return $this->hasMany(MembershipSubscription::class);
    }

    // Ambil subscription aktif terbaru
    public function currentSubscription()
    {
        return $this->membershipSubscriptions()
            ->where('status', 'active')
            ->orderByDesc('ends_at')
            ->first();
    }

    // Helper: apakah premium aktif
    public function isPremium(): bool
    {
        $sub = $this->currentSubscription();

        if (!$sub) return false;

        if ($sub->ends_at && $sub->ends_at->isPast()) return false;

        return true;
    }
}
