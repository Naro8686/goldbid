<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Bots\Bot;
use App\Models\Bots\BotName;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Throwable;

class BotController extends Controller
{
    private const DIR = 'admin.bots.';


    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $names = BotName::all();
        $botOne = Bot::query()->where('number', 1)->first();
        $bots = Bot::query()->where('number', '<>', 1)->get();
        return view(self::DIR . 'index', compact('names', 'botOne', 'bots'));
    }

    public function create()
    {
        return view(self::DIR . 'create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:16', 'unique:users,nickname,' . $request['name'], 'unique:bot_names', 'without_spaces'],
        ], ['name.unique' => 'Этот ник уже занят']);
        BotName::query()->create($request->only(['name']));
        return redirect()->route('admin.bots.index')->with('status', 'успешные дествия !');
    }

    public function edit($id)
    {
        $bot = Bot::query()->findOrFail($id);
        return view(self::DIR . 'edit', compact('bot'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param int $id
     * @return RedirectResponse
     */
    public function update(Request $request, int $id)
    {
        $bot = Bot::query()->findOrFail($id);
        if ($request->ajax()) {
            $request->validate([
                'is_active' => ['sometimes', 'required', 'boolean'],
            ]);
            $bot->update($request->only(['is_active']));
        } else {
            $request->validate([
                'time_to_bet' => ['required', function ($attribute, $value, $fail) {
                    list($max, $min) = array_pad(str_replace(' ', '', explode('-', $value)), 2, null);
                    if ((is_null($min) && $max !== '0') || is_null($max) || $min > $max)
                        $fail('заполните поля правильно');
                }],
                'change_name' => ['sometimes', 'required', function ($attribute, $value, $fail) {
                    list($min, $max) = array_pad(str_replace(' ', '', explode('-', $value)), 2, null);
                    if (is_null($min) || is_null($max) || $min > $max)
                        $fail('заполните поля правильно');
                }],
                'num_moves' => ['sometimes', 'required', function ($attribute, $value, $fail) {
                    list($min, $max) = array_pad(str_replace(' ', '', explode('-', $value)), 2, null);
                    if (is_null($min) || is_null($max) || $min > $max)
                        $fail('заполните поля правильно');
                }],
                'num_moves_other_bot' => ['sometimes', 'required', function ($attribute, $value, $fail) {
                    list($min, $max) = array_pad(str_replace(' ', '', explode('-', $value)), 2, null);
                    if (is_null($min) || is_null($max) || $min > $max)
                        $fail('заполните поля правильно');
                }],
            ],[
                'time_to_bet.required' => 'Это поле обезательно для заполнения ',
                'change_name.required' => 'Это поле обезательно для заполнения ',
                'num_moves.required' => 'Это поле обезательно для заполнения ',
                'num_moves_other_bot.required' => 'Это поле обезательно для заполнения ',
            ]);
            $bot->update($request->only(['time_to_bet','change_name','num_moves','num_moves_other_bot']));
        }
        return redirect()->route('admin.bots.index')->with('status', 'успешные дествия !');
    }

    public function nameDelete($id)
    {
        try {
            BotName::query()->findOrFail($id)->delete();
            return redirect()->back()->with('status', 'успешные дествия !');
        } catch (Throwable $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }
}
