<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\PackageRequest;
use App\Models\Auction\Step;
use App\Models\Pages\Package;
use App\Settings\ImageTrait;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    use ImageTrait;

    private const DIR = 'admin.orders.';


    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit($id)
    {
        $info = 'Шаг ';
        $step = Step::query()->findOrFail($id);
        if ($step->step === 1) {
            $info .= (string)($step->step . ' Для ' . ($step->for_winner ? 'победителя' : 'остальных'));
        } elseif ($step->step === 2) {
            $info .= (string)$step->step;
            if ($step->type === Step::PRODUCT)
                $info .= ' Товар';
            elseif ($step->type === Step::MONEY)
                $info .= ' Деньги';
            elseif ($step->type === Step::BET)
                $info .= ' Ставки';
        }
        return view(self::DIR . 'edit', compact('step', 'info'));
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
        $step = Step::query()->findOrFail($id);
        $step->update($request->only(['text']));
        return redirect()->route('admin.pages.order')->with('status', 'успешные дествия !');
    }
}
