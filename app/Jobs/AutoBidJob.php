<?php

namespace App\Jobs;

use App\Models\Auction\Auction;
use App\Models\Auction\AutoBid;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class AutoBidJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * AutoBidJob constructor.
     * @param AutoBid $active
     */
    public $active;

    public function __construct(AutoBid $active)
    {
        $this->active = $active;
    }

    /**
     * Execute the job.
     *
     * @return void
     * @throws \Exception
     */
    public function handle()
    {
        $autoBid = $this->active;
        $user = $autoBid->user;
        $auction = $autoBid->auction;
        $balance = $user->balance();
        try {
            DB::beginTransaction();
            if ($autoBid->count <= 0 || ($balance->bet + $balance->bonus) <= 0 || $auction->status === Auction::STATUS_FINISHED) {
                $autoBid->delete();
            } else {
                //if ($auction->winner()->nickname !== $user->nickname) {
                $autoBid->decrement('count');
                BidJob::dispatch($auction, $user->nickname, $user);
                //}
            }
            DB::commit();
        } catch (\Exception $exception) {
            DB::rollBack();
        }
    }
}
