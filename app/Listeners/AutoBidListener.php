<?php

namespace App\Listeners;

use App\Models\Auction\Order;
use App\Events\BetEvent;
use App\Jobs\DuplicateBidJob;
use Illuminate\Support\Facades\Log;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Throwable;

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


    public function handle(BetEvent $event)
    {
//        try {
//            $auction = $event->auction;
//            if (isset($auction) && $auction->bid()->exists()) DuplicateBidJob::dispatch($auction);
//        } catch (Throwable $exception) {
//            Log::warning('DuplicateBidJob: ' . $exception->getMessage());
//        }
        return $event;
    }
}
