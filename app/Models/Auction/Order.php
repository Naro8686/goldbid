<?php

namespace App\Models\Auction;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    const PENDING = '0';
    const SUCCESS = '1';
    const DECLINED = '2';
    protected $fillable = ['order_num', 'payment_type','price' ,'status', 'exchanged', 'user_id', 'auction_id'];
    protected $casts = ['status' => 'integer', 'exchanged' => 'boolean'];

    public function auction()
    {
        return $this->belongsTo(Auction::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
