<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Mailing
 *
 * @property int $id
 * @property int $type
 * @property string|null $title
 * @property string $subject
 * @property string|null $text
 * @property bool $visibly
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\User[] $users
 * @property-read int|null $users_count
 * @method static \Illuminate\Database\Eloquent\Builder|Mailing newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Mailing newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Mailing query()
 * @method static \Illuminate\Database\Eloquent\Builder|Mailing whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Mailing whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Mailing whereSubject($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Mailing whereText($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Mailing whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Mailing whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Mailing whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Mailing whereVisibly($value)
 * @mixin \Eloquent
 */
class Mailing extends Model
{
    const ADS = 0;
    const REGISTRATION = 1;
    const MAIL_CONFIRM = 2;
    const CHECKOUT = 3;
    const VICTORY = 4;
    protected $fillable = ['type', 'title', 'subject', 'text', 'visibly'];
    protected $casts = ['visibly' => 'boolean', 'type' => 'integer'];

    public static function ads($select = ['id', 'type', 'title', 'subject', 'text'])
    {
        return self::where('type', self::ADS)
            ->get($select);
    }

    public static function no_ads(int $type = null)
    {
        $mails = self::where('type', '<>', self::ADS);
        if ($type) $mails->where('type', $type);
        return $mails
            ->get(['id', 'type', 'subject', 'text', 'visibly']);
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'subscriptions', 'mailing_id', 'user_id');
    }

    public function textReplace(array $data = [])
    {
        $patterns = [];
        $replacements = [];
        $patterns[0] = '/#nickname#/i';
        $patterns[1] = '/#code#/i';
        $patterns[2] = '/#order#/i';
        $patterns[3] = '/#auction#/i';

        $replacements[0] = $data['nickname'] ?? '';
        $replacements[1] = $data['email_code'] ?? '';
        $replacements[2] = $data['order_num'] ?? '';
        $replacements[3] = $data['auction'] ?? '';

        $replacements = array_map(function ($array) {
            return "<b>{$array}</b>";
        }, $replacements);
        ksort($patterns);
        ksort($replacements);
        return preg_replace($patterns, $replacements, $this->attributes['text']);
    }
}
