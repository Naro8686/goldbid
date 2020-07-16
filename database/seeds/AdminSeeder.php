<?php

use App\Models\Setting;
use App\Models\User;
use App\Settings\Setting as Config;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        User::create([
            'is_admin' => true,
            'nickname' => 'SuperAdmin',
            'phone' => '70000000000',
            'email' => 'GoldBid24@gmail.com',
            'password' => Hash::make('secret'),
            'email_code' => Config::emailRandomCode(),
            'email_code_verified' => now(),
            'remember_token' => Str::random(10),
        ]);
        Setting::query()->firstOrCreate(['phone_number' => '70000000000', 'email' => 'goldbid24@gmail.com']);
    }
}
