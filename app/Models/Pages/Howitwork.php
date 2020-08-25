<?php

namespace App\Models\Pages;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Pages\Howitwork
 *
 * @property int $id
 * @property string $image
 * @property string|null $alt
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|Howitwork newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Howitwork newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Howitwork query()
 * @method static \Illuminate\Database\Eloquent\Builder|Howitwork whereAlt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Howitwork whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Howitwork whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Howitwork whereImage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Howitwork whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Howitwork extends Model
{
    protected $fillable = ['image','alt'];
}
