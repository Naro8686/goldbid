<?php

namespace App\Models\Pages;

use Illuminate\Database\Eloquent\Model;

class Package extends Model
{
    protected $fillable = ['image', 'alt', 'bet', 'bonus', 'price', 'visibly'];

    protected $casts = [
        'visibly' => 'boolean',
        'bet' => 'integer',
        'bonus' => 'integer',
        'price' => 'integer',
    ];
}
