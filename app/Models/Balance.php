<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Balance extends Model
{
    protected $fillable = ['type', 'description', 'bets', 'bonuses', 'user_id'];
    const REPLENISHMENT = 0;
    const DEFRAYAL = 1;
    public function user()
    {
        return $this->belongsToMany(User::class);
    }
}
