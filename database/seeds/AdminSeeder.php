<?php

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

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
            'is_admin'=>true,
            'nickname' => 'SuperAdmin',
            'phone' => '70000000000',
            'email' => 'GoldBid24@gmail.com',
            'password' => Hash::make('secret'),
            'email_code' => rand(1000,9999),
            'email_code_verified' => now(),
        ]);
    }
}
