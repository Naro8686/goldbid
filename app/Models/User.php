<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use stdClass;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'is_admin', 'has_ban', 'nickname', 'avatar',
        'fname', 'lname', 'mname',
        'phone', 'postcode', 'region', 'city',
        'street', 'gender', 'birthday', 'sms_code',
        'sms_verified_at', 'email', 'email_verified_at',
        'password', 'remember_token', 'is_online',
        'email_code','email_code_verified'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'sms_verified_at' => 'datetime',
        'email_code_verified' => 'datetime',
        'is_online' => 'datetime',
        'birthday' => 'date',
        'is_admin' => 'boolean',
        'has_ban' => 'boolean',
    ];
    protected $with = ['balanceHistory'];

    public function avatar()
    {
        return $this->avatar ?? asset('site/img/settings/noavatar.png');
    }

    public function isActive(): bool
    {
        return $this->is_online >= Carbon::now();
    }

    /**
     * @param string|null $phone
     * @return string|string[]|null
     */
    public static function unsetPhoneMask(?string $phone)
    {
        return is_null($phone) ? $phone : str_replace(['+', '(', ')', '-'], '', $phone);
    }

    public static function setPhoneMask(string $phone)
    {
        return preg_replace('/^[7]{1}([\d]{3})([\d]{3})([\d]{2})([\d]{2})$/', '+7($1)$2-$3-$4',$phone);
    }
    public function login()
    {
        return self::setPhoneMask($this->phone);
    }

    public function balanceHistory()
    {
        return $this->hasMany(Balance::class);
    }

    public function balance()
    {
        $balances = new stdClass;
        $balances->bet = (int)($this->balanceHistory->where('type', Balance::REPLENISHMENT)->sum('bets') - $this->balanceHistory->where('type', Balance::DEFRAYAL)->sum('bets'));
        $balances->bonus = (int)($this->balanceHistory->where('type', Balance::REPLENISHMENT)->sum('bonuses') - $this->balanceHistory->where('type', Balance::DEFRAYAL)->sum('bonuses'));

        return $balances;
    }
    public function subscribe()
    {
        return $this->belongsToMany(Mailing::class,'subscriptions','user_id','mailing_id');
    }
}
