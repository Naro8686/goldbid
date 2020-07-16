<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Balance;
use App\Models\Mailing;
use App\Models\User;
use App\Models\Pages\Slider;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class UserController extends Controller
{
    private const DIR = 'admin.users.';

    public function index(Request $request)
    {
        if ($request->ajax()) {
            try {
                $users = new Collection;
                foreach (User::all()->where('is_admin', false) as $user) {
                    $users->push([
                        'id' => $user->id,
                        'created_at' => $user->created_at->format('Y-m-d'),
                        'nickname' => $user->nickname,
                        'lname' => $user->lname,
                        'birthday' => $user->birthday ? $user->birthday->format('Y-m-d') : '',
                        'phone' => $user->login(),
                        'email' => $user->email,
                        'win' => 0,
                        'participation' => 0,
                        'has_ban' => $user->has_ban,
                        'bet' => $user->balance()->bet,
                        'bonus' => $user->balance()->bonus,
                        'count_referral' => $user->referrals()->count(),
                    ]);
                }
                return datatables()->of($users)
                    ->editColumn('has_ban', function ($user) {
                        $class = '';
                        $active = 'false';
                        $link = route('admin.users.update', $user['id']);
                        if ($user['has_ban']) {
                            $class = 'active';
                            $active = 'true';
                        }
                        return "<button type='button' class='btn btn-sm btn-toggle {$class}'
                                data-toggle='button'
                                aria-pressed='{$active}'
                                onclick='oNoFF(`{$link}`,{has_ban:($(this).attr(`aria-pressed`) === `true` ? 0 : 1)},`PUT`)'>
                                    <span class='handle'></span>
                                </button>";
                    })->addColumn('action', function ($user) {
                        $linkDelete = route('admin.users.destroy', $user['id']);
                        $linkShow = route('admin.users.edit', $user['id']);
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
                    })
                    ->rawColumns(['has_ban', 'action'])
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
    public function edit($id)
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
