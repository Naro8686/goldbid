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

    public function index(Request $request)
    {

    }


    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
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
    public function edit($id)
    {
        $slug = Auction::query()->findOrFail($id);
        $meta = (new Setting($slug->id))->mete();
        return view(self::DIR.'edit', compact('meta'));
    }
    public function show($id){
        if ($auction = Auction::query()->find($id)) {
            $data = $auction->auctionCard();
            $html = view('admin.auctions.card', compact('data'))->render();
        } else {
            $html = "<div class='col-md-12'><h3 class='text-center text-danger'>error</h3></div>";
        }
        return response(['success' => true, 'html' => $html, 'title' => "Аукциона ID: {$id}"]);
    }
}
