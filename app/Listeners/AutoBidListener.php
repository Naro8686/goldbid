<?php

namespace App\Listeners;

use App\Events\BetEvent;
use App\Jobs\AutoBidJob;
use App\Models\Auction\Auction;
use App\Models\Auction\AutoBid;
use App\Models\Auction\Order;
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
        if ($auction->autoBid()->exists() && $auction->status === $auction::STATUS_ACTIVE) {
            try {
                foreach ($auction->autoBid as $item) {
                    $user = $item->user;
                    $balance = $user->balance();
                    $stop = $user->auctionOrder()
                            ->where('orders.auction_id', '=', $auction->id)
                            ->where('orders.status', '=', Order::SUCCESS)
                            ->exists() || ($auction->full_price($user->id) <= 1);
                    if ($item->count <= 0
                        || ($balance->bet + $balance->bonus) <= 0
                        || $stop
                    ) $item->delete();
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
            $next->update(['status' => AutoBid::WORKED]);
            $rand = rand(0, ($next->auction->step_time() - 1));
            $delay = Carbon::now("Europe/Moscow")->addSeconds((int)(($rand === 0 && $next->auction->step_time() > 1) ? 1 : $rand));
            AutoBidJob::dispatchIf($next->status === AutoBid::WORKED, $next)->delay($delay);
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
        $run = DB::table('auto_bids')->where([
            ['auction_id', '=', $auction->id],
            ['status', '=', AutoBid::WORKED],
        ])->doesntExist();
        return $run ? $auction->autoBid()
            ->where('auto_bids.user_id', '<>', $auction->winner()->user_id)
            ->where('auto_bids.status', '=', AutoBid::PENDING)
            ->orderBy('auto_bids.bid_time')
            ->orderBy('auto_bids.id')
            ->first() : null;
    }
}
