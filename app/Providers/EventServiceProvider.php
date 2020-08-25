<?php

namespace App\Providers;

use App\Events\BetEvent;
use App\Events\StatusChangeEvent;
use App\Listeners\AutoBidListener;
use App\Listeners\BetListener;
use App\Listeners\StatusChangeListener;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Support\Facades\Event;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        StatusChangeEvent::class => [
            StatusChangeListener::class,
        ],
        BetEvent::class => [
            BetListener::class,
            AutoBidListener::class,
        ]
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();
    }
}
