<?php

namespace App\Listeners;

use App\Events\BetEvent;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;


class BetListener implements ShouldQueue
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {

    }

    /**
     * Handle the event.
     *
     * @param BetEvent $event
     * @return BetEvent
     */
    public function handle(BetEvent $event)
    {
        return $event;
    }
}
