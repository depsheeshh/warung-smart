<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ForecastMetric extends Model
{
    protected $fillable = ['product_id','mad','mape'];
}
