<?php

namespace App\Http\Controllers;

use DB;
use Throwable;
use Carbon\Carbon;
use App\Events\BetEvent;
use App\Settings\Setting;
use Illuminate\Http\Request;
use App\Models\Auction\AutoBid;
use App\Models\Auction\Auction;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class AuctionController extends Controller
{
    /**
     * @var \stdClass
     */
    public $page;


    /**
     * Create a new controller instance.
     *
     * @param Request $request
     */
    public function __construct(Request $request)
    {
        if (!$request->ajax()) {
            $this->page = (new Setting($request->segment(1)))->page();
            view()->share('page', $this->page);
        }
    }

    public function auction($id)
    {
//        $a = Auction::find($id);
//        dump($a->botCountBet(),$a->bot_shutdown_count);
        if (Auth::check()) {
            $closeAuction = Auction::whereHas('bid', function ($query) {
                $query->where('bids.user_id', Auth::id());
            })->where('auctions.id', $id)
                ->where('auctions.active', false)
                ->exists();
            if ($closeAuction)
                return redirect()->back()
                    ->with('message', 'Аукцион закрыт по истечение отведённого времени ');
        }
        $auction = Auction::auctionPage($id);
        return response()
            ->view('site.auction', compact('auction'))
            ->header('Cache-Control', 'no-store, no-cache, must-revalidate, post-check=0, pre-check=0');
    }

    /**
     * @param $id
     * @return array|string
     */
    public function addFavorite($id)
    {
        $auction = Auction::findOrFail($id);
        $favorite = $auction->userFavorites();
        $user_id = Auth::id();
        if ($favorite->where('id', $user_id)->exists())
            $favorite->detach($user_id);
        else
            $favorite->attach($user_id);
    }

    /**
     * @param $id
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     * @throws Throwable
     */
    public function autoBid($id, Request $request): \Illuminate\Http\RedirectResponse
    {
        $bid = null;
        $auction = Auction::where('status', Auction::STATUS_ACTIVE)->findOrFail($id);
        $user = Auth::user();
        if (!$user) abort(403);
        $time = Carbon::now("Europe/Moscow");
        $balance = $user->balance();
        $max_count = ($balance->bet + $balance->bonus);
        $request->validate(['count' => ['integer', 'min:0', 'max:' . $max_count, 'nullable']]);
        $count = (int)$request['count'];
        $isNew =true;
        try {
            DB::beginTransaction();
            if ($first = $auction->autoBid()->where('user_id', $user->id)->first()) {
                if ($count === 0) $first->delete();
                else $first->update(['count' => $count]);
                $isNew = false;
            } elseif ((bool)$count) {
                if ($lastBet = $auction->autoBid()
                    ->orderByDesc('auto_bids.bid_time')
                    ->first(['auto_bids.bid_time'])) $time = $lastBet->bid_time
                    ->addSecond();
                $auction->autoBid()->create([
                    'user_id' => $user->id,
                    'count' => $count,
                    'bid_time' => $time,
                    'status' => AutoBid::PENDING
                ]);
            }
            DB::commit();
        } catch (Throwable $e) {
            DB::rollBack();
            Log::error('function autoBid = ' . $e->getMessage());
        }
        if (!$auction->jobExists() && $isNew) event(new BetEvent($auction));
        return redirect()->back();
    }

    /**
     * @param null $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function changeStatus($id = null)
    {
        $html = [];
        $status = 200;
        try {
            if (!is_null($id)) {
                $auction = Auction::auctionPage($id)->except(['desc', 'specify', 'terms']);
                if ($auction['error']) $status = 403;
                if (!($auction['status'] === Auction::STATUS_FINISHED && is_null($auction['winner'])))
                    $html['home_page'] = view('site.include.auction', ['auction' => $auction])->render();
                $auctionPage = $auction->except(['images']);
                $html['auction_page'] = view('site.include.info', ['auction' => $auctionPage])->render();
            }
//            else {
//                $auctions = Auction::auctionsForHomePage();
//                $html['home_page'] = view('site.include.auctions', ['auctions' => $auctions])->render();
//            }
        } catch (Throwable $e) {
            if ($e->getCode() !== 0)
                Log::error('status_change.' . $e->getMessage() . ' code ' . $e->getCode());
        }
        return response()->json($html, $status);
    }


}
