<?php

namespace App\Listeners;

use App\Events\BetEvent;
use App\Jobs\AutoBidJob;
use App\Models\Auction\Auction;
use App\Models\Auction\AutoBid;
use Carbon\Carbon;
use DB;
use Illuminate\Support\Facades\Log;
use Throwable;

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
        $auction = $event->auction;
        if ($auction->autoBid()->exists()) {
            try {
                foreach ($auction->autoBid as $item) {
                    $balance = $item->user->balance();
                    if ($item->count <= 0 || ($balance->bet + $balance->bonus) <= 0 || $auction->status === $auction::STATUS_FINISHED) $item->delete();
                }
                if ($next = self::select($auction)) {
                    self::autoBid($next);
                }
            } catch (Throwable $throwable) {
                Log::error('AutoBidListener ' . $throwable->getMessage());
            }
        }
        return $event;
    }

    /**
     * @param AutoBid $next
     * @throws Throwable
     */
    private static function autoBid(AutoBid $next)
    {
        try {
            DB::transaction(function () use ($next) {
                $next->update(['status' => AutoBid::WORKED]);
                $rand = rand(1, ($next->auction->step_time() - 1));
                $delay = Carbon::now()->addSeconds((int)$rand);
                AutoBidJob::dispatchIf($next->status === AutoBid::WORKED, $next)->delay($delay);
            });
        } catch (Throwable $exception) {
            Log::error('autobid listener ' . $exception->getMessage());
        }
    }

    /**
     * @param Auction $auction
     * @return AutoBid|\Illuminate\Database\Eloquent\Model|\Illuminate\Database\Eloquent\Relations\HasMany|object|null
     */
    private static function select(Auction $auction)
    {
        $run = !$auction->autoBid()->where('auto_bids.status', AutoBid::WORKED)->exists();
        return $run ? $auction->autoBid()
            ->where('auto_bids.user_id', '<>', $auction->winner()->user_id)
            ->where('auto_bids.status', '=', AutoBid::PENDING)
            ->orderBy('auto_bids.bid_time')
            ->orderBy('auto_bids.id')
            ->sharedLock()
            ->first() : null;
    }
}
