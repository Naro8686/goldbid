<?php

namespace App\Models\Auction;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Auction\AutoBid
 *
 * @property int $id
 * @property int $auction_id
 * @property int $user_id
 * @property int $count
 * @property int $status
 * @property \Illuminate\Support\Carbon $bid_time
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Auction\Auction $auction
 * @property-read User $user
 * @method static \Illuminate\Database\Eloquent\Builder|AutoBid newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|AutoBid newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|AutoBid query()
 * @method static \Illuminate\Database\Eloquent\Builder|AutoBid whereAuctionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AutoBid whereBidTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AutoBid whereCount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AutoBid whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AutoBid whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AutoBid whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AutoBid whereUserId($value)
 * @mixin \Eloquent
 * @method static \Illuminate\Database\Eloquent\Builder|AutoBid whereStatus($value)
 */
class AutoBid extends Model
{
    protected $fillable = ['auction_id', 'user_id', 'count', 'bid_time', 'status'];
    protected $dates = ['bid_time'];
    protected $casts = [
        'status' => 'integer',
        'bid_time' => 'datetime',
    ];
    public const WORKED = 1;
    public const PENDING = 0;

    public function auction()
    {
        return $this->belongsTo(Auction::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function minus()
    {
        $this->decrement('count');
    }

    public function timeToBet()
    {
        return rand(0, ($this->auction->step_time() - 1));
    }
}
