<?php

namespace App\Http\Controllers;

use App\Events\BetEvent;
use App\Events\StatusChangeEvent;
use App\Models\Auction\Auction;
use App\Models\Balance;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class BetController extends Controller
{
    /**
     * @var \Illuminate\Contracts\Auth\Authenticatable|null
     */
    public $user;

    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware(function ($request, $next) {
            $this->user = Auth::user();
            return $next($request);
        });
    }

    public function bet($id)
    {
        $auction = Auction::query()->findOrFail($id);
        $user = $this->user;
        $nick = $user->nickname;
        $balance = $user->balance();
        if (($balance->bet + $balance->bonus) <= 0) return response()->json(['bet' => 0, 'bonus' => 0]);
        $bid_type = $balance->bet > 0 ? 'bet' : 'bonus';
        if ($auction->status === Auction::STATUS_ACTIVE && $auction->winner()->nickname !== $nick) {
            try {
                DB::beginTransaction();
                $new_price = is_null($auction->winner()->price) ? $auction->price() : ($auction->price() + $auction->step_price());
                $new_time = Carbon::now()->addSeconds($auction->bid_seconds);
                $auction->update([
                    'step_time' => $new_time,
                ]);
                $user->balanceHistory()->create([
                    'type' => Balance::MINUS,
                    $bid_type => 1,
                ]);
                $this->user->bid()->create([
                    $bid_type => 1,
                    'auction_id' => $auction->id,
                    'title' => $auction->title,
                    'nickname' => $this->user->nickname,
                    'price' => $new_price,
                ]);
                DB::commit();
            }catch (\Exception $exception){
                DB::rollBack();
            }
            event(new StatusChangeEvent([
                'status_change' => true,
            ]));
            return response()->json(['bet' => $balance->bet, 'bonus' => $balance->bonus]);
        }
    }
}
