<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Jobs\BidJob;
use App\Models\Auction\Auction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Throwable;

class BetController extends Controller
{
    public function __invoke($id, Request $request)
    {
        /** @var Auction $auction */
        /** @var User $user */
        try {
            $auction = Auction::where('status', '=', Auction::STATUS_ACTIVE)->find($id);
            $user = $request->user();
            DB::transaction(function () use ($auction, $user) {
                if (!is_null($auction) && !$auction->finished() && !is_null($user)) {
                    $balance = $user->balance();
                    $noDuplicate = $auction->bid()
                        ->where('bids.price', $auction->new_price())
                        ->doesntExist();
                    $autobid = $user->autoBid()->where([
                        ['auto_bids.auction_id', '=', $auction->id],
                        ['auto_bids.count', '>', 0],
                    ])->doesntExist();
                    if ($autobid && $auction->winner()->nickname !== $user->nickname && $noDuplicate && ($balance->bet + $balance->bonus) > 0)
                        BidJob::dispatchAfterResponse($auction, $user->nickname, $user);
                }
            });
        } catch (Throwable $throwable) {
            \Log::info('BetController ' . $throwable->getMessage());
        }
    }
}
