<?php

namespace App\Models\Bots;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Bots\Bot
 *
 * @property int $id
 * @property int $number bot 1,2,3
 * @property bool $is_active bot 1,2,3
 * @property string $time_to_bet bot 1,2,3
 * @property string|null $change_name bot 1
 * @property string|null $num_moves bot 2,3
 * @property string|null $num_moves_other_bot bot 2,3
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|Bot newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Bot newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Bot query()
 * @method static \Illuminate\Database\Eloquent\Builder|Bot whereChangeName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Bot whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Bot whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Bot whereIsActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Bot whereNumMoves($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Bot whereNumMovesOtherBot($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Bot whereNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Bot whereTimeToBet($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Bot whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Bot extends Model
{
    protected $fillable = ['number', 'is_active', 'time_to_bet', 'change_name', 'num_moves', 'num_moves_other_bot'];
    protected $casts = ['is_active' => 'boolean', 'number' => 'integer'];
}
