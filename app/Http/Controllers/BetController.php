<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Jobs\BidJob;
use App\Models\Auction\Auction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BetController extends Controller
{
    public function __invoke($id, Request $request)
    {
        /** @var Auction $auction */
        /** @var User $user */
        $auction = Auction::query()->where('status', '=', Auction::STATUS_ACTIVE)->findOrFail($id);
        $user = $request->user();
        $autobid = DB::table('auto_bids')->where([
            ['auction_id', '=', $id],
            ['user_id', '=', $user->id],
            ['count', '>', 0],
        ])->doesntExist();
        $price = ($auction->full_price($user->id) > 1);
        $run = ($price && $autobid && $auction->winner()->nickname !== $user->nickname);
        BidJob::dispatchIf($run, $auction, $user->nickname, $user);
    }
}
