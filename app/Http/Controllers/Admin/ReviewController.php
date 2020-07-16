<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\ReviewRequest;
use App\Models\Pages\Review;
use App\Settings\ImageTrait;

class ReviewController extends Controller
{
    use ImageTrait;
    private const DIR = 'admin.reviews.';

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create()
    {
        return view(self::DIR . 'create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(ReviewRequest $request)
    {
        $request->validate([
            'file' => ['required', 'image', 'mimes:jpeg,jpg,png,gif,svg', 'max:2048'],
        ]);
        if ($image = $request->file('file')) $request['image'] = $this->uploadImage($image, 'site/img/settings/reviews', 300, 200);
        Review::query()->create($request->only(['image', 'alt', 'title', 'description']));
        return redirect()->route('admin.pages.reviews')->with('status', 'успешные дествия !');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit($id)
    {
        $review = Review::findOrFail($id);
        return view(self::DIR . 'edit', compact('review'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(ReviewRequest $request, $id)
    {
        $request->validate([
            'file' => ['sometimes', 'image', 'mimes:jpeg,jpg,png,gif,svg', 'max:2048'],
        ]);
        $review = Review::query()->findOrFail($id);
        if ($image = $request->file('file')) {
            if ($request['image'] = $this->uploadImage($image, 'site/img/settings/reviews', 300, 200))
                if (is_file(public_path($review->image))) unlink(public_path($review->image));
        }
        $review->update($request->only(['image', 'alt', 'title', 'description']));
        return redirect()->route('admin.pages.reviews')->with('status', 'успешные дествия !');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($id)
    {
        $review = Review::findOrFail($id);
        if (is_file(public_path($review->image))) unlink(public_path($review->image));
        $review->delete();
        return redirect()->route('admin.pages.reviews')->with('status', 'успешные дествия !');
    }
}
