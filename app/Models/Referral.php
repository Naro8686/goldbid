<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Referral extends Model
{
    protected $fillable = ['referred_by', 'referral_id', 'referral_bonus'];

    protected $casts = [
        'referral_bonus' => 'integer',
        'referred_by' => 'integer',
        'referral_id' => 'integer',
    ];
}
