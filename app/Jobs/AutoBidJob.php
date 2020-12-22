<?php

namespace App\Jobs;

use App\Events\BetEvent;
use App\Models\Auction\Auction;
use App\Models\Auction\AutoBid;
use App\Models\Auction\Bid;
use App\Models\Auction\Order;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Throwable;

class AutoBidJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $tries = 1;
    /**
     * AutoBidJob constructor.
     * @param AutoBid $autoBid
     */
    public $autoBid;

    public function __construct(AutoBid $autoBid)
    {
        $this->autoBid = $autoBid;
    }

    public function fail($exception = null)
    {
        $autoBid = $this->autoBid->refresh();
        if ($autoBid && $auction = $autoBid->auction) {
            $auction->autoBid()
                ->where('status', AutoBid::WORKED)
                ->update(['status' => AutoBid::PENDING]);
            event(new BetEvent($this->autoBid->auction->refresh()));
        }
        if (!is_null($exception)) Log::warning('AutoBidJob fail ' . $exception);
    }

    /**
     * Execute the job.
     *
     * @return void
     * @throws Throwable
     */
    public function handle()
    {
        try {
            $autoBid = $this->autoBid->refresh();
            $user = $autoBid->user->refresh();
            $auction = $autoBid->auction->refresh();
            $balance = $user->balance();
            $notOrder = $user->auctionOrder()
                ->where('auction_id', '=', $autoBid->auction_id)
                ->where('status', '=', Order::SUCCESS)
                ->doesntExist();
            $auction->autoBid()
                ->where('status', AutoBid::WORKED)
                ->update(['status' => AutoBid::PENDING]);
            $count = $autoBid->count;
            if ((($balance->bet + $balance->bonus) >= Bid::COUNT) && $notOrder && $count > 0) {
                $error = !($autoBid->update(['bid_time' => Carbon::now("Europe/Moscow"), 'count' => ($count - 1)]));
            } else {
                $error = true;
                $autoBid->delete();
            }
            if ($auction->status === Auction::STATUS_ACTIVE)
                $error
                    ? $this->fail('fail')
                    : BidJob::dispatchNow($auction, $user->nickname, $user);
            if ($count <= 1) $autoBid->delete();
        } catch (Throwable $exception) {
            $this->fail($exception->getMessage().' Line '.$exception->getLine());
        }
    }

}
