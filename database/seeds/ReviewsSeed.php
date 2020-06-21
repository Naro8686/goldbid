<?php

use App\Models\Pages\Review;
use Illuminate\Database\Seeder;

class ReviewsSeed extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $reviews = [
            ['image' => 'site/img/settings/reviews/reviews1.jpg','title'=>'Оля г. Симферополь ','description'=>'Друзья, это все правда. Это реальный шанс сэкономить на покупке крутейших гаджетов. Участвую с удовольствием'],
            ['image' => 'site/img/settings/reviews/reviews2.jpg','title'=>'Данила г. Иркутск','description'=>'Это нереально круто! Я купил MAC за копейки! Ребята, вы в это верите вообще?? Моя самая выгодная покупка в жизни!'],
            ['image' => 'site/img/settings/reviews/reviews3.jpg','title'=>'Олеся г. Москва','description'=>'Про аукцион мне рассказала подруга, она давно там отоваривается. Я сразу подумала - развод. А потом ради интереса купила 10 ставок. И сразу выиграла! Азарт и адреналин просто зашкаливают! Буду участвовать ещё.'],
            ['image' => 'site/img/settings/reviews/reviews4.jpg','title'=>'Михаил г. Воронеж','description'=>'Ребята, даже не сомневайтесь. Самый крутой и выгодный шопинг в интернете.'],
            ['image' => 'site/img/settings/reviews/reviews5.jpg','title'=>'Аслан г. Сочи','description'=>'Аааа! Я в шоке! Айфон за 1800 рублей... Я вас люблю GoldBid!'],
        ];
        foreach ($reviews as $review)
            Review::create($review);
    }
}
