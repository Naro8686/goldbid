<?php

namespace App\Jobs;

use App\Models\Auction\Auction;
use App\Models\Auction\AutoBid;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;

class AutoBidJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * AutoBidJob constructor.
     * @param AutoBid $active
     */
    public AutoBid $active;
    /**
     * @var mixed
     */
    public $auction;
    /**
     * @var mixed
     */
    public $user;


    public function __construct(AutoBid $active)
    {
        $this->active = $active;
        $this->auction = $this->active->auction;
        $this->user = $this->active->user;
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
        $user = $this->user;
        $auction = $this->auction;
        $balance = $user->balance();
        if ($autoBid->count <= 0 ||
            ($balance->bet + $balance->bonus) <= 0 ||
            $auction->status === Auction::STATUS_FINISHED) {
            $autoBid->delete();
        } else {
            try {
                DB::beginTransaction();
                if ($autoBid->update([
                    'count' => ($autoBid->count - 1),
                ]))
                    BidJob::dispatchNow($auction, $user->nickname, $user);
                DB::commit();
            } catch (\Exception $exception) {
                DB::rollBack();
            }
        }
    }
}
