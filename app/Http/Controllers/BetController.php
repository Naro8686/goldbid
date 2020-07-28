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
        if ($user->auctionOrder()->where('auction_id', $id)->where('status', '<>', Order::PENDING)->exists()) {
            try {
                $html = view('site.include.info_modal')->with('message', 'Вы уже приобрели этот товар , и больше не можете совершать дествия в данном аукционе .')->render();
                return response()->json(['error' => $html]);
            } catch (\Throwable $e) {
                dd($e->getMessage());
            }
        }
        BidJob::dispatchIf(($auction->winner()->nickname !== $user->nickname), $auction, $user->nickname, $user);
    }
}
