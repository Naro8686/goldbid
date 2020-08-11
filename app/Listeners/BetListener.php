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
        $auction = $event->auction;
        if ($auction->autoBid()->exists()) {
            /** @var AutoBid $last */
            $last = $auction->autoBid()->orderBy('updated_at')->first();
            $this->autoBid($last);
        }
        return $event;
    }

    private function autoBid(AutoBid $last)
    {
        if ($active = $last) {
            $active->touch();
            $auction = $active->auction;
            $user = $active->user;
            $rand = rand(0, $auction->step_time() - 2);
            $delay = Carbon::now()->addSeconds((int)$rand);
//            Log::info("{$auction->step_time()} = {$rand}");
            AutoBidJob::dispatchIf($auction->winner()->nickname !== $user->nickname, $active)->delay($delay);
        }
    }
}
