<?php

namespace App\Http\Controllers;

use App\Events\BetEvent;
use App\Jobs\AutoBidJob;
use App\Models\Auction\Auction;
use App\Models\Auction\AutoBid;
use App\Models\Auction\Order;
use App\Settings\Setting;
use Carbon\Carbon;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Throwable;


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
        //dd(Auction::find($id)->bid()->where('bot_num',1)->count());
        if (Auth::check()) {
            $closeAuction = Auction::whereHas('bid', function ($query) {
                $query->where('bids.user_id', Auth::id());
            })
                ->where('auctions.id', $id)
                ->where('auctions.active', false)
                ->exists();
            if ($closeAuction)
                return redirect()->back()->with('message', 'Аукцион закрыт по истечение отведённого времени ');
        }
        $auction = Auction::auctionPage($id);
        return view('site.auction', compact('auction'));
    }

    /**
     * @param $id
     * @return array|string
     */
    public function addFavorite($id)
    {
        $auction = Auction::query()->findOrFail($id);
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
    public function autoBid($id, Request $request)
    {
        $bid = null;
        $auction = Auction::where('status', Auction::STATUS_ACTIVE)->findOrFail($id);
        $user = Auth::user();
        if (!$user) abort(403);
        $balance = $user->balance();
        $max_count = ($balance->bet + $balance->bonus);
        $request->validate(['count' => ['integer', 'min:0', 'max:' . $max_count, 'nullable']]);
        $count = (int)$request['count'];
        try {
            DB::beginTransaction();
            if ($first = $auction->autoBid()->where('auto_bids.user_id', $user->id)->first()) {
                if ($count === 0) {
                    $first->delete();
                    event(new BetEvent($auction));
                } else $first->update(['auto_bids.count' => $count]);
            } elseif ((bool)$count) {
                $status = (int)($auction->autoBid()->where('auto_bids.status', AutoBid::WORKED)->doesntExist());
                $time = Carbon::now("Europe/Moscow");
                $new = $auction->autoBid()->create([
                    'user_id' => $user->id,
                    'count' => $count,
                    'bid_time' => $time,
                    'status' => $status
                ]);
                AutoBidJob::dispatchIf($new->status === AutoBid::WORKED, $new)->afterResponse();
            }
            DB::commit();
        } catch (Throwable $e) {
            DB::rollBack();
            Log::error('function autoBid = ' . $e->getMessage());
        }
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
