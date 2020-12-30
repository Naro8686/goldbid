<?php

namespace App\Http\Middleware;

use App\Models\Auction\Auction;
use App\Models\Auction\Order;
use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Throwable;

class AuctionOrderMiddleware
{
    public $message = 'Вы уже приобрели этот товар , и больше не можете совершать дествия в данном аукционе .';

    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        /** @var User $user */
        $id = $request['id'];
        $user = $request->user();

        if (!is_null($id) && !is_null($user)) {
            $ordered = $user->auctionOrder()
                ->where('orders.auction_id', '=', $id)
                ->where('orders.status', '<>', Order::PENDING)
                ->exists();
            if ($ordered) {
                if ($request->ajax()) {
                    try {
                        $res = view('site.include.info_modal', ['message' => $this->message])->render();
                    } catch (Throwable $e) {
                        $res = $e->getMessage();
                    }
                    return response()->json($res, 200);
                } else return redirect()->back()->with('message', $this->message);
            } else return $next($request);
        } else abort(403);
    }
}
