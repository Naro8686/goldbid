<?php

namespace App\Models;

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
        'is_admin', 'nickname', 'avatar',
        'fname', 'lname', 'mname',
        'phone', 'postcode', 'region', 'city',
        'street', 'gender', 'birthday', 'sms_code',
        'sms_verified_at', 'email', 'email_verified_at', 'referral_link',
        'password','remember_token'
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
        'birthday' => 'date',
        'is_admin' => 'boolean',
    ];
    public function avatar(){
        return $this->avatar??asset('site/img/settings/noavatar.png');
    }
}
