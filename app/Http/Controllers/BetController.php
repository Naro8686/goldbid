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
            //DB::transaction(function () use ($id, $request) {
                $auction = Auction::where('status', '=', Auction::STATUS_ACTIVE)->find($id);
                if (!is_null($auction) && !$auction->finished() && $user = $request->user()) {
                    $noDuplicate = $auction->bid()
                        ->where('bids.price', $auction->new_price())
                        ->doesntExist();
                    $autobid = $user->autoBid()
                        ->where([
                            ['auto_bids.auction_id', '=', $id],
                            ['auto_bids.count', '>', 0],
                        ])->doesntExist();
                    $price = ($auction->full_price($user->id) > 1);
                    if (($price && $autobid && $auction->winner()->nickname !== $user->nickname && $noDuplicate))
                        BidJob::dispatchNow($auction, $user->nickname, $user);
                }
            //});
        } catch (Throwable $throwable) {
            \Log::info('BetController ' . $throwable->getMessage());
        }
    }
}
