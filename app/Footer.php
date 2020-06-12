<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

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
