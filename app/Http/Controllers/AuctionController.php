<?php

namespace App\Http\Controllers;

use App\Jobs\AutoBidJob;
use App\Models\Auction\Auction;
use App\Models\Auction\AutoBid;
use App\Settings\Setting;
use Carbon\Carbon;
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
        $this->page = (new Setting($request->segment(1)))->page();
        view()->share('page', $this->page);
    }

    public function auction($id)
    {
//        $auc =Auction::query()->with('bid')->find(92);
//        dd($auc->bid()->where('bids.created_at','>',$auc->end)->get()->groupBy('bids.user_id'));
        if (Auth::check()) {
            $closeAuction = Auction::query()
                ->whereHas('bid', function ($query) {
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

    public function addFavorite($id)
    {
        $auction = Auction::query()->findOrFail($id);
        $favorite = $auction->userFavorites();
        $user_id = Auth::id();
        if ($favorite->where('id', $user_id)->exists())
            $favorite->detach($user_id);
        else
            $favorite->attach($user_id);
        try {
            $auctions = Auction::auctionsForHomePage();
            return view('site.include.auctions', compact('auctions'))->render();
        } catch (Throwable $e) {
            Log::error('add favorite ' . $e->getMessage());
        }
    }

    public function autoBid($id, Request $request)
    {
        $bid = null;
        $user = Auth::user();
        $balance = $user->balance();
        $max_count = ($balance->bet + $balance->bonus);
        $request->validate(['count' => ['integer', 'min:0', 'max:' . $max_count, 'nullable']]);
        $time = Carbon::now()->timezone("Europe/Moscow");
        $auction = Auction::query()->where('status', Auction::STATUS_ACTIVE)->findOrFail($id);
        $auto_bid = $auction->autoBid();
        $count = (int)$request['count'];
        try {
            if ($first = $auto_bid->where('user_id', $user->id)->first()) {
                if ($count === 0) $first->delete();
                else $first->update(['count' => $count]);
            } elseif ((bool)$count) {
                $bid = $auto_bid->create([
                    'user_id' => $user->id,
                    'count' => $count,
                    'bid_time' => $time,
                    'status' => AutoBid::WORKED
                ]);
                AutoBidJob::dispatchAfterResponse($bid);
            }
        } catch (Throwable $e) {
            return redirect()->back()->with('status', $e->getMessage());
        }
        return redirect()->back();
    }

    /**
     * @param int|null $id
     * @param array $html
     * @return \Illuminate\Http\JsonResponse
     */
    public function changeStatus($id = null, $html = [])
    {
        try {
            $auctions = Auction::auctionsForHomePage();
            $html['home_page'] = view('site.include.auctions', compact('auctions'))->render();
            if (!is_null($id)) {
                $auction = Auction::auctionPage($id);
                unset($auction['images']);
                unset($auction['desc']);
                unset($auction['specify']);
                unset($auction['terms']);
                $html['auction_page'] = view('site.include.info', compact('auction'))->render();
            }
        } catch (Throwable $e) {
            Log::error('status_change.' . $e->getMessage() . ' code ' . $e->getCode());
        }
        return response()->json($html);
    }


}
