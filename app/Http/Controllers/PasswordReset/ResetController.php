<?php

namespace App\Http\Controllers\PasswordReset;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Notifications\AccountApproved;
use App\Settings\Setting;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ResetController extends Controller
{
    public $page;

    public function __construct(Request $request)
    {
        $this->page = (new Setting($request->segment(1)))->page();
        view()->share('page', $this->page);
    }

    public function reset()
    {
        return view('auth.passwords.sms');
    }

    public function checkPhone(Request $request)
    {
        if ($request->session()->has('reset_id')) {
            $request->validate([
                'sms_code' => ['required', 'exists:users,sms_code'],
            ], [
                'sms_code.exists' => 'неправильный код.'
            ]);
            return redirect()->route('reset.password.change');
        }
        $request['phone'] = User::unsetPhoneMask($request['phone']);
        $request->validate([
            'phone' => ['required', 'exists:users,phone'],
        ], [
            'phone.exists' => 'Не удалось найти пользователя.'
        ]);
        $user = User::query()->where('phone', $request['phone'])->first();
        try {
            $user->update(['sms_code' => Setting::randomNumber(6)]);
            $user->notify(new AccountApproved);
        } catch (Exception $exception) {
            dd($exception->getMessage());
        }
        $request->session()->push('reset_id', $user->id);
        return redirect()->back();
    }

    public function passwordChange()
    {
        if (!session()->has('reset_id'))
            return abort(404);
        return view('auth.passwords.recovery');
    }

    public function passwordChangeSuccess(Request $request)
    {
        $request->validate([
            'new_password' => ['required', 'min:8'],
            'new_confirm_password' => ['required', 'same:new_password'],
        ]);
        $password = Hash::make($request['new_password']);
        $id = $request->session()->get('reset_id');
        $request->session()->forget('reset_id');
        if ($auth = User::query()->where('id', $id)->first()) {
            $auth->update(['password' => $password]);
            /** @var User $auth */
            Auth::login($auth);
        }
        return redirect()->route('site.home');
    }
}
