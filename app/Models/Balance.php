<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Balance
 *
 * @property int $id
 * @property int $type
 * @property string|null $reason
 * @property int $bet
 * @property int $bonus
 * @property int|null $user_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\User[] $user
 * @property-read int|null $user_count
 * @method static \Illuminate\Database\Eloquent\Builder|Balance newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Balance newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Balance query()
 * @method static \Illuminate\Database\Eloquent\Builder|Balance whereBet($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Balance whereBonus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Balance whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Balance whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Balance whereReason($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Balance whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Balance whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Balance whereUserId($value)
 * @mixin \Eloquent
 */
class Balance extends Model
{
    protected $fillable = ['type', 'reason', 'bet', 'bonus', 'user_id'];
    const PLUS = 0;
    const MINUS = 1;
    const PURCHASE_REASON = 'Покупка';
    const WIN_REASON = 'Выигрыш';
    const EXCHANGE_REASON = 'Обмен';
    const PRIZE_REASON = 'Награда';
    const RETURN_REASON = 'Возврат';
    const PURCHASE_BONUS_REASON = 'За покупку ставок';
    const EXCHANGE_BONUS_REASON = 'За обмен товара';
    const REGISTRATION_BONUS_REASON = 'За регистрацию';
    const REFERRAL_BONUS_REASON = 'За реферала';
    const ADMIN = 'admin';

    public function user()
    {
        return $this->belongsToMany(User::class);
    }

    public static function bonusCount(string $key): int
    {
        $bonus = [
            self::PURCHASE_BONUS_REASON => 10,
            self::EXCHANGE_BONUS_REASON => 20,
            self::REGISTRATION_BONUS_REASON => 30,
            self::REFERRAL_BONUS_REASON => 40,
        ];
        return $bonus[$key] ?? 0;
    }

    /**
     * @return string[]
     */
    public static function reasonArray():array
    {
        return [
            self::PURCHASE_REASON,
            self::WIN_REASON,
            self::EXCHANGE_REASON,
            self::PRIZE_REASON,
            self::RETURN_REASON,
            self::PURCHASE_BONUS_REASON,
            self::EXCHANGE_BONUS_REASON,
            self::REGISTRATION_BONUS_REASON,
            self::REFERRAL_BONUS_REASON,
            self::ADMIN,
        ];
    }
}
