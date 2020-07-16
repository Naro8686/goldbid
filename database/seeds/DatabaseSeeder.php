<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
         $this->call([
             AdminSeeder::class,
             SlugSeeder::class,
             FooterLinksSeeder::class,
             SliderSeeder::class,
             StepSeeder::class,
             ReviewsSeed::class,
             PackagesSeed::class,
             MailingSeeder::class,
             TestSeeder::class,
         ]);
    }
}
