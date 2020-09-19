<?php

namespace App\Models\Auction;

use App\Models\Bots\AuctionBot;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Log;
use Throwable;

/**
 * App\Models\Auction\Auction
 *
 * @property int $id
 * @property string $title
 * @property string|null $short_desc
 * @property string|null $desc
 * @property string|null $specify
 * @property string|null $terms
 * @property string|null $img_1
 * @property string|null $img_2
 * @property string|null $img_3
 * @property string|null $img_4
 * @property string|null $alt_1
 * @property string|null $alt_2
 * @property string|null $alt_3
 * @property string|null $alt_4
 * @property string $start_price
 * @property string $full_price
 * @property string|null $bot_shutdown_count
 * @property string|null $bot_shutdown_price
 * @property int $bid_seconds
 * @property Carbon|null $step_time
 * @property int $step_price
 * @property Carbon $start
 * @property Carbon|null $end
 * @property bool $exchange
 * @property bool $buy_now
 * @property bool $top
 * @property bool $active
 * @property int $status
 * @property int|null $product_id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Auction\AutoBid[] $autoBid
 * @property-read int|null $auto_bid_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Auction\Bid[] $bid
 * @property-read int|null $bid_count
 * @property-read \App\Models\Auction\Product|null $product
 * @property-read \Illuminate\Database\Eloquent\Collection|User[] $userFavorites
 * @property-read int|null $user_favorites_count
 * @method static \Illuminate\Database\Eloquent\Builder|Auction newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Auction newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Auction query()
 * @method static \Illuminate\Database\Eloquent\Builder|Auction whereActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Auction whereAlt1($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Auction whereAlt2($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Auction whereAlt3($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Auction whereAlt4($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Auction whereBidSeconds($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Auction whereBotShutdownCount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Auction whereBotShutdownPrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Auction whereBuyNow($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Auction whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Auction whereDesc($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Auction whereEnd($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Auction whereExchange($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Auction whereFullPrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Auction whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Auction whereImg1($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Auction whereImg2($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Auction whereImg3($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Auction whereImg4($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Auction whereProductId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Auction whereShortDesc($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Auction whereSpecify($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Auction whereStart($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Auction whereStartPrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Auction whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Auction whereStepPrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Auction whereStepTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Auction whereTerms($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Auction whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Auction whereTop($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Auction whereUpdatedAt($value)
 * @mixin \Eloquent
 * @property-read \Illuminate\Database\Eloquent\Collection|AuctionBot[] $bots
 * @property-read int|null $bots_count
 */
class Auction extends Model
{
    const BET_RUB = 0.1;

