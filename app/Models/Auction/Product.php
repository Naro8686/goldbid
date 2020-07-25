<?php

namespace App\Models\Auction;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

class Product extends Model
{
    protected $fillable = [
        'title','short_desc', 'desc', 'specify', 'terms',
        'img_1', 'img_2', 'img_3', 'img_4',
        'alt_1', 'alt_2', 'alt_3', 'alt_4',
        'start_price', 'full_price', 'bot_shutdown_price',
        'step_time', 'step_price', 'to_start', 'exchange','buy_now',
        'top', 'visibly', 'company_id', 'category_id',
    ];
    protected $casts = [
        'to_start' => 'integer',
        'step_price' => 'integer',
        'step_time' => 'integer',
        'exchange' => 'boolean',
        'buy_now' => 'boolean',
        'top' => 'boolean',
        'visibly' => 'boolean',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function auction()
    {
        return $this->hasMany(Auction::class);
    }

    public static function info()
    {
        $products = self::all();
        return collect([
            'product_count' => $products->count(),
            'visibly_count' => $products->where('visibly', true)->count()
        ]);
    }

    public static function data()
    {
        $data = Product::query()->with(['category', 'company', 'auction'])->get();
        $products = new Collection;
        foreach ($data as $product) {
            $products->push([
                'id' => $product->id,
                'img_1' => $product->img_1,
                'alt_1' => $product->alt_1,
                'title' => $product->title,
                'company' => $product->company ? $product->company->name : 'Другой',
                'category' => $product->category ? $product->category->name : 'Другой',
                'start_price' => $product->start_price,
                'full_price' => $product->full_price,
                'bot_shutdown_price' => $product->bot_shutdown_price,
                'step_time' => $product->step_time,
                'step_price' => $product->step_price,
                'to_start' => $product->to_start,
                'exchange' => $product->exchange,
                'buy_now' => $product->buy_now,
                'top' => $product->top,
                'visibly' => $product->visibly,
            ]);
        }
        return $products;
    }
}
