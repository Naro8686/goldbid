<?php

namespace App\Http\Controllers;

use App\Jobs\BidJob;
use App\Models\Auction\Auction;
use App\Models\Auction\Order;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class BetController extends Controller
{
    public function bet($id)
    {
        /** @var Auction $auction */
        /** @var User $user */
        $auction = Auction::query()->findOrFail($id);
        $user = Auth::user();
        BidJob::dispatchIf(($auction->winner()->nickname !== $user->nickname), $auction, $user->nickname, $user);
    }
}
