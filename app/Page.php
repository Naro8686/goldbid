<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Page extends Model
{
    protected $fillable = [
        'slug', 'title', 'keywords', 'description','content'
    ];
    //protected $with = ['footer'];

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
