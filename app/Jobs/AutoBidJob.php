<?php

namespace App\Jobs;

use App\Events\BetEvent;
use App\Models\Auction\AutoBid;
use App\Models\Auction\Bid;
use App\Models\Auction\Order;
use Carbon\Carbon;
use DB;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Throwable;

class AutoBidJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * AutoBidJob constructor.
     * @param AutoBid $autoBid
     */
    public $autoBid;

    public function __construct(AutoBid $autoBid)
    {
        $this->autoBid = $autoBid;
    }

    /**
     * Execute the job.
     *
     * @return void
     * @throws Throwable
     */
    public function handle()
    {
        if ($autoBid = $this->autoBid) {
            $user = $autoBid->user;
            $auction = $autoBid->auction;
            try {
                $autoBid->bid_time = Carbon::now("Europe/Moscow");
                DB::beginTransaction();
                if (!is_null($auction) && !is_null($user)) {
                    $balance = $user->balance();
                    $notOrder = $user->auctionOrder()
                        ->where('auction_id', '=', $auction->id)
                        ->where('status', '=', Order::SUCCESS)
                        ->doesntExist();
                    $auction->autoBid()->where('status', '=', AutoBid::WORKED)->update(['status' => AutoBid::PENDING]);
                    if (($balance->bet + $balance->bonus) >= Bid::COUNT
                        && $notOrder
                        && !$auction->finished()
                        && $auction->winner()->nickname !== $user->nickname
                        && $autoBid->count > 0) {
                        $autoBid->count -= 1;
                        if ($autoBid->save(['timestamp' => false])) BidJob::dispatch($auction, $user->nickname, $user);
                    } else event(new BetEvent($auction));
                }
                DB::commit();
            } catch (Throwable $exception) {
                DB::rollBack();
                if (!is_null($auction)) {
                    $auction->autoBid()->where('status', '=', AutoBid::WORKED)->update(['status' => AutoBid::PENDING]);
                    event(new BetEvent($auction));
                }
                Log::error('AutoBidJob ' . $exception->getMessage());
            }
        }
    }
}
