<?php

namespace App\Models;

use App\Models\Auction\Auction;
use App\Models\Auction\Bid;
use App\Settings\Setting as SettingApp;
use Carbon\Carbon;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

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
     * @return mixed
     */
    public function routeNotificationForSmscru()
    {
        return $this->phone;
    }

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

    public function userCard()
    {
        $subscribe = [];
        foreach (Mailing::ads() as $ads) {
            $subscribe[] = [
                'title' => $ads->title,
                'subscribe' => $this->subscribe->where('id', $ads->id)->first() ? 'Да' : 'Нет',
            ];
        }
        return collect([
            'id' => $this->id,
            'created_at' => $this->created_at ? $this->created_at->format('Y-m-d') : '',
            'nickname' => $this->nickname,
            'lname' => $this->lname,
            'fname' => $this->fname,
            'mname' => $this->mname,
            'gender' => $this->gender === 'female' ? 'Ж' : 'М',
            'city' => $this->city,
            'postcode' => $this->postcode,
            'region' => $this->region,
            'street' => $this->street,
            'birthday' => $this->birthday ? $this->birthday->format('Y-m-d') : '',
            'phone' => $this->login(),
            'email' => $this->email,
            'win' => 0,
            'participation' => 0,
            'has_ban' => $this->has_ban ? 'да' : 'нет',
            'bet' => $this->balance()->bet,
            'bonus' => $this->balance()->bonus,
            'payment' => $this->paymentType(),
            'ccnum' => $this->ccnum,
            'reason' => [
                Balance::PRIZE_REASON,
                Balance::RETURN_REASON,
            ],
            'referred' => $this->referred()->count() ? 'ID ' . $this->referred()->first()->id : '',
            'referrals' => $this->referrals()->get()->implode('id', ','),
            'mailing' => $subscribe,
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

    public function favorite()
    {
        return $this->belongsToMany(Auction::class, 'favorites', 'user_id', 'auction_id');
    }

    public function bid()
    {
        return $this->hasMany(Bid::class);
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
        return $this->payment_type ? SettingApp::paymentType($this->payment_type) : null;
    }
}
