<?php

namespace App\Listeners;

use App\Events\BetEvent;
use App\Jobs\AutoBidJob;
use App\Jobs\BidJob;
use App\Models\Auction\AutoBid;
use Carbon\Carbon;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;

class BetListener
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
        if ($event->auction->autoBid()->exists()) {
            $last = $event->auction->autoBid()->orderBy('updated_at')->first();
            $this->autoBid($last);
        }
        return $event;
    }

    private function autoBid(?\Illuminate\Database\Eloquent\Model $last)
    {
        Log::info('ok' . $last->toJson());
        /** @var AutoBid $active */
        if ($active = $last) {
            $rand = rand(0, $active->auction->bid_seconds - 1);
//            $time = $active->auction->start->addSeconds($rand);
//            if ($active->auction->winner()->created_at)
            $time = $active->auction->winner()->created_at->addSeconds($rand);
            Log::info($time);
            AutoBidJob::dispatch($active)->delay($time);
        }
    }
}
