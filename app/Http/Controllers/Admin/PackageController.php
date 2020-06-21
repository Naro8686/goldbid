<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\PackageRequest;
use App\Models\Pages\Package;
use App\Settings\ImageTrait;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class PackageController extends Controller
{
    use ImageTrait;
    private const DIR = 'admin.packages.';

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
     * @param PackageRequest $request
     * @return RedirectResponse
     */
    public function store(PackageRequest $request)
    {
        $request->validate([
            'file' => ['required', 'image', 'mimes:jpeg,jpg,png,gif,svg', 'max:2048'],
        ]);
        if ($image = $request->file('file')) $request['image'] = $this->uploadImage($image, 'site/img/settings/packages',200,260);
        Package::query()->create($request->only(['image', 'alt', 'bet', 'bonus', 'price']));
        return redirect()->route('admin.pages.coupon')->with('status', 'успешные дествия !');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit($id)
    {
        $package = Package::findOrFail($id);
        return view(self::DIR . 'edit', compact('package'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param PackageRequest $request
     * @param int $id
     * @return RedirectResponse
     */
    public function update(PackageRequest $request, $id)
    {
        $request->validate([
            'file' => ['sometimes', 'image', 'mimes:jpeg,jpg,png,gif,svg', 'max:2048'],
        ]);
        $package = Package::query()->findOrFail($id);
        if ($image = $request->file('file')) {
            if ($request['image'] = $this->uploadImage($image, 'site/img/settings/packages',200,260))
                if (is_file(public_path($package->image))) unlink(public_path($package->image));
        }
        $package->update($request->only(['image', 'alt', 'bet', 'bonus', 'price', 'visibly']));
        return redirect()->route('admin.pages.coupon')->with('status', 'успешные дествия !');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return RedirectResponse
     */
    public function destroy($id)
    {
        $package = Package::findOrFail($id);
        if (is_file(public_path($package->image))) unlink(public_path($package->image));
        $package->delete();
        return redirect()->route('admin.pages.coupon')->with('status', 'успешные дествия !');
    }
}
