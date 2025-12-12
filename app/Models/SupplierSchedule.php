<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SupplierSchedule extends Model
{
    protected $fillable = ['supplier_id','expected_date','actual_date','status'];

    protected $casts = [
        'expected_date' => 'date',
        'actual_date'   => 'date',
    ];

    public function supplier()
    {
        return $this->belongsTo(User::class, 'supplier_id');
    }

    // Scope: hanya yang delayed
    public function scopeDelayed($query)
    {
        return $query->where('status', 'delayed');
    }
}
