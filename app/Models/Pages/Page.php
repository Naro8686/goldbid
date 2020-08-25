<?php

namespace App\Models\Pages;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Pages\Page
 *
 * @property int $id
 * @property string $slug
 * @property string|null $title
 * @property string|null $keywords
 * @property string|null $description
 * @property string|null $content
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Pages\Footer|null $footer
 * @method static \Illuminate\Database\Eloquent\Builder|Page newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Page newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Page query()
 * @method static \Illuminate\Database\Eloquent\Builder|Page whereContent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Page whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Page whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Page whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Page whereKeywords($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Page whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Page whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Page whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Page extends Model
{
    protected $fillable = [
        'slug', 'title', 'keywords', 'description','content'
    ];

    public function title()
    {
        return $this->title
            ? $this->title . ' - ' . config('app.name', 'GoldBid')
            : config('app.name', 'GoldBid');
    }
    public function footer(){
        return $this->hasOne(Footer::class);
    }
}
