<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Referral
 *
 * @property int $id
 * @property int $referred_by
 * @property int|null $referral_id
 * @property int $referral_bonus
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|Referral newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Referral newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Referral query()
 * @method static \Illuminate\Database\Eloquent\Builder|Referral whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Referral whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Referral whereReferralBonus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Referral whereReferralId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Referral whereReferredBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Referral whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Referral extends Model
{
    protected $fillable = ['referred_by', 'referral_id', 'referral_bonus'];

    protected $casts = [
        'referral_bonus' => 'integer',
        'referred_by' => 'integer',
        'referral_id' => 'integer',
    ];
}
