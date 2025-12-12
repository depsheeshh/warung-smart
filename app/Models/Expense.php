<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Expense extends Model
{
    protected $fillable = [
        'category',
        'amount',
        'date',
        'notes',
    ];

    // Casting agar amount selalu numeric
    protected $casts = [
        'amount' => 'decimal:2',
        'date'   => 'date',
    ];
}
