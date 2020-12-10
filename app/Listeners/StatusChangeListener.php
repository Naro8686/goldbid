<?php

namespace App\Listeners;

use App\Events\StatusChangeEvent;
use Illuminate\Contracts\Queue\ShouldQueue;

class StatusChangeListener implements ShouldQueue
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param StatusChangeEvent $event
     * @return StatusChangeEvent
     */
    public function handle(StatusChangeEvent $event)
    {
        return $event;
    }
}
