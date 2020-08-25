<?php

namespace App\Models\Auction;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Auction\Step
 *
 * @property int $id
 * @property int $step
 * @property string|null $type
 * @property bool|null $for_winner
 * @property string|null $text
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|Step newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Step newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Step query()
 * @method static \Illuminate\Database\Eloquent\Builder|Step whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Step whereForWinner($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Step whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Step whereStep($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Step whereText($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Step whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Step whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Step extends Model
{
    const PRODUCT = 'product';
    const MONEY = 'money';
    const BET = 'bet';
    protected $fillable = ['step', 'type', 'for_winner', 'text'];
    protected $casts = [
        'step' => 'integer',
        'for_winner' => 'boolean'
    ];

    /**
     * @param array $data
     * @return string
     */
    public function textReplace(array $data = []): string
    {
        $patterns = $replacements = [];
        $patterns[0] = '/#title#/i';
        $patterns[1] = '/#bet#/i';
        $patterns[2] = '/#bonus#/i';
        $patterns[3] = '/#money#/i';

        $replacements[0] = $data['title'] ?? '';
        $replacements[1] = $data['bet'] ?? '';
        $replacements[2] = $data['bonus'] ?? '';
        $replacements[3] = $data['money'] ?? '';

        $replacements = array_map(function ($array) {
            return "<b>{$array}</b>";
        }, $replacements);
        ksort($patterns);
        ksort($replacements);
        return preg_replace($patterns, $replacements, $this->attributes["text"]);
    }
}
