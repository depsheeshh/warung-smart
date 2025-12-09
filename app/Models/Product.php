<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $table = 'products';

    protected $fillable = ['name','description','price','stock','status','image','supplier_id'];

    public function supplier()
    {
        return $this->belongsTo(User::class, 'supplier_id');
    }

    public function getPriceForUser($user)
    {
        $basePrice = $this->price;
        $finalPrice = $basePrice;
        $percent = 0;

        if($user && $user->isPremium()){
            $discount = MembershipDiscount::active()->orderByDesc('discount_percent')->first();
            if($discount){
                $percent = $discount->discount_percent;
                $finalPrice = $basePrice * (1 - ($percent/100));
            }
        }

        return [
            'base' => $basePrice,
            'final' => $finalPrice,
            'percent' => $percent,
        ];
    }

}
