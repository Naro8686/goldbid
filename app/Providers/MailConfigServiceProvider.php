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
            if ($mail = Mail::first()) {
                Config::set('mail', [
                    'driver' => $mail->driver ?? 'smtp',
                    'host' => $mail->host ?? 'smtp.mailgun.org',
                    'port' => $mail->port ?? 465,
                    'encryption' => $mail->encryption ?? 'tls',
                    'username' => $mail->username,
                    'password' => $mail->getPassword(),
                    'sendmail' => '/usr/sbin/sendmail -bs',
                    'from' => [
                        'address' => $mail->from_address,
                        'name' => $mail->from_name
                    ],
                    'pretend' => false,
                ]);
            }
        }
    }
}
