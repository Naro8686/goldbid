<?php

use App\Models\Bots\BotName;
use Illuminate\Database\Seeder;
use App\Models\Bots\Bot;

class BotsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $bots = [
            ['number' => 1, 'is_active' => true, 'time_to_bet' => '10-4', 'change_name' => '20-50', 'num_moves' => null, 'num_moves_other_bot' => null],
            ['number' => 2, 'is_active' => true, 'time_to_bet' => '9-2', 'change_name' => null, 'num_moves' => '15-65', 'num_moves_other_bot' => '2-10'],
            ['number' => 3, 'is_active' => true, 'time_to_bet' => '0', 'change_name' => null, 'num_moves' => '10-30', 'num_moves_other_bot' => '3-6'],
        ];
        factory(BotName::class, 20)->create();
        foreach ($bots as $bot) Bot::create($bot);

    }
}
