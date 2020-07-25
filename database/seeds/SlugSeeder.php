<?php

use App\Models\Pages\Page;
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
            ['slug' => '/'],
            ['slug' => '/how-it-works', 'title' => 'Как это работает'],
            ['slug' => '/coupon', 'title' => 'Пополнить баланс'],
            ['slug' => '/reviews', 'title' => 'Отзывы'],
            ['slug' => '/feedback', 'title' => 'Обратная связь'],
            ['slug' => '/login', 'title' => 'Вход'],
            ['slug' => '/register', 'title' => 'Регистрация'],
            ['slug' => '/cabinet', 'title' => 'Личный кабинет'],
            ['slug' => '/order', 'title' => 'Оформление заказа'],

            ['slug' => '/regulations', 'title' => 'Правила участия в аукционе', 'content' => config('footer.regulation')],
            ['slug' => '/terms-of-use', 'title' => 'Пользовательское соглашение', 'content' => config('footer.regulation')],
            ['slug' => '/personal-data', 'title' => 'Персональные данные', 'content' => config('footer.regulation')],
            ['slug' => '/privacy-policy', 'title' => 'Политика конфиденциальности', 'content' => config('footer.regulation')],
            ['slug' => '/cookie-terms-of-use', 'title' => 'Файлы cookie', 'content' => config('footer.regulation')],
            ['slug' => '/offer', 'title' => 'Публичная оферта', 'content' => config('footer.regulation')],

            ['slug' => '/payment-methods', 'title' => 'Способы оплаты товаров', 'content' => config('footer.regulation')],
            ['slug' => '/delivery', 'title' => 'Доставка товаров', 'content' => config('footer.regulation')],
            ['slug' => '/return-of-goods-and-payment', 'title' => 'Возврат товара и оплаты', 'content' => config('footer.regulation')],
            ['slug' => '/guarantee', 'title' => 'Гарантии', 'content' => config('footer.regulation')],
            ['slug' => '/requisite', 'title' => 'Реквизиты', 'content' => config('footer.regulation')],
        ];

        foreach ($urls as $url) {
            $url['slug'] = Str::slug($url['slug']);
            Page::create($url);
        }

    }
}
