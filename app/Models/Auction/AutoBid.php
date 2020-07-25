<?php

namespace App\Models\Auction;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class AutoBid extends Model
{
    public const PENDING = 0;
    const ACTIVE = 1;
    protected $fillable = ['auction_id', 'user_id', 'count', 'status'];


    public function auction()
    {
        return $this->belongsTo(Auction::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
