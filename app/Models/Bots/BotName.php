<?php

namespace App\Models\Bots;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Bots\BotName
 *
 * @property int $id
 * @property string $name
 * @method static \Illuminate\Database\Eloquent\Builder|BotName newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|BotName newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|BotName query()
 * @method static \Illuminate\Database\Eloquent\Builder|BotName whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BotName whereName($value)
 * @mixin \Eloquent
 */
class BotName extends Model
{
    protected $fillable = ['name'];
    public $timestamps = false;
}
