<?php

namespace App\Http\Controllers\Payments;

use App\Http\Controllers\Controller;
use App\Models\Balance;
use App\Models\Pages\Package;
use App\Models\User;
use App\Settings\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use function GuzzleHttp\Promise\all;

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
        $request->validate([
            "coupon_id" => ['required', 'integer'],
            "payment_id" => ['required', 'integer'],
        ]);
        if (!$this->user->email) {
            $request->validate([
                "email" => ['required','email','unique:users'],
            ]);
            $this->user->update(['email'=> $request['email']]);
        }

        $coupon = Package::where('visibly', true)->findOrFail($request['coupon_id']);
        $this->user->balanceHistory()
            ->create([
                'bonus' => $coupon->bonus,
                'bet' => $coupon->bet,
                'reason' => Balance::PURCHASE_BONUS_REASON
            ]);

        return redirect()->back();
    }
}
