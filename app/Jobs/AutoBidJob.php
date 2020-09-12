<?php

namespace App\Jobs;

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
            try {
                DB::transaction(function () use ($autoBid) {
                    $autoBid->update(['bid_time' => Carbon::now("Europe/Moscow")]);
                    $user = $autoBid->user;
                    $auction = $autoBid->auction;
                    $auction->autoBid()->where('status', AutoBid::WORKED)->update(['status' => AutoBid::PENDING]);
                    $balance = $user->balance();
                    $ordered = $user->auctionOrder()
                        ->where('orders.auction_id', '=', $auction->id)
                        ->where('orders.status', '=', Order::SUCCESS)
                        ->doesntExist();
                    if (($balance->bet + $balance->bonus) >= Bid::COUNT
                        && $ordered
                        && $auction->winner()->nickname !== $user->nickname
                        && ($auction->full_price($user->id) > 1))
                        $autoBid->minus();
                    BidJob::dispatch($auction, $user->nickname, $user);
                });
            } catch (Throwable $exception) {
                Log::error('AutoBidJob ' . $exception->getMessage());
            }
        }
    }
}
