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
                $user = $autoBid->user;
                $auction = $autoBid->auction;
                DB::beginTransaction();
                $data['bid_time'] = Carbon::now()->timezone("Europe/Moscow");
                $data['status'] = AutoBid::PENDING;
                if ($auction->winner()->nickname !== $user->nickname) {
                    $autoBid->minus();
                }
                $autoBid->update($data);
                BidJob::dispatch($auction, $user->nickname, $user);
                DB::commit();
            } catch (Throwable $exception) {
                DB::rollBack();
                Log::error('AutoBidJob ' . $exception->getMessage());
            }
        }
    }
}
