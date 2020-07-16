<?php

namespace App\Models\Auction;

use App\Models\User;
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

    public function bid()
    {
        return $this->hasMany(Bid::class);
    }

    public function winner()
    {
        return $this->bid()
                ->latest('created_at')
                ->first() ?? new Bid;
    }

    public function status()
    {
        $text = '';
        $winner = $this->winner()->nickname ?? '';
        if ((int)$this->status === self::STATUS_ACTIVE) $text = 'Активный';
        if ((int)$this->status === self::STATUS_PENDING) $text = 'Скоро начало';
        if ((int)$this->status === self::STATUS_FINISHED) $text = "Победил {$winner}";
        if ((int)$this->status === self::STATUS_ERROR) $text = 'Ошибка';
        return $text;
    }

    public function price()
    {
        return $this->winner()->price ?? $this->start_price();
    }

    public function step_price()
    {
        return number_format($this->step_price / 100, 1);
    }

    public function start_price()
    {
        return number_format($this->start_price, 1);
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
        $user_id = Auth::id();

        foreach ($data as $auction) {
            $favorite = $auction
                ->userFavorites()
                ->where('id', $user_id)
                ->exists();
            $winner = $auction->winner();
            $auctions->push([
                'id' => $auction->id,
                'top' => $auction->top,
                'favorite' => $favorite,
                'short_desc' => $auction->short_desc,
                'status' => $auction->status,
                'img' => $auction->img_1,
                'alt' => $auction->alt_1,
                'title' => $auction->title,
                'step_price' => $auction->step_price(),
                'start_price' => $auction->start_price(),
                'exchange' => $auction->exchange,
                'buy_now' => (bool)$auction->full_price,
                'full_price' => $auction->full_price,
                'bid_seconds' => $auction->bid_seconds,
                'step_time' => $auction->step_time ? $auction->step_time() : null,
                'start' => $auction->start(),
                'winner' => $winner->nickname,
                'my_win' => (Auth::check() && $auction->status === Auction::STATUS_FINISHED) ? $winner->user_id === Auth::id() : false,
                'price' => $auction->price(),
                'end' => $auction->end ? $auction->end->format('Y-m-d H:i:s') : null,
            ]);
        }
        //dd($auctions);
        return $auctions->sortByDesc(function ($auction) {
            $top = (int)$auction['top'];
            $favorite = (int)$auction['favorite'];
            $my_win = (int)$auction['my_win'];
            if ($auction['status'] === Auction::STATUS_ACTIVE)
                $status = 3;
            elseif ($auction['status'] === Auction::STATUS_PENDING)
                $status = 2;
            elseif ($auction['status'] === Auction::STATUS_FINISHED)
                $status = 1;
            else $status = 0;
            return "{$my_win}-{$top}-{$favorite}-{$status}";
        });
    }

    public static function auctionPage($id)
    {
        $data = self::query()
            ->where('active', true)
            ->findOrFail($id);
        dd($data->bid()->get());
        $images = [];
        $data->img_1 ? $images[] = ['img' => $data->img_1, 'alt' => $data->alt_1] : null;
        $data->img_2 ? $images[] = ['img' => $data->img_2, 'alt' => $data->alt_2] : null;
        $data->img_3 ? $images[] = ['img' => $data->img_3, 'alt' => $data->alt_3] : null;
        $data->img_4 ? $images[] = ['img' => $data->img_4, 'alt' => $data->alt_4] : null;
        return collect([
            'id' => $data->id,
            'images' => $images,
            'title' => $data->title,
            'desc' => $data->desc,
            'status' => $data->status,
            'specify' => $data->specify,
            'terms' => $data->terms,
            'step_price' => number_format($data->step_price / 100, 1),
            'start_price' => number_format($data->start_price, 1),
            'exchange' => $data->exchange,
            'buy_now' => (bool)$data->full_price,
            'full_price' => $data->full_price,
            'bid_seconds' => $data->bid_seconds,
            'step_time' => $data->step_time ? $data->step_time() : null,
            'start' => $data->start(),
            'end' => $data->end ? $data->end->format('Y-m-d H:i:s') : null,
        ]);
    }
}
