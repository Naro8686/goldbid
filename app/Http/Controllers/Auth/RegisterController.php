<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use App\Models\User;
use App\Settings\Setting;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller
{
    protected $rules = [
        'nickname.unique' => 'этот ник уже занят',
        'phone.unique' => 'этот номер уже используется',
        'g-recaptcha-response.required' => 'проверка captcha не пройдена',
    ];
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
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
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param array $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        $data['phone'] = User::unsetPhoneMask($data['phone']);
        return Validator::make($data, [
            'nickname' => ['required', 'string', 'max:16', 'unique:users'],
            'phone' => ['required', 'numeric', 'digits:11', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'terms_of_use' => ['required'],
            'personal_data' => ['required'],
            'privacy_policy' => ['required'],
            'g-recaptcha-response' => ['required', 'recaptcha']
        ], $this->rules);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param array $data
     * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Model
     */
    protected function create(array $data)
    {
        $referred_by = Cookie::get('referral');
        $user = User::query()->create([
            'nickname' => $data['nickname'],
            'phone' => User::unsetPhoneMask($data['phone']),
            'password' => Hash::make($data['password']),
        ]);
        $user->referred()->attach($referred_by);
        return $user;
    }

    public function showRegistrationForm()
    {
        $page = (new Setting('register'))->page();
        return view('auth.register', compact('page'));
    }

}
