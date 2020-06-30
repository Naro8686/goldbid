<?php

namespace App\Models;

use App\Models\Pages\Package;
use Illuminate\Database\Eloquent\Model;

class CouponOrder extends Model
{
    protected $fillable = ['order', 'payment_type', 'user_id', 'coupon_id'];

    public function coupon()
    {
        return $this->belongsTo(Package::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
