<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Jobs\BidJob;
use App\Models\Auction\Auction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BetController extends Controller
{
    public function __invoke($id,Request $request)
    {

        /** @var Auction $auction */
        /** @var User $user */
        $auction = Auction::query()->where('status','=',Auction::STATUS_ACTIVE)->findOrFail($id);
        $user = $request->user();
        $autobid = ($auction->autoBid()->where('user_id', $user->id)->exists() &&
            $auction->autoBid()->where('user_id', $user->id)->first()->count > 0);
        $run = (!$autobid && $auction->winner()->nickname !== $user->nickname);
        BidJob::dispatchIf($run, $auction, $user->nickname, $user);
    }
}
