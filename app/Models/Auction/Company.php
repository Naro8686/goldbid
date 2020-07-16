<?php

namespace App\Models\Auction;

use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    protected $fillable = ['name'];

    public function product()
    {
        return $this->hasMany(Product::class);
    }
}
