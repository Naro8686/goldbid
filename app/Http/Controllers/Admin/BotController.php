<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
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
        return view(self::DIR . 'index', compact('names'));
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
        return view(self::DIR . 'edit');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param int $id
     * @return RedirectResponse
     */
    public function update(Request $request, $id)
    {
        return redirect()->route('admin.pages.order')->with('status', 'успешные дествия !');
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
