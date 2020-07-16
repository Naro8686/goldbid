<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

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
}
