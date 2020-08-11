<?php

namespace App\Http\Controllers;

use App\Jobs\AutoBidJob;
use App\Models\Auction\Auction;
use App\Settings\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;


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
        } catch (\Throwable $e) {
            Log::error('add favorite ' . $e->getMessage());
        }
    }

    public function autoBid($id, Request $request)
    {
        $bid = null;
        $user = Auth::user();
        $balance = $user->balance();
        $max_count = $balance->bet + $balance->bonus;
        $request->validate([
            'count' => ['integer', 'min:0', 'max:' . $max_count, 'nullable']
        ]);
        $auction = Auction::query()->where('status', Auction::STATUS_ACTIVE)->findOrFail($id);
        $auto_bid = $auction->autoBid();
        $status = (int)!$auto_bid->exists();
        if ($auto_bid->where('user_id', $user->id)->exists()) {
            if ((int)$request['count'] === 0)
                $auto_bid->where('user_id', $user->id)->delete();
            else {
                $update = $auto_bid->where('user_id', $user->id)->first();
                $update->count = $request['count'];
                $update->timestamps = false;
                $update->save();
                $bid = $update;
            }
        } elseif (!is_null($request['count'])) {
            $bid = $auto_bid->create(['status' => $status, 'user_id' => $user->id, 'count' => $request['count']]);
        }
        AutoBidJob::dispatchIf((!is_null($bid) && $auction->winner()->nickname !== $bid->user->nickname), $bid);
        return redirect()->back();
    }

    public function changeStatus($id = null)
    {
        try {
            $html = [];
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
            return response()->json($html);
        } catch (\Throwable $e) {
            Log::error('status_change.' . $e->getMessage());
        }
    }


}
