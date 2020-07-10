<?php

use App\Models\Auction\Auction;
use App\Models\Auction\Company;
use App\Models\Auction\Product;
use App\Models\User;
use Illuminate\Database\Seeder;

class TestSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(User::class, 100)->create();
        factory(Product::class, 5)->create();
    }
}
