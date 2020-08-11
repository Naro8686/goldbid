<?php

namespace App\Http\Controllers\Payments;

use App\Http\Controllers\Controller;
use App\Mail\CouponOrderSendMail;
use App\Models\Balance;
use App\Models\CouponOrder;
use App\Models\Pages\Package;
use App\Models\User;
use App\Settings\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class CouponController extends Controller
{
    /**
     * @var User|null $user
     */
    private $user;

    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware(function ($request, $next) {
            $this->user = Auth::user();
            view()->share('user', $this->user);
            return $next($request);
        });
    }

    public function buy(Request $request)
    {
        $request['order'] = Setting::orderNumCoupon($this->user->id);
        $request->validate([
            "coupon_id" => ['required', 'integer'],
            "payment_id" => ['required', 'integer'],
            "order" => ['required', 'unique:coupon_orders'],
        ]);
        $request['payment_type'] = Setting::paymentCoupon($request['payment_id']);
        $request['user_id'] = $this->user->id;
        $coupon = Package::where('visibly', true)->findOrFail($request['coupon_id']);
        if (!$this->user->email) {
            $request->validate([
                "email" => ['required', 'email', 'unique:users'],
            ]);
            $this->user->update(['email' => $request['email']]);
        }
        $coupon_order = CouponOrder::query()->create($request->only(['user_id', 'coupon_id', 'order', 'payment_type']));
        $this->user->balanceHistory()
            ->create([
                'bonus' => $coupon->bonus,
                'bet' => $coupon->bet,
                'reason' => Balance::PURCHASE_BONUS_REASON
            ]);
        $referred = $this->user->referred()->first();
        if ($referred && $this->user->fullProfile() && $referred->pivot->referral_bonus === 0) {
            $referralBonus = $coupon->bet / 2;
            $referred->pivot->update(['referral_bonus' => $referralBonus]);
            $referred->balanceHistory()->create(['bonus' => $referred->pivot->referral_bonus, 'reason' => Balance::REFERRAL_BONUS_REASON]);
        }
        /** @var CouponOrder $coupon_order */
        try {
            Mail::to(config('mail.from.address'))->later(5, new CouponOrderSendMail($coupon_order));
        } catch (\Throwable $exception) {
            Log::error('order_coupon' . $exception->getMessage());
        }
        return redirect()->back();
    }
}
