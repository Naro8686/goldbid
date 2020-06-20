<?php

namespace App\Http\Controllers\Admin;

use App\Howitwork;
use App\Http\Controllers\Controller;
use App\Settings\ImageTrait;
use Illuminate\Http\Request;

class HowitworkController extends Controller
{
    use ImageTrait;
    private const DIR = 'admin.howitworks.';

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create()
    {
        return view(self::DIR.'create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $request->validate([
            'file' => ['required', 'image', 'mimes:jpeg,jpg,png,gif,svg', 'max:2048'],
        ]);
        if ($image = $request->file('file')) $request['image'] = $this->uploadImage($image,'site/img/settings/howitworks/steps',582,203);

        Howitwork::query()->create($request->only(['image','alt']));
        return redirect()->route('admin.pages.howitworks')->with('status', 'успешные дествия !');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit($id)
    {
        $step = Howitwork::findOrFail($id);
        return view(self::DIR . 'edit', compact('step'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'file' => ['sometimes', 'image', 'mimes:jpeg,jpg,png,gif,svg', 'max:2048'],
        ]);
        $step = Howitwork::query()->findOrFail($id);
        if ($image = $request->file('file')) $request['image'] = $this->uploadImage($image,'site/img/settings/howitworks/steps',582,203);
        if ($request['image'] && is_file(public_path($step->image))) unlink(public_path($step->image));
        $step->update($request->only(['image','alt']));
        return redirect()->route('admin.pages.howitworks')->with('status', 'успешные дествия !');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($id)
    {
        $step = Howitwork::findOrFail($id);
        if (is_file(public_path($step->image))) unlink(public_path($step->image));
        $step->delete();
        return redirect()->route('admin.pages.howitworks')->with('status', 'успешные дествия !');
    }
}
