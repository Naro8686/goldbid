<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Mail\MailingSendMail;
use App\Models\Balance;
use App\Models\Mailing;
use App\Models\User;
use App\Rules\OldPasswordRule;
use App\Settings\ImageTrait;
use App\Settings\Setting;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;


class ProfileController extends Controller
{
    use ImageTrait;

    /**
     * @var User|null $user
     */
    private $user;
    private $slug = 'cabinet';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware(function ($request, $next) {
            $this->user = Auth::user();
            if ($this->user->is_admin)
                return redirect()->route('admin.dashboard');
            if ($this->user->fullProfile()) {
                $this->user->balanceHistory()
                    ->where('reason', Balance::REGISTRATION_BONUS_REASON)
                    ->firstOrCreate([
                        'bonus' => Balance::bonusCount(Balance::REGISTRATION_BONUS_REASON),
                        'reason' => Balance::REGISTRATION_BONUS_REASON,
                    ]);
            }
            view()->share(['user' => $this->user, 'page' => (new Setting($this->slug))->page()]);
            return $next($request);
        });
    }

    public function index(Request $request)
    {
        if ($request->isMethod('POST')) {
            if ($request->hasFile('file')) {
                $request->validate([
                    'file' => ['image', 'mimes:jpeg,jpg,png,gif,svg', 'max:2048'],
                ]);
            } else if ($request->hasAny(['current_password', 'new_password', 'new_confirm_password'])) {
                $request->validate([
                    'current_password' => ['required', new OldPasswordRule],
                    'new_password' => ['required', 'min:8'],
                    'new_confirm_password' => ['required', 'same:new_password'],
                ]);
                $request['password'] = Hash::make($request['new_password']);
            }
            if ($image = $request->file('file')) {
                $request['avatar'] = $this->uploadImage($image, 'site/img/users/avatar', 250, 250);
                if ($request['avatar'] && is_file(public_path($this->user->avatar))) unlink(public_path($this->user->avatar));
            }
            $this->user->update($request->only(['avatar', 'password']));
            return redirect()->back()->with('status', 'Изменения успешно сохранились ');
        }
        $mailings = Mailing::ads();
        return view('user.index', compact('mailings'));
    }

    public function personalData(Request $request)
    {
        $payments = Setting::paymentType(null);
        if ($request->isMethod('POST')) {
            $request['email'] = empty($this->user->email) ? $request['email'] : $this->user->email;
            $request->validate([
                'fname' => ['nullable', 'sometimes', 'string', 'min:5', 'max:50'],
                'lname' => ['nullable', 'string', 'min:5', 'max:50'],
                'mname' => ['nullable', 'string', 'min:5', 'max:50'],
                'gender' => ['nullable', 'string', 'regex:/^(male|female)/'],
                'birthday' => ['nullable', 'date', 'date_format:Y-m-d', 'before:16 years ago'],
                'postcode' => ['nullable', 'digits_between:4,10'],
                'region' => ['nullable', 'string', 'min:5', 'max:50'],
                'city' => ['nullable', 'string', 'min:5', 'max:50'],
                'street' => ['nullable', 'string', 'min:5', 'max:50'],
                'email' => ['nullable', 'email', 'max:100', 'unique:users,email,' . $this->user->id],
                'payment_type' => ['nullable', 'integer', 'min:' . min($payments)['id'], 'max:' . max($payments)['id']],
                'ccnum' => ['nullable', 'digits_between:8,16'],
            ]);
            $this->user->update($request->only([
                'lname', 'fname', 'mname', 'gender',
                'birthday', 'postcode', 'region', 'city',
                'street', 'email', 'payment_type', 'ccnum'
            ]));
            return redirect()->back()->with('status', 'Изменения успешно сохранились ');
        }
        return view('user.personal_data', compact('payments'));
    }

    public function balance()
    {
        $balance = $this->user->balanceHistory()
            ->where('type', Balance::PLUS)
            ->paginate(10);
        return view('user.balance', compact('balance'));
    }

    public function auctionsHistory()
    {
        $bids = $this->user->bid->groupBy('auction_id');

        $bids = $bids->map(function ($item) {
            return collect([
                'bonus' => $item->sum('bonus'),
                'bet' => $item->sum('bet'),
                'win' => (bool)$item->where('win', true)->count(),
                'title' => $item->sortDesc()->first()->title,
                'end' => $item->sortDesc()->first()->created_at->format('Y-m-d H:i:s')
            ]);
        });
        return view('user.auctions_history', compact('bids'));
    }

    public function referralProgram()
    {
        $referrals = $this->user->referrals;
        return view('user.referral_program', compact('referrals'));
    }

    public function subscribe(Request $request, int $id)
    {
        $request->validate([
            'subscribe' => ['required', 'boolean']
        ]);
        $subscribe = $this->user->subscribe();
        if ($subscribe->where('id', $id)->exists())
            $subscribe->detach($id);
        else
            $subscribe->attach($id);
    }

    public function favorite(Request $request, int $id)
    {
        $request->validate([
            'auction_id' => ['required', 'boolean']
        ]);
        $subscribe = $this->user->subscribe();
        if ($subscribe->where('id', $id)->exists())
            $subscribe->detach($id);
        else
            $subscribe->attach($id);
    }

    public function codeEmailConfirm(Request $request)
    {
        $text = 'Код отправлен на Ваш е-майл !';
        $status = 'status';
        if ($request->isMethod('POST')) {
            $request->validate([
                'code' => ['required', function ($attribute, $value, $fail) {
                    if ((string)$value !== (string)$this->user->email_code)
                        $fail('код не правильный !');
                }],
            ]);
            if ($this->user->update(['email_code_verified' => now()]))
                $text = 'Спасибо за подтверждение майла !';
        } else {
            if (!empty($this->user->email)) {
                try {
                    $request['theme'] = Setting::feedbackTheme($request['theme']);
                    Mail::to($this->user->email)->later(5, new MailingSendMail(Mailing::MAIL_CONFIRM,[],$this->user));
                } catch (Exception $exception) {
                    $text = 'что то пошло не так !';
                    $status = 'error';
                    Log::error($exception->getMessage());
                }
            }
        }
        return redirect()->back()->with($status, $text);
    }

}
