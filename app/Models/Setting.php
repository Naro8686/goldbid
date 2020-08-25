<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Setting
 *
 * @property int $id
 * @property string|null $phone_number
 * @property string|null $email
 * @property bool $site_enabled
 * @property string|null $storage_period_month
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|Setting newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Setting newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Setting query()
 * @method static \Illuminate\Database\Eloquent\Builder|Setting whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Setting whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Setting whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Setting wherePhoneNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Setting whereSiteEnabled($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Setting whereStoragePeriodMonth($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Setting whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Setting extends Model
{
    protected $fillable = ['phone_number', 'email', 'site_enabled', 'storage_period_month'];
    protected $casts = [
        'site_enabled' => 'boolean',
    ];
}
