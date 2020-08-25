<?php

namespace App\Models;

use App\Models\Pages\Package;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\CouponOrder
 *
 * @property int $id
 * @property string $order
 * @property string|null $payment_type
 * @property int $user_id
 * @property int|null $coupon_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read Package|null $coupon
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder|CouponOrder newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CouponOrder newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CouponOrder query()
 * @method static \Illuminate\Database\Eloquent\Builder|CouponOrder whereCouponId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CouponOrder whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CouponOrder whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CouponOrder whereOrder($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CouponOrder wherePaymentType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CouponOrder whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CouponOrder whereUserId($value)
 * @mixin \Eloquent
 */
class CouponOrder extends Model
{
    protected $fillable = ['order', 'payment_type', 'user_id', 'coupon_id'];

    public function coupon()
    {
        return $this->belongsTo(Package::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
