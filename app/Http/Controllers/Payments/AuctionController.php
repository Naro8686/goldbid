<?php

namespace App\Http\Controllers\Payments;

use App\Http\Controllers\Controller;
use App\Mail\AuctionOrderSendMail;
use App\Mail\MailingSendMail;
use App\Models\Auction\Auction;
use App\Models\Auction\Order;
use App\Models\Auction\Step;
use App\Models\Balance;
use App\Models\Mailing;
use App\Models\User;
use App\Settings\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class AuctionController extends Controller
{
    public function __construct()
    {
        view()->share(['page' => (new Setting('order'))->page()]);
    }

    public function winInfo($id, Request $request)
    {
        $data = [];
        $user = User::query()->findOrFail(Auth::id());
        $auction = Auction::auctionPage($id);
        if (!$auction['my_win']) return abort(403);
        $data['id'] = $auction['id'];
        $data['image'] = $auction['images'][0]['img'];
        $data['alt'] = $auction['images'][0]['alt'];
        $data['title'] = $auction['title'];
        $data['price'] = $auction['price'];
        $data['bet'] = $auction['exchangeBetBonus']['bet'];
        $data['bonus'] = $auction['exchangeBetBonus']['bonus'];
        if ((bool)$request["exchange"]) {
            try {
                DB::beginTransaction();
                $user->auctionOrder()
                    ->create([
                        'status' => Order::SUCCESS,
                        'exchanged' => true,
                        'auction_id' => $auction['id']
                    ]);
                $user->balanceHistory()->create([
                    'type' => Balance::PLUS,
                    'bet' => $data['bet'],
                    'bonus' => $data['bonus'],
                    'reason' => Balance::EXCHANGE_REASON,
                ]);
                DB::commit();
            } catch (\Exception $exception) {
                DB::rollBack();
            }
            return redirect()->route('profile.balance');
        } else {
            if ($request->ajax()) {
                try {
                    $modal = view('site.include.winner_modal', compact('data'))->render();
                } catch (\Throwable $e) {
                    $modal = $e->getMessage();
                }
                return response()->json($modal);
            }
        }
    }

    public function order($id, Request $request)
    {
        /** @var Auction $auction */
        /** @var User $user */
        /** @var Order $order */
        $auction = Auction::query()->where('active', true)->findOrFail($id);
        $user = User::query()->findOrFail(Auth::id());

        $request->validate([
            'step' => ['required', 'integer', 'between:1,3']
        ]);
        $step = (int)$request['step'];
        $winner = ($auction->status === Auction::STATUS_FINISHED)
            ? ($auction->winner()->nickname === $user->nickname)
            : false;
        $order = $user->auctionOrder()->where('auction_id', '=', $auction->id)->first();
        if (is_null($order)) {
            $order = $user
                ->auctionOrder()
                ->create([
                    'order_num' => Setting::orderNumAuction($user->id),
                    'status' => Order::PENDING,
                    'exchanged' => false,
                    'auction_id' => $auction->id
                ]);
        }
        $type = $auction->type();
        if ($type !== Step::PRODUCT && !$winner) return abort(404);
        switch ($step) {
            case 1:
                return $this->stepOne($auction, $user, $order, $winner);
            case 2:
                return $this->stepTwo($auction, $user, $type);
            case 3:
                return $this->stepThree($auction, $user, $order, $type);
        }
    }

    public function stepOne(Auction $auction, User $user, Order $order, bool $winner, $data = [])
    {
        $step = Step::all()->where('for_winner', $winner)->where('step', 1)->first();
        $data['text'] = $step->textReplace(['title' => $auction->title]);
        $data['title'] = $auction->title;
        $data['auction_id'] = $auction->id;
        $data['img'] = $auction->img_1;
        $data['alt'] = $auction->alt_1;
        $data['order_num'] = $order->order_num;
        $data['winner'] = $winner;
        if (!$winner) {
            $data['full_price'] = number_format($auction->full_price, 1);
            $data['bid_price'] = number_format($user->bid_price($auction->id), 1);
            $data['total_price'] = $auction->full_price($user->id);
            $order->update(['price' => $data['total_price'], 'timestamp' => false]);
        } else {
            $data['auction_price'] = $auction->price();
            $order->update(['price' => $data['auction_price'], 'timestamp' => false]);
        }
        return view('site.order.step_1', compact('data'));
    }

    public function stepTwo(Auction $auction, User $user, $type, $data = [])
    {
        $data['auction_id'] = $auction->id;
        $data['type'] = $type;
        $data['lname'] = $user->lname;
        $data['fname'] = $user->fname;
        $data['mname'] = $user->mname;
        $step = Step::all()->where('type', $type)->where('step', 2)->first();
        if ($type === Step::PRODUCT) {
            $replace['title'] = $auction->title;
            $data['country'] = $user->country;
            $data['postcode'] = $user->postcode;
            $data['region'] = $user->region;
            $data['city'] = $user->city;
            $data['street'] = $user->street;
            $data['phone'] = $user->login();
        } elseif ($type === Step::MONEY) {
            $replace['money'] = $auction->title;
            $data['ccnum'] = $user->ccnum;
            $data['payment_type'] = $user->paymentType();
        } else {
            $replace = $auction->exchangeBetBonus($user->id);
        }
        $data['text'] = $step->textReplace($replace);
        return view('site.order.step_2', compact('data'));
    }

    public function stepThree(Auction $auction, User $user, Order $order, $type, $data = [])
    {
        $data['lname'] = $user->lname;
        $data['fname'] = $user->fname;
        $data['mname'] = $user->mname;
        if ($type === Step::PRODUCT) {
            $data['country'] = $user->country;
            $data['postcode'] = $user->postcode;
            $data['region'] = $user->region;
            $data['city'] = $user->city;
            $data['street'] = $user->street;
            $data['phone'] = $user->login();
        } elseif ($type === Step::MONEY) {
            $data['ccnum'] = $user->ccnum;
            $data['payment_type'] = $user->paymentType();
        }
        $validate = array_filter($data, static function ($var) {
            return $var === null;
        });
        if ($type !== Step::BET && (boolean)count($validate))
            return redirect()->back()->with('message', 'Заполните все необходимые поля в личном кабинете');
        $data['auction_id'] = $auction->id;
        $data['title'] = $auction->title;
        $data['email'] = $user->email;
        $data['order_num'] = $order->order_num;
        $data['price'] = $order->price;
        $payments = Setting::paymentCoupon(null);
        return view('site.order.step_3', compact('data', 'payments'));
    }

    public function buy(Request $request)
    {
        $request->validate([
            "payment_id" => ['required', 'integer', function ($attribute, $value, $fail) {
                if (is_null(Setting::paymentCoupon($value)))
                    $fail('error');
            }],
            "order_num" => ['required', 'exists:orders,order_num'],
        ]);
        $user = User::query()->findOrFail(Auth::id());
        $order = $user->auctionOrder()->where('order_num', '=', $request['order_num'])->firstOrFail();
        $request['payment_type'] = Setting::paymentCoupon($request['payment_id']);
        if (!$user->email) {
            $request->validate([
                "email" => ['required', 'email', 'unique:users'],
            ]);
            $user->update(['email' => $request['email']]);
        }
        $order->update(['payment_type' => $request['payment_type']]);
        $auction = $order->auction;
        if (!isset($auction)) return abort(404);
        if ($auction->type() === Step::BET) {
            $data = $auction->exchangeBetBonus($user->id);
            $user->balanceHistory()->create([
                'type' => Balance::PLUS,
                'bet' => $data['bet'],
                'bonus' => $data['bonus'],
                'reason' => Balance::WIN_REASON,
            ]);
        }
        try {
            Mail::to(config('mail.from.address'))->later(2, new AuctionOrderSendMail($order));
            Mail::to($user->email)->later(5, new MailingSendMail(Mailing::CHECKOUT, ['order_num' => $order->order_num]));
        } catch (\Exception $e) {
            Log::info($e->getMessage());
        }
        $order->update(['status' => Order::SUCCESS]);
        return redirect()->route('site.home')->with('message', 'Вы успешно оформили заказ ');
    }
}
