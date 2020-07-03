<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Mailing extends Model
{
    const ADS = 0;
    const REGISTRATION = 1;
    const MAIL_CONFIRM = 2;
    const CHECKOUT = 3;
    const VICTORY = 4;
    protected $fillable = ['type', 'title', 'subject', 'text', 'visibly'];
    protected $casts = ['visibly' => 'boolean', 'type' => 'integer'];

    public static function ads()
    {
        return self::query()->where('type', self::ADS)
            ->get(['id', 'type', 'title', 'subject', 'text']);
    }

    public static function no_ads(int $type = null)
    {
        $mails = self::query()->where('type', '<>', self::ADS);
        if ($type)
            $mails->where('type', $type);
        return $mails
            ->get(['id', 'type', 'subject', 'text', 'visibly']);
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'subscriptions', 'mailing_id', 'user_id');
    }

    public function textReplace(object $data)
    {
        $patterns = [];
        $replacements = [];
        $patterns[0] = '/#nickname#/i';
        $patterns[1] = '/#login#/i';
        //$patterns[2] = '/#password#/i';
        $patterns[3] = '/#code#/i';
        $patterns[4] = '/#order#/i';
        $patterns[5] = '/#auction#/i';

        $replacements[0] = $data->nickname;
        $replacements[1] = $data->login();
        //$replacements[2] = $data->password;
        $replacements[3] = $data->email_code;
        $replacements[4] = 'order';
        $replacements[5] = 'auction';
        $replacements = array_map(function ($array) {
            return "<b>{$array}</b>";
        }, $replacements);
        ksort($patterns);
        ksort($replacements);
        return preg_replace($patterns, $replacements, $this->text);
    }
}
