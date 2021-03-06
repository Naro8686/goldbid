<?php

namespace App\Models\Bots;

use App\Models\Auction\Auction;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;

/**
 * App\Models\Bots\AuctionBot
 *
 * @property int $id
 * @property int $auction_id
 * @property int $bot_id
 * @property string $name bot 1,2,3
 * @property string $time_to_bet bot 1,2,3
 * @property int|null $change_name bot 1
 * @property int|null $num_moves bot 2,3
 * @property int|null $num_moves_other_bot bot 2,3
 * @property int $status
 * @property \Illuminate\Support\Carbon $bid_time
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read Auction $auction
 * @property-read \App\Models\Bots\Bot $bot
 * @method static \Illuminate\Database\Eloquent\Builder|AuctionBot newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|AuctionBot newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|AuctionBot query()
 * @method static \Illuminate\Database\Eloquent\Builder|AuctionBot whereAuctionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AuctionBot whereBidTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AuctionBot whereBotId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AuctionBot whereChangeName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AuctionBot whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AuctionBot whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AuctionBot whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AuctionBot whereNumMoves($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AuctionBot whereNumMovesOtherBot($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AuctionBot whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AuctionBot whereTimeToBet($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AuctionBot whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class AuctionBot extends Model
{
    const PENDING = 0;
    const WORKED = 1;
    protected $fillable = [
        'auction_id',
        'bot_id',
        'name',
        'time_to_bet',
        'change_name',
        'num_moves',
        'num_moves_other_bot',
        'status',
    ];
    protected $casts = [
        'status' => 'integer',
    ];

    public function auction(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Auction::class);
    }

    public function bot(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Bot::class);
    }

    /**
     * @param int|null $number
     * @return bool|int
     */
    public function number(int $number = null)
    {
        return is_null($number) ? $this->bot->number : $this->bot->number === $number;
    }

    public function timeToBet(int $subSec = 0)
    {
        $stepTime = $this->auction->step_time();
        if ($this->time_to_bet === '0') {
            $min = 0;
            $max = $stepTime - 1;
        } else {
            list($max, $min) = array_pad(str_replace(' ', '', explode('-', $this->time_to_bet)), 2, null);
            $avg = $max - $min;
            if ($max >= $stepTime) $max = $stepTime - 1;
            if ($min >= $max) $min = (($max >= $avg) ? ($max - $avg) : 0);
        }
        $rand = rand($min, $max);
        $time = ($stepTime >= $rand ? ($stepTime - $rand) : ($stepTime - 1));
        return $time < 1 ? 0 : ($time - $subSec);
    }

    public function botRefresh(): ?AuctionBot
    {
        $refreshed = false;
        if ($this->auction->bots->isNotEmpty()) {
            $names = $this->auction->bots->pluck('name');
            $random = BotName::whereNotIn('name', $names)->inRandomOrder()->first(['name']);
            if ($random) {
                $data['name'] = $random->name;
                if ($this->number(1)) {
                    list($min, $max) = array_pad(str_replace(' ', '', explode('-', $this->bot->change_name)), 2, null);
                    $data['change_name'] = rand($min, $max) < 1 ? 1 : rand($min, $max);
                } else {
                    list($min, $max) = array_pad(str_replace(' ', '', explode('-', $this->bot->num_moves)), 2, null);
                    $data['num_moves'] = rand($min, $max) < 1 ? 1 : rand($min, $max);
                    list($min, $max) = array_pad(str_replace(' ', '', explode('-', $this->bot->num_moves_other_bot)), 2, null);
                    $data['num_moves_other_bot'] = rand($min, $max) < 1 ? 1 : rand($min, $max);
                }
                $refreshed = $this->update($data);
            } else Log::warning('error in refresh bot data AuctionID = ' . $this->auction->id);
        }
        return $refreshed ? $this : null;
    }


    /**
     * @param string $column
     * @param int $count
     * @return int
     */
    public function minus(string $column, int $count = 1)
    {
        if ($this->$column < $count) return false;
        return $this->decrement($column, $count);
    }

    /**
     * @param string $column
     * @param int $count
     * @return int
     */
    public function plus(string $column, int $count = 1): int
    {
        return $this->increment($column, $count);
    }
}
