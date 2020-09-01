<?php

namespace App\Jobs;

use App\Models\Auction\AutoBid;
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
                    $autoBid->update(['status' => AutoBid::PENDING, 'bid_time' => Carbon::now()->timezone("Europe/Moscow")]);
                    $user = $autoBid->user;
                    $auction = $autoBid->auction;
                    if ($auction->winner()->nickname !== $user->nickname) $autoBid->minus();
                    BidJob::dispatch($auction, $user->nickname, $user);
                });
            } catch (Throwable $exception) {
                Log::error('AutoBidJob ' . $exception->getMessage());
            }
        }
    }
}
