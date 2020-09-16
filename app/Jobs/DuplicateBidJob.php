<?php

namespace App\Jobs;


use App\Models\Auction\Auction;
use App\Models\Balance;
use App\Models\User;
use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class DuplicateBidJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $auction;



    public function __construct(Auction $auction)
    {
        $this->auction = $auction;
    }

    public function handle()
    {
        try {
            $duplicate = null;
            $auction = $this->auction;
            if ($auction && $auction->bid) {
                foreach ($auction->bid->pluck('price') as $price) {
                    $bids = $auction->bid->where('price', $price);
                    if ($bids->count() > 1) {
                        $duplicate = $bids;
                        break;
                    }
                }
                if (!is_null($duplicate)) {
                    $last = $duplicate->last();
                    if ($last->is_bot) {
                        if ($last->bot_num === 1) {
                            $auction->bot_shutdown_count += 1;
                        } else {
                            $auction->bot_shutdown_price += 10;
                        }
                        $auction->save(['timestamp' => false]);
                    } else {
                        $user = User::find($last->user_id);
                        $type = $last->bonus ? 'bonus' : 'bet';
                        $user->balanceHistory()->create([
                            $type => 1,
                            'type' => Balance::PLUS
                        ]);
//                        if ($user->autoBid()->exists()) {
//                            $user->autoBid()->where('auto_bids.auction_id', $last->auction_id)
//                                ->update(['auto_bids.count' => DB::raw('auto_bids.count + 1')]);
//                        }
                    }
                    $last->delete();
                }
            }
        } catch (Exception $exception) {
            Log::warning('Bet duplicate delete ' . $exception->getMessage());
        }
    }
}
