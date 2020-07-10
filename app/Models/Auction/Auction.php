<?php

namespace App\Models\Auction;

use App\Models\User;
use App\Settings\Setting;
use Carbon\CarbonInterval;
use Carbon\CarbonPeriod;
use Carbon\CarbonTimeZone;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;

class Auction extends Model
{
    public const STATUS_PENDING = 1;
    public const STATUS_ACTIVE = 2;
    public const STATUS_FINISHED = 3;
    public const STATUS_ERROR = 4;
    protected $fillable = [
        'title', 'short_desc', 'desc', 'specify', 'terms',
        'img_1', 'img_2', 'img_3', 'img_4',
        'alt_1', 'alt_2', 'alt_3', 'alt_4',
        'start_price', 'full_price', 'bot_shutdown_price',
        'step_time', 'step_price', 'start', 'end', 'exchange',
        'top', 'active', 'product_id', 'status', 'bid_seconds'
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
        'top' => 'boolean',
        'active' => 'boolean',
        //'full_price' => 'float',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function userFavorites()
    {
        return $this->belongsToMany(User::class, 'favorites', 'auction_id', 'user_id');
    }

    public function status()
    {
        $text = '';
        if ((int)$this->status === self::STATUS_ACTIVE) $text = 'Активный';
        if ((int)$this->status === self::STATUS_PENDING) $text = 'Скоро начало';
        if ((int)$this->status === self::STATUS_FINISHED) $text = 'Победил ...';
        if ((int)$this->status === self::STATUS_ERROR) $text = 'Ошибка';
        return $text;
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
                'bet' => 0,
                'bonus' => 0,
                'bot' => 0,
                'start' => $auction->start->format('Y-m-d H:i:s'),
                'end' => $auction->end ? $auction->start->format('Y-m-d H:i:s') : null,
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

    public function start()
    {
        return !Carbon::now()->diff($this->start)->invert
            ? $this->start->diffInSeconds()
            : 0;
    }

    public function step_time()
    {
        return !Carbon::now()->diff($this->step_time)->invert
            ? $this->step_time->diffInSeconds()
            : 0;
    }

    public static function auctionsForHomePage()
    {
        $data = self::query()->where('active', true)->get();
        $auctions = new Collection;
        foreach ($data as $auction) {
            $favorite = $auction
                ->userFavorites()
                ->where('id', Auth::id())
                ->exists();
            $auctions->push([
                'id' => $auction->id,
                'top' => $auction->top,
                'favorite' => $favorite,
                'short_desc' => $auction->short_desc,
                'status' => $auction->status,
                'img' => $auction->img_1,
                'alt' => $auction->alt_1,
                'title' => $auction->title,
                'step_price' => number_format($auction->step_price / 100, 1),
                'start_price' => number_format($auction->start_price, 1),
                'exchange' => $auction->exchange,
                'buy_now' => (bool)$auction->full_price,
                'full_price' => $auction->full_price,
                'bid_seconds' => $auction->bid_seconds,
                'step_time' => $auction->step_time ? $auction->step_time() : null,
                'start' => $auction->start(),
                'end' => $auction->end ? $auction->end->format('Y-m-d H:i:s') : null,
            ]);
        }
        //dd($auctions);
        return $auctions->sortByDesc(function ($auction) {
            $top = (int)$auction['top'];
            $favorite = (int)$auction['favorite'];
            return "{$top}-{$favorite}";
        });
    }

}
