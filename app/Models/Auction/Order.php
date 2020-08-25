<?php

namespace App\Models\Auction;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Auction\Order
 *
 * @property int $id
 * @property string|null $order_num
 * @property string|null $payment_type
 * @property string|null $price
 * @property int $status
 * @property bool $exchanged
 * @property int $user_id
 * @property int $auction_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Auction\Auction $auction
 * @property-read User $user
 * @method static \Illuminate\Database\Eloquent\Builder|Order newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Order newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Order query()
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereAuctionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereExchanged($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereOrderNum($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order wherePaymentType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order wherePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereUserId($value)
 * @mixin \Eloquent
 */
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
