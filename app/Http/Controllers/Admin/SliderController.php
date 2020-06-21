<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Settings\ImageTrait;
use App\Models\Pages\Slider;
use Illuminate\Http\Request;

class SliderController extends Controller
{
    use ImageTrait;
    private const DIR = 'admin.sliders.';

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view(self::DIR.'create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'file' => ['required', 'image', 'mimes:jpeg,jpg,png,gif,svg', 'max:2048'],
        ]);
        if ($image = $request->file('file')) $request['image'] = $this->uploadImage($image,'site/img/settings/sliders');

        Slider::query()->insert($request->only(['image', 'alt']));
        return redirect()->route('admin.pages.home')->with('status', 'успешные дествия !');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit($id)
    {
        $slider = Slider::findOrFail($id);
        return view(self::DIR . 'edit', compact('slider'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'file' => ['sometimes', 'image', 'mimes:jpeg,jpg,png,gif,svg', 'max:2048'],
        ]);
        $slider = Slider::query()->findOrFail($id);
        if ($image = $request->file('file')) $request['image'] = $this->uploadImage($image,'site/img/settings/sliders');
        if ($request['image'] && is_file(public_path($slider->image))) unlink(public_path($slider->image));
        $slider->update($request->only(['image', 'alt']));
        return redirect()->route('admin.pages.home')->with('status', 'успешные дествия !');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $slider = Slider::findOrFail($id);
        if (is_file(public_path($slider->image))) unlink(public_path($slider->image));
        $slider->delete();
        return redirect()->route('admin.pages.home')->with('status', 'успешные дествия !');
    }
}
