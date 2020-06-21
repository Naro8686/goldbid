<?php

namespace App\Models\Pages;

use Illuminate\Database\Eloquent\Model;

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
