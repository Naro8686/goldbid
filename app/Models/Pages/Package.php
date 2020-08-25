<?php

namespace App\Models\Pages;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Pages\Package
 *
 * @property int $id
 * @property string $image
 * @property string|null $alt
 * @property int|null $bet
 * @property int|null $bonus
 * @property int|null $price
 * @property bool $visibly
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|Package newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Package newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Package query()
 * @method static \Illuminate\Database\Eloquent\Builder|Package whereAlt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Package whereBet($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Package whereBonus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Package whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Package whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Package whereImage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Package wherePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Package whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Package whereVisibly($value)
 * @mixin \Eloquent
 */
class Package extends Model
{
    protected $fillable = ['image', 'alt', 'bet', 'bonus', 'price', 'visibly'];

    protected $casts = [
        'visibly' => 'boolean',
        'bet' => 'integer',
        'bonus' => 'integer',
        'price' => 'integer',
    ];
}