    public const STATUS_PENDING = 1;
    public const STATUS_ACTIVE = 2;
    public const STATUS_FINISHED = 3;
    public const STATUS_ERROR = 4;
    protected $fillable = [
        'title', 'short_desc', 'desc', 'specify', 'terms',
        'img_1', 'img_2', 'img_3', 'img_4',
        'alt_1', 'alt_2', 'alt_3', 'alt_4',
        'start_price', 'full_price',
        'bot_shutdown_count', 'bot_shutdown_price',
        'step_time', 'step_price', 'start', 'end', 'exchange', 'buy_now',
        'top', 'active', 'product_id', 'status', 'bid_seconds',
    ];
    protected $dates = ['start', 'end', 'step_time'];
    protected $casts = [
        'start' => 'datetime',
        'end' => 'datetime',
        'status' => 'integer',
        'step_price' => 'integer',
        'bid_seconds' => 'integer',
        'step_time' => 'datetime',
        'exchange' => 'boolean',
        'buy_now' => 'boolean',
        'top' => 'boolean',
        'active' => 'boolean',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function type()
    {
        $type = null;
        if ((bool)$this->buy_now) $type = 'product';
        else {
            if ((bool)$this->exchange) $type = 'money';
            else $type = 'bet';
        }
        return $type;
    }

    public function userFavorites()
    {
        return $this->belongsToMany(User::class, 'favorites', 'auction_id', 'user_id');
    }

    public function autoBid()
    {
        return $this->hasMany(AutoBid::class);
    }

    public function bots()
    {
        return $this->hasMany(AuctionBot::class);
    }

    public function botNum(int $num)
    {
        return $this->bots()->whereHas('bot', function ($query) use ($num) {
            $query->where('number', '=', $num);
        })->first();
    }

    public function shutdownBots()
    {
        $count = 0;
        $stopBotOne = (int)$this->bot_shutdown_count;
        $stopBotTwoThree = $sumBids = (int)$this->bot_shutdown_price;
        if ($this->bid->isNotEmpty()) {
            if (!is_null($this->botNum(1))) {
                $count = $count + $stopBotOne;
            }
            if (!is_null($this->botNum(2)) || !is_null($this->botNum(3))) {
                $sumBids = $this->bid->sum('bet') / self::BET_RUB;
            }
        }

        return $count < 1 && $sumBids >= $stopBotTwoThree;
    }

    public function bid()
    {
        return $this->hasMany(Bid::class);
    }

    public function bidTable()
    {
        $tr = "";
        foreach ($this->bid()->orderBy('id', 'desc')->take(5)->get() as $bid) {
            $tr .= "<tr>
                      <td>{$bid->price} руб</td>
                      <td>{$bid->nickname}</td>
                      <td>{$bid->created_at->format('H:i:s')}</td>
                    </tr>";
        }
        return $tr;
    }

    public function winner()
    {
        return $this->bid()
                ->latest('id')
                ->first() ?? new Bid;
    }

    public function status()
    {
        $winner = ($this->winner()->is_bot) ? 'бот' : 'игрок';
        if ((int)$this->status === self::STATUS_ACTIVE) $text = 'Активный';
        if ((int)$this->status === self::STATUS_PENDING) $text = 'Скоро начало';
        if ((int)$this->status === self::STATUS_ERROR) $text = 'Ошибка';
        if ((int)$this->status === self::STATUS_FINISHED && !is_null($this->winner()->id)) $text = "Победил {$winner}";
        if ((int)$this->status === self::STATUS_FINISHED && is_null($this->winner()->id)) $text = "Не состоялся";
        return $text ?? '';
    }

    public function price()
    {
        return is_null($this->winner()->price)
            ? $this->start_price()
            : $this->winner()->price;
    }

    public function new_price()
    {
        return is_null($this->winner()->price)
            ? $this->price()
            : ($this->price() + $this->step_price());
    }

    public function countBid()
    {
        $price = $this->bid->isEmpty()
            ? $this->start_price
            : ($this->step_price / 100);
        return round($price / Auction::BET_RUB);
    }

    public function step_price()
    {
        $price = ($this->step_price / 100);
        return number_format($price, 1);
    }

    public function start_price()
    {
        return number_format($this->start_price, 1);
    }

    public function full_price($user_id = null)
    {
        if ($this->full_price <= 0) return 0;
        $user_bet = $this->bid()->where('user_id', $user_id)->sum('bet');
        $new_price = ($this->full_price - ($user_bet / self::BET_RUB));
        return ($new_price <= 0) ? 1 : $new_price;
        //number_format($new_price, 1);
    }

    public static function data()
    {
        $data = self::all();
        $auctions = new Collection;
        foreach ($data as $auction) {
            $auctions->push([
                'id' => $auction->id,
                'img_1' => $auction->img_1,
                'alt_1' => $auction->alt_1,
                'title' => $auction->title,
                'status' => $auction->status(),
                'bet' => $auction->bid()->where('is_bot', false)->sum('bet'),
                'bonus' => $auction->bid()->where('is_bot', false)->sum('bonus'),
                'bot' => $auction->bid()->where('is_bot', true)->count(),
                'start' => $auction->start->format('Y-m-d H:i:s'),
                'end' => $auction->end ? $auction->end->format('Y-m-d H:i:s') : null,
                'active' => $auction->active ? 'да' : 'нет',
            ]);
        }
        return $auctions;
    }

    public static function info()
    {
        $auctions = self::all();
        return collect([
            'auction_count' => $auctions->count(),
            'active_count' => $auctions->where('active', true)->count()
        ]);
    }

    public function auctionCard()
    {
        $bids = $this->bid()->where('is_bot', false)->get()->groupBy('user_id');
        return $bids->map(function ($item) {
            return collect([
                'bonus' => $item->sum('bonus'),
                'bet' => $item->sum('bet'),
            ]);
        });
    }

    public function start()
    {
        return !Carbon::now("Europe/Moscow")->diff($this->start)->invert
            ? $this->start->diffInSeconds()
            : 0;
    }

    public function exchangeBetBonus($user_id = null)
    {
        $bet = $bonus = 0;
        if (isset($this->bid) && $this->status === self::STATUS_FINISHED && !is_null($user_id)) {
            $new_price = $this->full_price;
            $bet = (int)round($new_price / 10);
            $bonus = (int)round($bet / 2);
        }
        return ['bet' => $bet, 'bonus' => $bonus];
    }

    public function step_time()
    {
        return !Carbon::now("Europe/Moscow")->diff($this->step_time)->invert
            ? $this->step_time->diffInSeconds()
            : 0;
    }

    public function images()
    {
        $images = [];
        $this->img_1 ? $images[] = ['img' => $this->img_1, 'alt' => $this->alt_1] : null;
        $this->img_2 ? $images[] = ['img' => $this->img_2, 'alt' => $this->alt_2] : null;
        $this->img_3 ? $images[] = ['img' => $this->img_3, 'alt' => $this->alt_3] : null;
        $this->img_4 ? $images[] = ['img' => $this->img_4, 'alt' => $this->alt_4] : null;
        return $images;
    }

    public static function auctionsForHomePage()
    {
        $data = self::where('active', true)
            ->orderByRaw('(CASE WHEN `status` = 1 THEN `start` END) ASC,
                              (CASE WHEN `status` = 2 THEN `start` END) DESC')
            ->get();
        $auctions = new Collection;
        $user = Auth::user();
        foreach ($data as $auction) {
            $images = $auction->images();
            $favorite = $user ? $auction
                ->userFavorites()
                ->where('id', $user->id)
                ->exists() : false;
            $autoBid = $user ? $auction
                ->autoBid()
                ->where('auto_bids.user_id', $user->id)
                ->first() : false;
            $winner = $auction->winner();
            $ordered = $user ? $user->auctionOrder()
                ->where('auction_id', $auction->id)
                ->where('status', Order::SUCCESS)
                ->exists() : false;
            $auctions->push([
                'id' => $auction->id,
                'top' => $auction->top,
                'favorite' => $favorite,
                'autoBid' => $autoBid ? $autoBid->count : null,
                'short_desc' => $auction->short_desc,
                'status' => $auction->status,
                'images' => $images,
                'title' => $auction->title,
                'step_price' => $auction->step_price(),
                'step_price_info' => $auction->step_price,
                'start_price' => $auction->start_price(),
                'exchange' => $auction->exchange,
                'buy_now' => (bool)$auction->buy_now,
                'full_price' => number_format($auction->full_price(Auth::id()), 1),
                'exchangeBetBonus' => $auction->exchangeBetBonus(Auth::id()),
                'bid_seconds' => $auction->bid_seconds,
                'step_time' => $auction->step_time ? $auction->step_time() : null,
                'start' => $auction->start(),
                'winner' => $winner->nickname,
                'my_win' => (isset($user) && ($auction->status === Auction::STATUS_FINISHED || $auction->status === Auction::STATUS_ERROR))
                    ? $winner->nickname === $user->nickname
                    : false,
                'error' => (isset($user) && $auction->status === Auction::STATUS_ERROR)
                    ? $winner->nickname === $user->nickname
                    : false,
                'ordered' => $ordered,
                'price' => $auction->price(),
                'end' => $auction->end ? $auction->end->format('Y-m-d H:i:s') : null,
            ]);
        }
        return $auctions->sortByDesc(function ($auction) {
            $top = (int)$auction['top'];
            $favorite = (int)$auction['favorite'];
            $my_win = $auction['ordered'] ? 0 : (int)$auction['my_win'];
            if ($auction['status'] === Auction::STATUS_ACTIVE) $status = 2;
            elseif ($auction['status'] === Auction::STATUS_PENDING) $status = 1;
            else $status = 0;
            return "{$my_win}{$top}{$favorite}{$status}";
        });
    }

    public static function auctionPage($id)
    {
        /** @var Auction $auction */
        $auction = self::query()->where('active', true)->findOrFail($id);
        $data = self::auctionsForHomePage()->firstWhere('id', '=', $id);
        $data['desc'] = $auction->desc;
        $data['specify'] = $auction->specify;
        $data['terms'] = $auction->terms;
        $data['bids'] = $auction->bid()->orderBy('id', 'desc')->take(5)->get();
        $data['bet'] = $data['bonus'] = 0;
        if (Auth::check() && $user = Auth::user()) {
            $bid = $user->bid->where('auction_id', $id);
            $data['bet'] = $bid->sum('bet');
            $data['bonus'] = $bid->sum('bonus');
        }
        return $data;
    }

    /**
     * @return mixed
     */
    public function bidDataForUser()
    {
        $data['user'] = [];
        $data['auction'] = [];
        try {
            $last = $this->bid()->orderBy('bids.id', 'desc')
                ->first(['bids.user_id', 'bids.auction_id', 'bids.nickname', 'bids.price']);
            if (!is_null($last) && $user = User::query()->find($last->user_id)) {
                $balance = $user->balance();
                $bid = $user->bid->where('auction_id', $last->auction_id);
                $autoBid = $user->autoBid()->where('auto_bids.auction_id', $last->auction_id)->first();
                $data['user']['id'] = $user->id;
                $data['user']['bet'] = $balance->bet;
                $data['user']['bonus'] = $balance->bonus;
                $data['user']['auction_bet'] = $bid->sum('bet');
                $data['user']['auction_bonus'] = $bid->sum('bonus');
                $data['user']['full_price'] = number_format($this->full_price($user->id), 1) . ' руб';
                $data['user']['auto_bid'] = $autoBid ? $autoBid->count : null;
            }
            $data['auction']['id'] = $last->auction_id;
            $data['auction']['step_time'] = $this->step_time();
            $data['auction']['nickname'] = $last->nickname;
            $data['auction']['price'] = $last->price . ' руб';
            $data['auction']['tr'] = $this->bidTable();
        } catch (Throwable $throwable) {
            Log::error('bidDataForUser - ' . $throwable->getMessage());
        }
        return $data;
    }
}
