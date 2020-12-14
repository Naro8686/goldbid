<?php

namespace App\Models\Auction;

use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Collection;
use App\Models\Bots\AuctionBot;
use Illuminate\Support\Carbon;
use App\Models\User;
use Throwable;
use Log;
use DB;

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
 * @property int $start_price
 * @property int $full_price
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
 * @property-read \App\Models\Auction\Order $userOrder
 */
class Auction extends Model
{
    const BET_RUB = 10;

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
        'start_price' => 'integer',
        'full_price' => 'integer',
        'step_price' => 'integer',
        'bid_seconds' => 'integer',
        'step_time' => 'datetime',
        'exchange' => 'boolean',
        'buy_now' => 'boolean',
        'top' => 'boolean',
        'active' => 'boolean',
    ];
    public static $selectAuctionFillable = [
        'auctions.id', 'auctions.title', 'auctions.short_desc',
        'auctions.img_1', 'auctions.img_2', 'auctions.img_3', 'auctions.img_4',
        'auctions.alt_1', 'auctions.alt_2', 'auctions.alt_3', 'auctions.alt_4',
        'auctions.start_price', 'auctions.full_price', 'auctions.bot_shutdown_count',
        'auctions.bot_shutdown_price', 'auctions.bid_seconds', 'auctions.step_time',
        'auctions.step_price', 'auctions.start', 'auctions.end', 'auctions.exchange',
        'auctions.buy_now', 'auctions.top', 'auctions.active', 'auctions.status',
        'auctions.product_id', 'auctions.created_at', 'auctions.updated_at',
    ];

    /**
     * @return bool
     */
    public function jobExists(): bool
    {
        return $this->whereHas('bots', function ($query) {
            $query->where('auction_bots.auction_id', '=', $this->id)->where('auction_bots.status', '=', AuctionBot::WORKED);
        })->orWhereHas('autoBid', function ($query) {
            $query->where('auto_bids.auction_id', '=', $this->id)->where('auto_bids.status', '=', AutoBid::WORKED);
        })->exists();
    }

    /**
     * @param int $num
     * @return int
     */
    public function botCountBet(int $num = 1): int
    {
        return $this->bid()->where('bot_num', '=', $num)->count();
    }

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

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function bots()
    {
        return $this->hasMany(AuctionBot::class);
    }


    /**
     * @param int $num
     * @param string[] $select
     * @return \Illuminate\Database\Eloquent\Builder|Model|\Illuminate\Database\Eloquent\Relations\HasMany|AuctionBot|null
     */
    public function botNum(int $num, $select = ['*'])
    {
        return $this->bots()->whereHas('bot', function ($query) use ($num) {
            $query->where('number', '=', $num);
        })->first($select);
    }

    public function shutdownBots(): bool
    {
        $count = 0;
        $stopBotOne = (int)$this->bot_shutdown_count;
        $stopBotTwoThree = $sumBids = (int)$this->bot_shutdown_price;
        if ($this->bid()->take(1)->exists()) {
            if (!is_null($this->botNum(1))) {
                $count += $stopBotOne;
            }
            if (!is_null($this->botNum(2)) || !is_null($this->botNum(3))) {
                $sumBids = (int)($this->bid()->sum('bet') * self::BET_RUB);
            }
        }

        return ($count < 1 && $sumBids >= $stopBotTwoThree);
    }

    public function bid()
    {
        return $this->hasMany(Bid::class);
    }

    /**
     * @param $nickname
     * @return \Illuminate\Database\Eloquent\HigherOrderBuilderProxy|Carbon|mixed
     */
    public function lastBid($nickname)
    {
        $bid = $this->bid()->where('nickname', '=', $nickname)->orderByDesc('bids.id')->first(['bids.created_at']);
        return !is_null($bid) ? $bid->created_at : Carbon::now("Europe/Moscow")->subSeconds($this->step_time());
    }

    public function userOrder()
    {
        //return $this->belongsToMany(User::class,Order::class,'auction_id', 'user_id');
        return $this->hasOne(Order::class);
    }

    public static function moneyFormat($price, $optional = false, $text = ' руб')
    {
        $text = $optional ? $text : '';
        $format = number_format($price, 1, ',', ' ');
        return "{$format}{$text}";
    }

    /**
     * @return bool
     */
    public function finished()
    {
        return !is_null($this->step_time) ? (bool)Carbon::now("Europe/Moscow")->diff($this->step_time->addSecond())->invert : false;
    }

    public function bidTable()
    {
        $tr = "";
        foreach ($this->bid()->orderBy('id', 'desc')->take(5)->get() as $bid) {
            $price = self::moneyFormat($bid->price, true);
            $tr .= "<tr>
                      <td>{$price}</td>
                      <td>{$bid->nickname}</td>
                      <td>{$bid->created_at->format('H:i:s')}</td>
                    </tr>";
        }
        return $tr;
    }

    public function winner()
    {
        return ($this->bid()->orderByDesc('id')->take(1)->first() ?? new Bid);
    }

    public function status()
    {
        if ((int)$this->status === self::STATUS_ACTIVE) $text = 'Активный';
        if ((int)$this->status === self::STATUS_PENDING) $text = 'Скоро начало';
        if ((int)$this->status === self::STATUS_ERROR) $text = 'Ошибка';
        if ((int)$this->status === self::STATUS_FINISHED) {
            $bid = $this->bid()
                ->where('win', '=', true)
                ->orderByDesc('id')
                ->take(1)
                ->first(['is_bot']);
            if (!is_null($bid)) $text = "Победил " . (($bid->is_bot) ? 'бот' : 'игрок');
            else $text = "Не состоялся";
        }
        return $text ?? '';
    }

    public function price()
    {
        return is_null($this->winner()->price)
            ? $this->start_price
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
        $price = $this->bid()->doesntExist()
            ? $this->start_price
            : ($this->step_price / 100);
        return round($price * Auction::BET_RUB);
    }

    public function step_price()
    {
        return ($this->step_price / 100);
    }

    public function start_price()
    {
        return self::moneyFormat($this->start_price);
    }

    public function full_price($user_id = null)
    {
        if ($this->full_price <= 0) return 0;
        $user_bet = !is_null($user_id) ? $this->bid()->where('user_id', $user_id)->sum('bet') : 0;
        $new_price = ((int)$this->full_price - ($user_bet * self::BET_RUB));
        return ($new_price <= 0) ? 1 : $new_price;
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
        return collect([
            'auction_count' => self::count(),
            'active_count' => self::where('active', true)->count()
        ]);
    }

    public function auctionCard()
    {
        return $this->bid()
            ->where('bids.is_bot', false)
            ->selectRaw('bids.user_id, SUM(bids.bet) AS bet, SUM(bids.bonus) AS bonus')
            ->groupBy('bids.user_id')
            ->get()
            ->transform(function ($bid) {
                return [
                    'bonus' => $bid->bonus,
                    'bet' => $bid->bet,
                    'user_id' => $bid->user_id,
                ];
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
        if ($this->bid()->take(1)->exists() && $this->status === self::STATUS_FINISHED && !is_null($user_id)) {
            $bet = (int)round($this->full_price() / self::BET_RUB);
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

    public function transformAuction($userID = null)
    {
        $winner = $this->winner();
        $images = $this->images();
        $favorite = false;
        $ordered = false;
        $autoBid = null;
        if (!is_null($userID)) {
            $favorite = $this->userFavorites()
                ->where('users.id', '=', $userID)
                ->take(1)
                ->exists();
            $autoBid = $this->autoBid()
                ->where('user_id', '=', $userID)
                ->take(1)
                ->first();
            $ordered = $this->userOrder()
                ->where([
                    ['user_id', '=', $userID],
                    ['status', '=', Order::SUCCESS]
                ])
                ->take(1)
                ->exists();
        }

        return collect([
            'id' => $this->id,
            'top' => $this->top,
            'favorite' => $favorite,
            'autoBid' => !is_null($autoBid) ? $autoBid->count : null,
            'short_desc' => $this->short_desc,
            'status' => $this->status,
            'images' => $images,
            'title' => $this->title,
            'step_price' => $this->step_price(),
            'step_price_info' => $this->step_price,
            'start_price' => $this->start_price(),
            'exchange' => $this->exchange,
            'buy_now' => (bool)$this->buy_now,
            'full_price' => self::moneyFormat($this->full_price($userID)),
            'exchangeBetBonus' => $this->exchangeBetBonus($userID),
            'bid_seconds' => $this->bid_seconds,
            'step_time' => $this->step_time ? $this->step_time() : null,
            'start' => $this->start(),
            'winner' => $winner->nickname,
            'my_win' => (!is_null($userID) && ($this->status === Auction::STATUS_FINISHED || $this->status === Auction::STATUS_ERROR))
                ? ($winner->user_id === $userID)
                : false,
            'error' => (!is_null($userID) && $this->status === Auction::STATUS_ERROR)
                ? $winner->user_id === $userID
                : false,
            'ordered' => $ordered,
            'price' => self::moneyFormat($this->price()),
            'end' => $this->end ? $this->end->format('Y-m-d H:i:s') : null,
        ]);
    }

    public static function auctionsForHomePageQuery($userID = null)
    {
        $userID = !is_null($userID) ? $userID : "NULL";
        $select = implode(', ', self::$selectAuctionFillable);
        return self::where('active', '=', true)
            ->leftJoinSub("SELECT `favorites`.`auction_id` FROM `favorites` WHERE `user_id` = $userID GROUP BY `favorites`.`auction_id`", 'userFavorites', 'auctions.id', '=', 'userFavorites.auction_id')
            ->leftJoinSub("SELECT `orders`.`auction_id` FROM `orders` WHERE `orders`.`user_id` = $userID AND `orders`.`status` = '" . Order::SUCCESS . "' GROUP BY `orders`.`auction_id`", 'userOrders', 'auctions.id', '=', 'userOrders.auction_id')
            ->leftJoinSub("SELECT `bids`.`auction_id`, `bids`.`user_id` FROM `bids` WHERE `bids`.`win` = 1 GROUP BY `bids`.`auction_id`, `bids`.`user_id`", 'lastBet', 'auctions.id', '=', 'lastBet.auction_id')
            ->orderByRaw("IF((lastBetUserID IS NOT NULL) AND (lastBetUserID = $userID) AND (userOrders.auction_id IS NULL),1,0) DESC")
            ->orderByDesc('top')
            ->orderByRaw('IF(userFavorites.auction_id IS NULL, 0, 1) DESC')
            ->orderByRaw('(CASE WHEN `auctions`.`status` = ' . self::STATUS_ACTIVE . ' THEN `auctions`.`status` END) DESC,
                              (CASE WHEN `auctions`.`status` = ' . self::STATUS_PENDING . ' THEN `auctions`.`status` END) DESC,
                              (CASE WHEN `auctions`.`status` = ' . self::STATUS_FINISHED . ' THEN `auctions`.`status` END) ASC,
                              (CASE WHEN `auctions`.`status` = ' . self::STATUS_ERROR . ' THEN `auctions`.`status` END) ASC')
            ->orderByRaw('(CASE WHEN `auctions`.`status` = ' . self::STATUS_PENDING . ' THEN `auctions`.`start` END) ASC,
                              (CASE WHEN `auctions`.`status` = ' . self::STATUS_ACTIVE . ' THEN `auctions`.`start` END) DESC')
            ->selectRaw($select . ', IF((((auctions.status = ' . self::STATUS_FINISHED . ') OR (auctions.status = ' . self::STATUS_ERROR . ')) AND ' . $userID . ' IS NOT NULL),lastBet.user_id,NULL) AS lastBetUserID');
    }

    public static function auctionsForHomePage(int $paginate = 25)
    {
        $userID = Auth::id();
        $itemsPaginated = self::auctionsForHomePageQuery($userID)->paginate($paginate);
        $itemsTransformed = collect($itemsPaginated->items())->transform(function (Auction $auction) use ($userID) {
            return $auction->transformAuction($userID)->toArray();
        });
        return new LengthAwarePaginator(
            $itemsTransformed,
            $itemsPaginated->total(),
            $itemsPaginated->perPage(),
            $itemsPaginated->currentPage(), [
            'path' => request()->url(),
            'query' => ['page' => $itemsPaginated->currentPage()]
        ]);
    }

    public static function auctionPage($id)
    {
        $auction = self::where('active', '=', true)->findOrFail($id);
        $data = $auction->statusChangeData(Auth::id());
        $data['desc'] = $auction->desc;
        $data['specify'] = $auction->specify;
        $data['terms'] = $auction->terms;
        return $data;
    }

    public function statusChangeData($userID = null)
    {
        $data = $this->transformAuction($userID);
        $data['bids'] = $this->bid()->orderByDesc('bids.id')
            ->take(5)
            ->get(['nickname', 'price', 'created_at'])->transform(function ($bid) {
                $res['nickname'] = $bid->nickname;
                $res['created_at'] = $bid->created_at->format('H:i:s');
                $res['price'] = self::moneyFormat($bid->price, true);
                return $res;
            });
        $data['bet'] = $data['bonus'] = 0;
        if (!is_null($userID) && $user = User::find($userID)) {
            $bid = $user->bid()->where('auction_id', $this->id);
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
        $data['user'] = $data['auction'] = [];
        try {
            /** @var Bid $last */
            $last = $this->bid()->orderByDesc('bids.id')
                ->first(['bids.user_id', 'bids.auction_id', 'bids.nickname', 'bids.price']);
            if (!is_null($last) && $user = User::find($last->user_id)) {
                $balance = $user->balance();
                $bid = $user->bid()->where('bids.auction_id', $last->auction_id);
                $autoBid = $user->autoBid()->where('auto_bids.auction_id', $last->auction_id)->first(['auto_bids.count']);
                $data['user']['id'] = $user->id;
                $data['user']['bet'] = $balance->bet;
                $data['user']['bonus'] = $balance->bonus;
                $data['user']['auction_bet'] = $bid->sum('bet');
                $data['user']['auction_bonus'] = $bid->sum('bonus');
                $data['user']['full_price'] = self::moneyFormat($this->full_price($user->id), true);
                $data['user']['auto_bid'] = !is_null($autoBid) ? $autoBid->count : null;
            }
            if (!is_null($last)) {
                $data['auction']['id'] = $last->auction_id;
                $data['auction']['step_time'] = $this->step_time();
                $data['auction']['nickname'] = $last->nickname;
                $data['auction']['price'] = self::moneyFormat($last->price, true);
            }
            $data['auction']['tr'] = $this->bidTable();
        } catch (Throwable $throwable) {
            Log::error('bidDataForUser - ' . $throwable->getMessage());
        }
        return $data;
    }
}
