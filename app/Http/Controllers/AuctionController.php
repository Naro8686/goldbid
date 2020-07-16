<?php

namespace App\Http\Controllers;

use App\Events\BetEvent;
use App\Jobs\CreateAuctionJob;
use App\Models\Auction\Auction;
use App\Settings\Setting;
use Carbon\Carbon;
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

    public function index(Request $request)
    {

    }

    public function auction($id)
    {
//        $auction = Auction::auctionPage($id);
        $auction = Auction::auctionsForHomePage()->firstWhere('id','=',$id);
        dd($auction);
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
            Log::info($e->getMessage());
        }
    }

    public function changeStatus()
    {
        try {
            $auctions = Auction::auctionsForHomePage();
            return view('site.include.auctions', compact('auctions'))->render();
        } catch (\Throwable $e) {
            Log::info($e->getMessage());
        }
    }


}
