<?php

namespace App\Models\Auction;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Auction\Bid
 *
 * @property int $id
 * @property int|null $auction_id
 * @property int|null $user_id
 * @property int $bet
 * @property int $bonus
 * @property string|null $price
 * @property string|null $title
 * @property string|null $nickname
 * @property bool $is_bot
 * @property bool $win
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|Bid newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Bid newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Bid query()
 * @method static \Illuminate\Database\Eloquent\Builder|Bid whereAuctionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Bid whereBet($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Bid whereBonus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Bid whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Bid whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Bid whereIsBot($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Bid whereNickname($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Bid wherePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Bid whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Bid whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Bid whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Bid whereWin($value)
 * @mixin \Eloquent
 */
class Bid extends Model
{
    protected $fillable = ['auction_id','user_id','title','nickname','bet','bonus','price','is_bot'];

    protected $casts = [
        'bet' => 'integer',
        'bonus' => 'integer',
        'is_bot' => 'boolean',
        'win' => 'boolean'
    ];
}
