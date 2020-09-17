<?php

namespace App\Jobs;


use App\Models\Auction\Auction;
use App\Models\Auction\Bid;
use App\Models\Balance;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Throwable;

class DuplicateBidJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * @var Auction
     */
    public $auction;


    public function __construct(Auction $auction)
    {
        $this->auction = $auction;
    }

    /**
     * @throws Throwable
     */
    public function handle()
    {
        try {
            if ($auction = $this->auction) {
                DB::beginTransaction();
                $duplicates = DB::table('bids')
                    ->select('id', DB::raw('COUNT(*) as `duplicates`'))
                    ->where('auction_id', $auction->id)
                    ->groupBy('price')
                    ->having('duplicates', '>', 1)
                    ->latest()->first();
                if ($duplicates) {
                    $bid = Bid::find($duplicates->id);
                    if ($bid->is_bot) {
                        if ($bid->bot_num === 1) {
                            $auction->increment('bot_shutdown_count');
                        } else {
                            $auction->increment('bot_shutdown_price', 10);
                        }
                    } else {
                        if ($user = $bid->user) {
                            $user->balanceHistory()->create([
                                'bet' => $bid->bet,
                                'bonus' => $bid->bonus,
                                'type' => Balance::PLUS
                            ]);
                            if ($user->autoBid->isNotEmpty()) {
                                $user->autoBid()
                                    ->where('auto_bids.auction_id', $bid->auction_id)
                                    ->increment('auto_bids.count');
                            }
                        }
                    }
                    $bid->delete();
                }
                DB::commit();
            }

        } catch (Throwable $exception) {
            Log::warning('Bet duplicate delete ' . $exception->getMessage());
            DB::rollBack();
        }
    }
}
