<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Jobs\DeleteAuctionInNotWinner;
use App\Models\Auction\Auction;
use App\Settings\Setting;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class AuctionController extends Controller
{
    private const DIR = 'admin.auctions.';

    /**
     * @param $id
     * @return RedirectResponse
     */
    public function destroy($id)
    {
        $auction = Auction::findOrFail($id);
        try {
            DeleteAuctionInNotWinner::dispatchNow($auction);
        } catch (Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
        return redirect()->back()->with('status', 'успешные дествия !');
    }

    /**
     * @param $id
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit($id)
    {
        $slug = Auction::findOrFail($id);
        $meta = (new Setting($slug->id))->mete();
        return view(self::DIR . 'edit', compact('meta'));
    }

    /**
     * @param $id
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     * @throws \Throwable
     */
    public function show($id)
    {
        try {
            $html = "<div class='col-md-12'><h3 class='text-center text-danger'>нет данных</h3></div>";
            if ($auction = Auction::find($id)) {
                $data = $auction->auctionCard();
                $html = view('admin.auctions.card', compact('data'))->render();
            }
        } catch (\Throwable $exception) {
            $html = "<div class='col-md-12'><h3 class='text-center text-danger'>{$exception->getMessage()}</h3></div>";
        }

        return response(['success' => true, 'html' => $html, 'title' => "Аукциона ID: {$id}"]);
    }
}
