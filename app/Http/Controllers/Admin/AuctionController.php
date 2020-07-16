<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Auction\Auction;
use App\Models\Auction\Product;
use App\Models\Balance;
use App\Models\Pages\Page;
use App\Models\User;
use App\Settings\Setting;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

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
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($id)
    {
        $auction = Auction::findOrFail($id);
        try {
            if (is_file(public_path($auction->img_1))) unlink(public_path($auction->img_1));
            if (is_file(public_path($auction->img_2))) unlink(public_path($auction->img_2));
            if (is_file(public_path($auction->img_3))) unlink(public_path($auction->img_3));
            if (is_file(public_path($auction->img_4))) unlink(public_path($auction->img_4));
            Page::query()->where('slug',$auction->id)->delete();
            $auction->delete();
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

}
