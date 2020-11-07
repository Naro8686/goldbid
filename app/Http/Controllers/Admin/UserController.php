<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Balance;
use App\Models\User;
use Exception;

class UserController extends Controller
{
    private const DIR = 'admin.users.';

    public function index(Request $request)
    {
        $users = User::where('users.is_admin', false)
            ->leftJoinSub('SELECT bids.user_id, SUM(IF(bids.win = 1, 1, 0)) AS win, COUNT(DISTINCT(bids.auction_id)) AS participation FROM bids GROUP BY bids.user_id', 'bidsCount', 'users.id', '=', 'bidsCount.user_id')
            ->leftJoinSub('SELECT balances.user_id, (SUM(IF(type = 1, 0, bet)) - SUM(IF(type = 1, bet, 0))) AS bet, (SUM(IF(type = 1, 0, bonus)) - SUM(IF(type = 1, bonus, 0))) AS bonus FROM balances GROUP BY balances.user_id', 'balance', 'users.id', '=', 'balance.user_id')
            ->leftJoinSub('SELECT referrals.referred_by, COUNT(referrals.referral_id) AS count_referral FROM referrals GROUP BY referrals.referred_by', 'ref', 'users.id', '=', 'ref.referred_by')
            ->groupBy(['users.id', 'bidsCount.win', 'bidsCount.participation', 'balance.bet', 'balance.bonus', 'ref.count_referral'])
            ->selectRaw('users.*, IFNULL(bidsCount.win, 0) AS win, IFNULL(bidsCount.participation, 0) AS participation, IFNULL(balance.bet,0) AS bet, IFNULL(balance.bonus,0) AS bonus, IFNULL(ref.count_referral,0) AS count_referral');
        if ($request->ajax()) {
            try {
                return datatables()->of($users)
                    ->editColumn('has_ban', function ($user) {
                        $link = route('admin.users.update', $user->id);
                        $class = '';
                        $active = 'false';
                        if ($user->has_ban) {
                            $class = 'active';
                            $active = 'true';
                        }
                        return "<button type='button'
                                class='btn btn-sm btn-toggle {$class}'
                                data-toggle='button'
                                aria-pressed='{$active}'
                                onclick='oNoFF(`{$link}`,{has_ban:($(this).attr(`aria-pressed`) === `true` ? 0 : 1)},`PUT`)'>
                                    <span class='handle'></span>
                                </button>";
                    })->editColumn('phone', function ($user) {
                        return $user->login();
                    })->editColumn('birthday', function ($user) {
                        return $user->birthday ? $user->birthday->format('Y-m-d') : null;
                    })->editColumn('created_at', function ($user) {
                        return $user->created_at->format('Y-m-d');
                    })->addColumn('action', function ($user) {
                        $linkDelete = route('admin.users.destroy', $user->id);
                        $linkShow = route('admin.users.edit', $user->id);
                        return "<div class='btn-group btn-group-sm' role='group' aria-label='Basic example'>
                                        <button data-href='{$linkShow}' type='button'
                                                data-toggle='modal' data-target='#cardModal'
                                                class='btn btn-info mr-1'><i class='fa fa-eye'></i></button>
                                        <button type='button' class='btn btn-danger' data-toggle='modal'
                                                data-target='#resourceModal'
                                                data-action='{$linkDelete}'>
                                                    удалить
                                        </button>
                                    </div>";
                    })->rawColumns(['has_ban', 'action'])
                    ->make(true);
            } catch (Exception $e) {
                dd($e->getMessage());
            }
        }
        $usersInfo = User::info();
        return view(self::DIR . 'index', compact('usersInfo'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     * @throws \Throwable
     */
    public function edit(int $id)
    {
        if ($user = User::query()->find($id)) {
            $data = $user->userCard();
            $html = view('admin.users.card', compact('data'))->render();
        } else {
            $html = "<div class='col-md-12'><h3 class='text-center text-danger'>Такого пользователя не существует</h3></div>";
        }
        return response(['success' => true, 'html' => $html, 'title' => 'Карточка пользователя']);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, $id)
    {
        $user = User::query()->findOrFail($id);
        if ($request->ajax()) {
            $request->validate([
                'has_ban' => ['required', 'boolean'],
            ]);
            $user->update($request->only('has_ban'));
            $banned = User::info()['banned'];
            return response()->json(['id_name' => 'count_banned', 'change_info' => $banned]);
        }
        $request->validate([
            'bet' => ['sometimes', 'required', 'integer', 'min:0'],
            'bonus' => ['sometimes', 'required', 'integer', 'min:0'],
            'old_bet' => ['sometimes', 'required', 'integer', 'min:0'],
            'old_bonus' => ['sometimes', 'required', 'integer', 'min:0'],
        ]);

        if ($request['bet'] > 0 || $request['bonus'] > 0)
            $user->balanceHistory()->create($request->only('bet', 'bonus', 'reason'));

        if ((int)$request['old_bet'] !== $user->balance()->bet) {
            $bet = abs($request['old_bet'] - $user->balance()->bet);
            if ($request['old_bet'] > $user->balance()->bet)
                $type = Balance::PLUS;
            else $type = Balance::MINUS;
            $user->balanceHistory()->create([
                'type' => $type,
                'bet' => $bet,
                'reason' => Balance::ADMIN,
            ]);
        }
        if ((int)$request['old_bonus'] !== $user->balance()->bonus) {
            $bonus = abs($request['old_bonus'] - $user->balance()->bonus);
            if ($request['old_bonus'] > $user->balance()->bonus)
                $type = Balance::PLUS;
            else $type = Balance::MINUS;

            $user->balanceHistory()->create([
                'type' => $type,
                'bonus' => $bonus,
                'reason' => Balance::ADMIN,
            ]);
        }
        return redirect()->back()->with('status', 'успешные дествия !');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($id)
    {
        $user = User::findOrFail($id);
        if (is_file(public_path($user->avatar))) unlink(public_path($user->avatar));
        $user->delete();
        return redirect()->back()->with('status', 'успешные дествия !');
    }
}
