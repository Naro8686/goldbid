<?php

namespace App\Listeners;

use App\Events\BetEvent;
use App\Jobs\DuplicateBidJob;
use App\Models\Auction\Order;
use Exception;
use Illuminate\Support\Facades\Log;

//use Illuminate\Contracts\Queue\ShouldQueue;
//use Illuminate\Queue\InteractsWithQueue;

class AutoBidListener
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
     * @param BetEvent $event
     * @return BetEvent
     */
    public function handle(BetEvent $event)
    {
        try {
            $auction = $event->auction;
            if (isset($auction) && $auction->bid()->exists()) DuplicateBidJob::dispatch($auction);
        } catch (Exception $exception) {
            Log::warning('DuplicateBidJob: ' . $exception->getMessage());
        }
        return $event;
    }
}
