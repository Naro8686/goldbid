<?php

namespace App\Models\Pages;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Pages\Footer
 *
 * @property int $id
 * @property int|null $page_id
 * @property string|null $name
 * @property bool $show
 * @property int $position
 * @property string|null $icon
 * @property bool $social
 * @property string|null $link
 * @property string|null $float
 * @property-read \App\Models\Pages\Page|null $page
 * @method static \Illuminate\Database\Eloquent\Builder|Footer newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Footer newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Footer query()
 * @method static \Illuminate\Database\Eloquent\Builder|Footer whereFloat($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Footer whereIcon($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Footer whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Footer whereLink($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Footer whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Footer wherePageId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Footer wherePosition($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Footer whereShow($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Footer whereSocial($value)
 * @mixin \Eloquent
 */
class Footer extends Model
{
    protected $fillable = ['page_id','name','show','position','icon','social','link','float'];
    public $timestamps = false;
    protected $with = ['page'];
    protected $casts = [
        'show' => 'boolean',
        'social' => 'boolean',
    ];
    public function page(){
        return $this->belongsTo(Page::class);
    }
}
