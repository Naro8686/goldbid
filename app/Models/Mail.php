<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Mail extends Model
{

    protected $fillable = [
        'driver',
        'host',
        'port',
        'from_address',
        'from_name',
        'encryption',
        'username',
        'password',
    ];
//    protected $hidden = [
//        'password',
//    ];


    /**
     * @return false|mixed|string
     */
    public function getPassword()
    {
        $password = null;
        if (isset($this->password)){
            $password = base64_decode($this->password,true) ?? $this->password;
        }
        return $password;
    }
}
