<?php

namespace App\Models\Auction;

use Illuminate\Database\Eloquent\Model;

class Bid extends Model
{
    protected $fillable = ['auction_id','user_id','title','nickname','bet','bonus','price','is_bot'];

    protected $casts = [
        'bet' => 'integer',
        'bonus' => 'integer',
        'is_bot' => 'boolean',
        'win' => 'boolean',
    ];
}
