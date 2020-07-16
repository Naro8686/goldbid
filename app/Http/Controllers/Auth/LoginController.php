<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use App\Settings\Setting;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function username()
    {
        return 'phone';
    }

    protected function validateLogin(Request $request)
    {
        $request['phone'] = User::unsetPhoneMask($request['phone']);
        $request->validate([
            $this->username() => 'required|string',
            'password' => 'required|string',
        ]);
    }

    protected function authenticated(Request $request,User $user)
    {
        if (!$user->fullProfile()) $request->session()->flash('bonus_modal',30);
        if ($user->is_admin) $this->redirectTo = '/admin';
        if ($request->ajax()) {
            return response()->json([
                'auth' => auth()->check(),
                'intended' => $this->redirectPath(),
            ]);
        }
    }

    public function showLoginForm()
    {
        $page = (new Setting('login'))->page();
        return view('auth.login', compact('page'));
    }
}
