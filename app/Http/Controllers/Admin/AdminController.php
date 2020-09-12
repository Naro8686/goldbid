<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Auction\Auction;
use App\Models\Mailing;
use App\Models\User;
use App\Notifications\AccountApproved;
use App\Rules\OldPasswordRule;
use App\Settings\Setting;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    const DIR = 'admin.';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('admin');
    }

    /**
     * Show the application dashboard.
     *
     * @param Request $request
     * @return \Illuminate\Contracts\Support\Renderable
     */

    public function dashboard(Request $request)
    {
        if ($request->ajax()) {
            try {
                $auctions = Auction::data();
                return datatables()->of($auctions)->editColumn('img_1', function ($auction) {
                    $img = asset($auction['img_1']);
                    return "<img class='img-fluid img-thumbnail' src='{$img}' alt='{$auction['alt_1']}'>";
                })->editColumn('title', function ($auction) {
                    $link = route('auction.index', $auction['id']);
                    return "<a href='{$link}'>{$auction['title']}</a>";
                })->addColumn('action', function ($auction) {
                    $linkShow = route('admin.auctions.show', $auction['id']);
                    $linkDelete = route('admin.auctions.destroy', $auction['id']);
                    $linkEditSeo = route('admin.auctions.edit', $auction['id']);
                    return "<div class='btn-group btn-group-sm' role='group' aria-label='Basic example'>
                                        <button data-href='{$linkShow}' type='button'
                                                data-toggle='modal' data-target='#cardModal'
                                                class='btn btn-info mr-1'><i class='fa fa-eye'></i></button>
                                        <a href='{$linkEditSeo}' class='btn btn-info'>seo</a>

                                        <button type='button' class='btn btn-danger'
                                                data-toggle='modal'
                                                data-target='#resourceModal'
                                                data-action='{$linkDelete}'>
                                                    удалить
                                        </button>
                                    </div>";
                })->rawColumns(['img_1','title', 'action'])->make(true);
            } catch (Exception $e) {
                dd($e->getMessage());
            }
        }
        $auctionsInfo = Auction::info();
        return view(self::DIR . 'dashboard', compact('auctionsInfo'));
    }

    public function mailConfig(Request $request)
    {
        $mail = Setting::mailConfig();
        if ($request->isMethod('POST')) {
            $request->validate([
                'username' => ['sometimes', 'required', 'string', 'max:50'],
                'password' => ['sometimes', 'required', 'string', 'max:50'],
            ]);
            if ($request->has('password')) $request['password'] = base64_encode($request['password']);
            $mail->update($request->only(['driver', 'host', 'port', 'from_address', 'from_name', 'encryption', 'username', 'password']));
            return redirect()->back()->with('status', 'успешные дествия !');
        }
        return view(self::DIR . 'mail_config', compact('mail'));
    }

    public function siteConfig(Request $request)
    {
        $site = Setting::siteConfig();
        if ($request->isMethod('POST')) {
            //$request['phone_number'] = User::unsetPhoneMask($request['phone_number']);
            $request->validate([
                'storage_period_month' => ['nullable', 'integer', 'max:12'],
                'email' => ['nullable', 'email'],
                //'phone_number' => ['nullable', 'numeric'],
                'site_enabled' => ['sometimes', 'required', 'boolean'],
            ]);
            if ($request->has('site_enabled')) {
                if ((bool)$request['site_enabled'])
                    Artisan::call('up');
                else
                    Artisan::call('down');
            }
            $site->update($request->only(['email', 'phone_number', 'storage_period_month', 'site_enabled']));
            return redirect()->back()->with('status', 'успешные дествия !');
        }

        return view(self::DIR . 'site_config', compact('site'));
    }

    public function mailing()
    {
        $mailings = collect(['ads' => Mailing::ads(), 'no_ads' => Mailing::no_ads()]);
        return view(self::DIR . 'mailing', compact('mailings'));
    }

    public function adminProfileChange(Request $request)
    {
        if ($request->isMethod('POST')) {
            $data = [];
            $request['phone'] = User::unsetPhoneMask($request['phone']);
            if ($request['phone'] !== Auth::user()->phone) {
                $request->validate([
                    'phone' => ['required', 'numeric', 'digits:11', 'unique:users,id,' . Auth::id()],
                    'current_password' => ['required_with:phone', new OldPasswordRule],
                ]);
                $data['phone'] = $request['phone'];
            }
            if (!is_null($request['new_password'])) {
                $request->validate([
                    'current_password' => ['required_with:new_password', new OldPasswordRule],
                    'new_password' => ['required', 'min:8'],
                    'new_confirm_password' => ['required_with:new_password', 'same:new_password'],
                ]);
                $data['password'] = Hash::make($request['new_password']);
            }
            Auth::user()->update($data);
            return redirect()->back()->with('status', 'Изменения успешно сохранились');
        }
        return view(self::DIR . 'profile');
    }
}
