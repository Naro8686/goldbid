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
            'password' => Hash::make('secret'),
        ]);
    }
}
