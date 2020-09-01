<?php

namespace App\Models\Bots;

use Illuminate\Database\Eloquent\Model;

class Bot extends Model
{
    protected $fillable = ['number', 'is_active', 'time_to_bet', 'change_name', 'num_moves', 'num_moves_other_bot'];
    protected $casts = ['is_active' => 'boolean', 'number' => 'integer'];
}
