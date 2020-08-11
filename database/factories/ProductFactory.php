<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Auction\Category;
use App\Models\Auction\Company;
use App\Models\Auction\Product;
use Faker\Generator as Faker;

$factory->define(Product::class, function (Faker $faker) {
    return [
        'title' => $faker->text(15),
        'short_desc' => $faker->text(10),
        'desc' => $faker->realText(1500),
        'specify' => $faker->realText(1500),
        'terms' => $faker->realText(1500),
        'exchange' => rand(0, 1),
        'buy_now' => rand(0, 1),
        'top' => rand(0, 1),
        'visibly' => false,
        'start_price' => $faker->numberBetween(1, 10),
        'full_price' => $faker->numberBetween(1, 10000),
        'bot_shutdown_count' => $faker->numberBetween(100, 10000) . '-' . $faker->numberBetween(10000, 100000),
        'bot_shutdown_price' => $faker->numberBetween(100, 10000) . '-' . $faker->numberBetween(10000, 100000),
        'step_time' => $faker->numberBetween(10, 20),
        'step_price' => $faker->numberBetween(10, 20),
        'to_start' => $faker->numberBetween(1, 10),
        'category_id' => function () {
            return factory(Category::class)->create()->id;
        },
        'company_id' => function () {
            return factory(Company::class)->create()->id;
        },
        'img_1' => 'site/img/product/' . $faker->image('public/site/img/product', 500, 500, null, false),
    ];
});
