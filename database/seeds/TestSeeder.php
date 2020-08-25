<?php

use App\Models\Auction\Auction;
use App\Models\Auction\Company;
use App\Models\Auction\Product;
use App\Models\Bots\BotName;
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
        if (!is_dir(public_path('site/img/product'))) mkdir(public_path('site/img/product'), 0777,true);
        factory(User::class, 100)->create();
        factory(Product::class, 2)->create();
        factory(BotName::class, 20)->create();
    }
}
