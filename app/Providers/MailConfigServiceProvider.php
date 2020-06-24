<?php

namespace App\Providers;

use App\Models\Mail;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;

class MailConfigServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        if (Schema::hasTable('mails')) {
            if ($mail = Mail::query()->first()) {
                $config = [
                    'driver' => $mail->driver ?? 'smtp',
                    'host' => $mail->host ?? env('MAIL_HOST', 'smtp.mailgun.org'),
                    'port' => $mail->port ?? env('MAIL_PORT', 587),
                    'from' => [
                        'address' => $mail->from_address ?? env('MAIL_FROM_ADDRESS','goldbid24@gmail.com'),
                        'name' => $mail->from_name??env('MAIL_FROM_NAME','GoldBid')
                    ],
                    'encryption' => $mail->encryption ?? env('MAIL_ENCRYPTION', 'tls'),
                    'username' => $mail->username ?? env('MAIL_USERNAME'),
                    'password' => $mail->getPassword() ?? env('MAIL_PASSWORD'),
                    'sendmail' => '/usr/sbin/sendmail -bs',
                    'pretend' => false,
                ];
                Config::set('mail', $config);
            }
        }
    }
}
