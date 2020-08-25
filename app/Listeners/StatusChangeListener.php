<?php

namespace App\Listeners;

use App\Events\StatusChangeEvent;
use App\Jobs\CreateAuctionJob;
use App\Jobs\DeleteAuctionInNotWinner;
use App\Mail\MailingSendMail;
use App\Models\Auction\Auction;
use App\Models\Mailing;
use App\Models\User;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

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
