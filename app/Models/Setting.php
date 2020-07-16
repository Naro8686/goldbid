<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $fillable = ['phone_number', 'email', 'site_enabled', 'storage_period_month'];
    protected $casts = [
        'site_enabled' => 'boolean',
    ];
}
