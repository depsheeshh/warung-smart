<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ForecastResult extends Model
{
    protected $fillable = ['product_id','period','actual','forecast'];
}
