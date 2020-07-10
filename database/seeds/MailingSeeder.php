<?php

use App\Models\Mailing;
use App\Models\User;
use Illuminate\Database\Seeder;

class MailingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $url = config('app.url');
        $mailings = [
            //["type" => Mailing::REGISTRATION, "subject" => "Регистрация на сайте {$url}", "text" => "Благодарим вас за регистрацию на сайте {$url}! Для входа на сайт используйте следующие данные #login# #password#"],
            ["type" => Mailing::MAIL_CONFIRM, "subject" => "Подтверждение почты", "text" => "Здравствуйте #nickname# ваш код для подтверждения аккаунта на сайте {$url} #code#"],
            ["type" => Mailing::CHECKOUT, "subject" => "Оформления товара", "text" => "Вы успешно оформили заказ №#order#  в ближайшее время мы его отправим , о чем сообщить дополнительно"],
            ["type" => Mailing::VICTORY, "subject" => "Вы победили в аукционе!", "text" => "Поздравляем ! Вы победили в аукционе #auction#! Не забудьте оформить заказ в течения 72 часов."],

            ["title" => "о рекомендуемых аукционах", "subject" => "Внимание! Новый аукцион!", "text" => "Через 24 часа начнется аукцион , где будет разыгрываться путевка в Италию ! не упустите свой шанс ! @ссылка"],
            ["title" => "о новых акциях ", "subject" => "У нас новые акции!", "text" => "На этой недели за все двойные Бонусы !"],
        ];
        foreach ($mailings as $mailing)
            Mailing::create($mailing);
    }
}
