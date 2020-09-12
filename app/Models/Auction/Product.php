<?php

namespace App\Models\Auction;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

/**
 * App\Models\Auction\Product
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
 * @property string $start_price начальная цена (руб)
 * @property string $full_price полная стоимость (руб)
 * @property string|null $bot_shutdown_count количество ставок выключения бота 1 (руб)
 * @property string|null $bot_shutdown_price цена выключения бота 2,3 (руб)
 * @property int $step_time сек.
 * @property int $step_price
 * @property int $to_start мин.
 * @property bool $exchange
 * @property bool $buy_now
 * @property bool $top
 * @property bool $visibly
 * @property int|null $company_id
 * @property int|null $category_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Auction\Auction[] $auction
 * @property-read int|null $auction_count
 * @property-read \App\Models\Auction\Category|null $category
 * @property-read \App\Models\Auction\Company|null $company
 * @method static \Illuminate\Database\Eloquent\Builder|Product newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Product newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Product query()
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereAlt1($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereAlt2($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereAlt3($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereAlt4($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereBotShutdownCount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereBotShutdownPrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereBuyNow($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereCategoryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereCompanyId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereDesc($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereExchange($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereFullPrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereImg1($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereImg2($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereImg3($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereImg4($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereShortDesc($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereSpecify($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereStartPrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereStepPrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereStepTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereTerms($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereToStart($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereTop($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereVisibly($value)
 * @mixin \Eloquent
 */
class Product extends Model
{
    protected $fillable = [
        'title', 'short_desc', 'desc', 'specify', 'terms',
        'img_1', 'img_2', 'img_3', 'img_4',
        'alt_1', 'alt_2', 'alt_3', 'alt_4',
        'start_price', 'full_price',
        'bot_shutdown_price', 'bot_shutdown_count',
        'step_time', 'step_price', 'to_start', 'exchange', 'buy_now',
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
                'short_desc' => $product->short_desc,
                'company' => $product->company ? $product->company->name : 'Другой',
                'category' => $product->category ? $product->category->name : 'Другой',
                'start_price' => $product->start_price,
                'full_price' => $product->full_price,
                'bot_shutdown_count' => $product->bot_shutdown_count,
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
