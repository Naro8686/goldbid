<?php


namespace App\Settings;


use App\Models\Mail;
use App\Models\Pages\Footer;
use App\Models\Setting as ConfigSite;
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
    public function __construct(?string $slug)
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

    public function page(): stdClass
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
    public function content(): stdClass
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


    public static function siteContacts(): stdClass
    {
        $data = new stdClass();
        $siteConfig = self::siteConfig()->first(['phone_number', 'email']);
        $data->phone = is_null($siteConfig) ? null : $siteConfig->phone_number;
        $data->email = is_null($siteConfig) ? null : $siteConfig->email;
        $data->name = config('app.name') ?? null;
        return $data;
    }

    /**
     * @param int|null $id
     * @return array|mixed
     */
    public static function feedbackTheme(?int $id): ?array
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

    public static function paymentType(?int $id): ?array
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

    public static function paymentCoupon(?int $id): ?array
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

    public static function orderNumCoupon(int $num): string
    {
        $data_format = now("Europe/Moscow")->format('ymd-His');
        return "{$data_format}-{$num}";
    }

    public static function orderNumAuction(int $user_id): string
    {
        $data_format = now("Europe/Moscow")->format('ymd-His');
        return "{$data_format}-{$user_id}";
    }

    public static function mailConfig()
    {
        return Mail::first() ?? Mail::create(['driver' => null]);
    }

    public static function siteConfig()
    {
        return ConfigSite::first() ?? ConfigSite::create(['phone_number' => '70000000000', 'email' => 'goldbid24@gmail.com']);
    }

    public static function emailRandomCode(): string
    {
        return self::randomNumber(6);
    }

    /**
     * @param $length
     * @return string
     */
    public static function randomNumber($length): string
    {
        $result = '';
        for ($i = 0; $i < $length; $i++) $result .= mt_rand(0, 9);
        return $result;
    }

    public static function timezone()
    {
        try {
            $ip = request()->ip();
            $ipInfo = file_get_contents('http://ip-api.com/json/' . $ip);
            $ipInfo = json_decode($ipInfo);
            return $ipInfo->timezone;
        } catch (Exception $exception) {
            return config('app.timezone');
        }

    }
}
