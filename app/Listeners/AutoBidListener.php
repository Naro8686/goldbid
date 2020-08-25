<?php

namespace App\Listeners;

use App\Events\BetEvent;
use App\Jobs\AutoBidJob;
use App\Models\Auction\Auction;
use App\Models\Auction\AutoBid;
use Carbon\Carbon;

//use Illuminate\Contracts\Queue\ShouldQueue;
//use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;
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
            try {
                foreach ($auction->autoBid as $item) {
                    $balance = $item->user->balance();
                    if ($item->count <= 0 || ($balance->bet + $balance->bonus) <= 0 || $auction->status === $auction::STATUS_FINISHED) $item->delete();
                }
                if ($next = $this->select($auction)) {
                    $next->update(['status' => !$next->status]);
                    $this->autoBid($next);
                }
            } catch (Throwable $throwable) {
                Log::error('AutoBidListener ' . $throwable->getMessage());
            }
        }
        return $event;
    }

    private function autoBid(AutoBid $next)
    {
        try {
            $auction = $next->auction;
            $rand = rand(0, ($auction->step_time() - 2));
            $delay = Carbon::now()->addSeconds((int)$rand);
            //$run = !($auction->autoBid()->where('auto_bids.status', AutoBid::WORKED)->count() > 1);
            AutoBidJob::dispatchIf($next->status === AutoBid::WORKED, $next)->delay($delay);
        } catch (Throwable $exception) {
            Log::error('autobid listener ' . $exception->getMessage());
        }
    }

    /**
     * @param Auction $auction
     * @return AutoBid|\Illuminate\Database\Eloquent\Model|\Illuminate\Database\Eloquent\Relations\HasMany|object|null
     */
    private function select(Auction $auction)
    {
        $next = null;
        //if ($auction->autoBid()->where('auto_bids.status', AutoBid::WORKED)->count() <= 1) {
        $next = $auction->autoBid()
            ->where('auto_bids.user_id', '<>', $auction->winner()->user_id)
            //->where('auto_bids.status', '=', AutoBid::PENDING)
            ->orderBy('auto_bids.bid_time')
            ->first();
        //}
        return $next;
    }
}
