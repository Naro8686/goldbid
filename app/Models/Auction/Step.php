<?php

namespace App\Models\Auction;

use Illuminate\Database\Eloquent\Model;

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
