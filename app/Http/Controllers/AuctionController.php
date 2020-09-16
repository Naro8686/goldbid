<?php

namespace App\Http\Controllers;

use App\Events\BetEvent;
use App\Jobs\AutoBidJob;
use App\Models\Auction\Auction;
use App\Models\Auction\AutoBid;
use App\Models\Balance;
use App\Models\User;
use App\Settings\Setting;
use Carbon\Carbon;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Throwable;
use function GuzzleHttp\Promise\queue;


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
        try {
            $auctions = Auction::auctionsForHomePage();
            return view('site.include.auctions', compact('auctions'))->render();
        } catch (Throwable $e) {
            Log::error('add favorite ' . $e->getMessage());
        }
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
        $user = Auth::user();
        $balance = $user->balance();
        $max_count = ($balance->bet + $balance->bonus);
        $request->validate(['count' => ['integer', 'min:0', 'max:' . $max_count, 'nullable']]);
        $time = Carbon::now()->timezone("Europe/Moscow");
        $auction = Auction::query()->where('status', Auction::STATUS_ACTIVE)->findOrFail($id);
        $auto_bid = $auction->autoBid();
        $count = (int)$request['count'];
        try {
            DB::beginTransaction();
            if ($first = $auto_bid->where('user_id', $user->id)->first()) {
                if ($count === 0) {
                    $first->delete();
                    event(new BetEvent($auction));
                } else $first->update(['count' => $count]);
            } elseif ((bool)$count) {
                $run = ($auto_bid->where('auto_bids.status', AutoBid::WORKED)->count() === 0);
                $bid = $auto_bid->create([
                    'user_id' => $user->id,
                    'count' => $count,
                    'bid_time' => $time,
                    'status' => (int)$run
                ]);
                AutoBidJob::dispatchIf($bid->status === AutoBid::WORKED, $bid)->afterResponse();
            }
            DB::commit();
        } catch (Throwable $e) {
            Log::error('function autoBid = ' . $e->getMessage());
            DB::rollBack();
        }
        return redirect()->back();
    }

    /**
     * @param null $id
     * @param array $html
     * @return \Illuminate\Http\JsonResponse
     */
    public function changeStatus($id = null)
    {
        $html = [];
        try {
            if (!is_null($id)) {
                $auctionForHomePage = Auction::auctionsForHomePage()->firstWhere('id', '=', $id);
                $html['home_page'] = view('site.include.auction', ['auction' => $auctionForHomePage])->render();
                $auctionPage = Auction::auctionPage($id);
                unset($auctionPage['images']);
                unset($auctionPage['desc']);
                unset($auctionPage['specify']);
                unset($auctionPage['terms']);
                $html['auction_page'] = view('site.include.info', ['auction' => $auctionPage])->render();
            } else {
                $auctions = Auction::auctionsForHomePage();
                $html['home_page'] = view('site.include.auctions', ['auctions' => $auctions])->render();
            }
        } catch (Throwable $e) {
            if ($e->getCode() !== 0)
                Log::error('status_change.' . $e->getMessage() . ' code ' . $e->getCode());
        }
        return response()->json($html);
    }


}
