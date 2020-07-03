<?php


namespace App\Settings;


use App\Models\Mail;
use App\Models\Pages\Footer;
use App\Models\User;
use App\Models\Setting as Config;
use App\Models\Pages\Page;
use Exception;
use Illuminate\Support\Str;

use stdClass;


class Setting
{
    protected $page;

    /**
     * Setting constructor.
     * @param string|null $slug
     */
    public function __construct($slug)
    {
        $slug = Str::slug($slug);

        $this->page = new stdClass;

        $this->page->footer = new stdClass;
        $this->page->meta = Page::whereSlug($slug)->first() ?? Page::query()->firstOrNew();
        $this->page->footer->social = Footer::query()
            ->where('show', true)
            ->where('social', true)
            ->orderBy('position')
            ->get();
        $this->page->footer->left = Footer::query()
            ->where('show', true)
            ->where('social', false)
            ->where('float', 'left')
            ->with('page')
            ->orderBy('position')
            ->get();
        $this->page->footer->right = Footer::query()
            ->where('show', true)
            ->where('social', false)
            ->where('float', 'right')
            ->with('page')
            ->orderBy('position')
            ->get();

    }

    public function page()
    {
        return $this->page;
    }

    public function mete()
    {
        return $this->page->meta;
    }

    /**
     * @return stdClass
     */
    public function content()
    {
        $this->page->content = $this->page->meta->content;
        return $this->page;
    }

    /**
     * @param string $slug
     * @return \Illuminate\Contracts\Routing\UrlGenerator|string
     */
    public static function dynamicURL(string $slug)
    {
        $slug = Str::slug($slug);
        $page = Page::whereSlug($slug)->with('footer')->first();
        return url($page->footer->link);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Model|object|null
     */
    public static function siteContacts()
    {
        $data = self::siteConfig()->first(['phone_number', 'email']);
        $data->phone = $data->phone_number ? User::setPhoneMask($data->phone_number) : '';
        $data->name = config('app.name') ?? null;
        return $data;
    }

    /**
     * @param int|null $id
     * @return array|mixed
     */
    public static function feedbackTheme(?int $id)
    {
        $themes = [
            ['id' => 1, 'value' => 'Регистрация'],
            ['id' => 2, 'value' => 'Аккаунт'],
            ['id' => 3, 'value' => 'Баланс'],
            ['id' => 4, 'value' => 'Игровой процесс'],
            ['id' => 5, 'value' => 'Прочее'],
        ];
        foreach ($themes as $theme) {
            if ($theme['id'] === $id) {
                $themes = $theme['value'];
                break;
            }
        }
        return (is_int($id) && is_array($themes)) ? null : $themes;
    }

    public static function paymentType(?int $id)
    {
        $payments = [
            ['id' => 1, 'value' => 'Банковская карта'],
            ['id' => 2, 'value' => 'Yandex деньги'],
            ['id' => 3, 'value' => 'Qiwi кошелек'],
        ];
        foreach ($payments as $payment) {
            if ($payment['id'] === $id) {
                $payments = $payment['value'];
                break;
            }
        }
        return (is_int($id) && is_array($payments)) ? null : $payments;
    }

    public static function paymentCoupon(?int $id)
    {
        $payments = [
            ['id' => 1, 'value' => 'visa', 'img' => asset('site/img/payment/visa.png')],
            ['id' => 2, 'value' => 'mastercard', 'img' => asset('site/img/payment/mastercard.png')],
            ['id' => 3, 'value' => 'maestro', 'img' => asset('site/img/payment/Maestro.png')],
            ['id' => 4, 'value' => 'mir', 'img' => asset('site/img/payment/Mir-logo.jpg')],
            ['id' => 5, 'value' => 'sberbank', 'img' => asset('site/img/payment/sberbank.jpg')],
            ['id' => 6, 'value' => 'yandex', 'img' => asset('site/img/payment/yandex.png')],
            ['id' => 7, 'value' => 'qiwi', 'img' => asset('site/img/payment/qiwi.png')],
            ['id' => 8, 'value' => 'mts', 'img' => asset('site/img/payment/mts.png')],
            ['id' => 9, 'value' => 'megafon', 'img' => asset('site/img/payment/megafon.png')],
            ['id' => 10, 'value' => 'beeline', 'img' => asset('site/img/payment/beeline.png')],
            ['id' => 11, 'value' => 'tele2', 'img' => asset('site/img/payment/tele2.png')],

        ];
        foreach ($payments as $payment) {
            if ($payment['id'] === $id) {
                $payments = $payment['value'];
                break;
            }
        }
        return (is_int($id) && is_array($payments)) ? null : $payments;
    }

    public static function orderNumCoupon(int $num)
    {
        $data_format = now()->format('ymd-Hi');
        return "{$data_format}-{$num}";
    }

    public static function mailConfig()
    {
        return Mail::query()->first() ?? Mail::query()->create(['driver' => null]);
    }

    public static function siteConfig()
    {
        return Config::query()->first() ?? Config::create(['phone_number' => '70000000000', 'email' => 'goldbid24@gmail.com']);
    }

    public static function emailRandomCode()
    {
        return mt_rand(100000, 999999);
    }

    /**
     * @param $length
     * @return string
     */
    public static function randomNumber($length)
    {
        $result = '';
        for ($i = 0; $i < $length; $i++)
            $result .= mt_rand(0, 9);
        return $result;
    }
}
