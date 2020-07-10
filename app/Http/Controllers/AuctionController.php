<?php

namespace App\Http\Controllers;

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
        $data = Auction::query()->where('active', true)
            ->findOrFail($id);
        $auction = collect([
            'images' => [
                $data->img_1 ? ['img' => $data->img_1, 'alt' => $data->alt_1] : null,
                $data->img_2 ? ['img' => $data->img_2, 'alt' => $data->alt_2] : null,
                $data->img_3 ? ['img' => $data->img_3, 'alt' => $data->alt_3] : null,
                $data->img_4 ? ['img' => $data->img_4, 'alt' => $data->alt_4] : null,
            ],
            'title' => $data->title,
        ]);
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
            return view('site.auctions', compact('auctions'))->render();
        } catch (\Throwable $e) {
            Log::info($e->getMessage());
        }
    }

    public function changeStatus($id)
    {
        $auction = Auction::query()->where('start', '<=', now())->findOrFail($id);
        $auction->update([
            'status' => Auction::STATUS_ACTIVE,
            'step_time' => Carbon::now()->addSeconds($auction->bid_seconds)
        ]);
        try {
            $auctions = Auction::auctionsForHomePage();
            return view('site.auctions', compact('auctions'))->render();
        } catch (\Throwable $e) {
            Log::info($e->getMessage());
        }
    }

}
