<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Mail
 *
 * @property int $id
 * @property string|null $driver
 * @property string|null $host
 * @property int|null $port
 * @property string|null $from_address
 * @property string|null $from_name
 * @property string|null $encryption
 * @property string|null $username
 * @property string|null $password
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|Mail newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Mail newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Mail query()
 * @method static \Illuminate\Database\Eloquent\Builder|Mail whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Mail whereDriver($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Mail whereEncryption($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Mail whereFromAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Mail whereFromName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Mail whereHost($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Mail whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Mail wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Mail wherePort($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Mail whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Mail whereUsername($value)
 * @mixin \Eloquent
 */
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
