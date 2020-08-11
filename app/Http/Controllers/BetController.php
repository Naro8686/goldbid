<?php

namespace App\Http\Controllers;

use App\Jobs\BidJob;
use App\Models\Auction\Auction;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class BetController extends Controller
{
    public function bet($id)
    {
        /** @var Auction $auction */
        /** @var User $user */
        $autobid = false;
        $auction = Auction::query()->findOrFail($id);
        $user = Auth::user();
        if ($auction->autoBid()->where('user_id', $user->id)->exists()
            && $auction->autoBid()->where('user_id', $user->id)->first()->count > 0)
            $autobid = true;
        $run = (!$autobid && $auction->winner()->nickname !== $user->nickname);
        BidJob::dispatchIf($run, $auction, $user->nickname, $user);
    }
}
