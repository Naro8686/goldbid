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
        $this->autoBid($event->auction->autoBid());
        return $event;
    }

    public function autoBid(\Illuminate\Database\Eloquent\Relations\HasMany $autoBid)
    {
        /** @var AutoBid $active */
        if (isset($autoBid)) {
            if ($active = $autoBid->orderBy('updated_at')->first()) {
                $rand = rand(0, $active->auction->bid_seconds - 1);
                $time = $active->auction->start->addSeconds($rand);
                if ($active->auction->winner()->created_at)
                    $time = $active->auction->winner()->created_at->addSeconds($rand);
                dispatch(new AutoBidJob($active))->delay($time);
            }
        }
    }
}
