<?php

use App\Page;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class SlugSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $urls = [
            ['slug'=>'/'],
            ['slug'=>'/how-it-works','title'=>'Как это работает'],
            ['slug'=>'/coupon','title'=>'Пополнить баланс'],
            ['slug'=>'/reviews','title'=>'Отзывы'],
            ['slug'=>'/feedback','title'=>'Обратная связь'],
            ['slug'=>'/login','title'=>'Вход'],
            ['slug'=>'/register','title'=>'Регистрация'],

            ['slug'=>'/regulations','title'=>'Правила участия в аукционе'],
            ['slug'=>'/terms-of-use','title'=>'Пользовательское соглашение'],
            ['slug'=>'/personal-data','title'=>'Персональные данные'],
            ['slug'=>'/privacy-policy','title'=>'Политика конфиденциальности'],
            ['slug'=>'/cookie-terms-of-use','title'=>'Файлы cookie'],
            ['slug'=>'/offer','title'=>'Публичная оферта'],

            ['slug'=>'/payment-methods','title'=>'Способы оплаты товаров'],
            ['slug'=>'/delivery','title'=>'Доставка товаров'],
            ['slug'=>'/return-of-goods-and-payment','title'=>'Возврат товара и оплаты'],
            ['slug'=>'/guarantee','title'=>'Гарантии'],
            ['slug'=>'/requisite','title'=>'Реквизиты'],
        ];

        foreach ($urls as $url) {
            $url['slug'] = Str::slug($url['slug']);
            Page::create($url);
        }

    }
}
