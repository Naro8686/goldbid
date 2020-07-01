<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Balance;
use App\Models\Mailing;
use App\Models\User;
use App\Settings\Setting;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

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
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */

    public function dashboard()
    {
        return view(self::DIR . 'dashboard');
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
            $request['phone_number'] = User::unsetPhoneMask($request['phone_number']);
            $request->validate([
                'storage_period_month' => ['nullable', 'integer', 'max:12'],
                'email' => ['nullable', 'email'],
                'phone_number' => ['nullable', 'numeric', 'digits:11'],
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
}
