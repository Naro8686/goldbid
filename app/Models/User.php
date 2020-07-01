<?php

namespace App\Models;

use App\Settings\Setting as SettingApp;
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
        'email_code', 'email_code_verified',
        'payment_type', 'ccnum',
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
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'is_online' => 'datetime',
        'birthday' => 'date',
        'is_admin' => 'boolean',
        'has_ban' => 'boolean',
    ];
    protected $with = ['balanceHistory'];
    /**
     * @var int
     */
    public $bet;
    /**
     * @var int
     */
    public $bonus;

    /**
     * @return mixed|string
     */
    public function avatar()
    {
        return $this->avatar ?? asset('site/img/settings/noavatar.png');
    }

    /**
     * @return bool
     */
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

    /**
     * @param string $phone
     * @return string|string[]|null
     */
    public static function setPhoneMask(string $phone)
    {
        return preg_replace('/^[7]{1}([\d]{3})([\d]{3})([\d]{2})([\d]{2})$/', '+7($1)$2-$3-$4', $phone);
    }

    /**
     * @return string|string[]|null
     */
    public function login()
    {
        return self::setPhoneMask($this->phone);
    }

    public function regDate()
    {
        return $this->created_at;
    }

    public function birthdayDate()
    {
        return $this->birthday;
    }

    public static function info()
    {
        $users = User::query()->where('is_admin', false)->get();
        return collect([
            'count' => $users->count(),
            'active' => 0,
            'banned' => $users->where('has_ban', true)->count(),
            'online' => $users->where('is_online', '>=', now())->count()
        ]);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function balanceHistory()
    {
        return $this->hasMany(Balance::class);
    }

    /**
     * @return $this
     */
    public function balance()
    {
        $this->bet = (int)($this->balanceHistory->where('type', Balance::PLUS)->sum('bet') - $this->balanceHistory->where('type', Balance::MINUS)->sum('bet'));
        $this->bonus = (int)($this->balanceHistory->where('type', Balance::PLUS)->sum('bonus') - $this->balanceHistory->where('type', Balance::MINUS)->sum('bonus'));
        return $this;
    }

    public function subscribe()
    {
        return $this->belongsToMany(Mailing::class, 'subscriptions', 'user_id', 'mailing_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function referrals()
    {
        return $this->belongsToMany(self::class, Referral::class, 'referred_by', 'referral_id')
            ->withPivot('referral_bonus', 'created_at', 'updated_at');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function referred()
    {
        return $this->belongsToMany(self::class, Referral::class, 'referral_id', 'referred_by')
            ->withPivot('referral_bonus', 'created_at', 'updated_at');
    }

    /**
     * @return bool
     */
    public function fullProfile()
    {
        $data = array_filter([
            $this->fname, $this->lname, $this->mname,
            $this->phone, $this->postcode, $this->region,
            $this->city, $this->street, $this->gender,
            $this->birthday, $this->email_code_verified,
        ], static function ($var) {
            return $var === null;
        });

        return !(boolean)count($data);
    }

    public function couponOrder()
    {
        return $this->hasMany(CouponOrder::class);
    }
    public function paymentType()
    {
        return SettingApp::paymentType($this->payment_type);
    }
}
