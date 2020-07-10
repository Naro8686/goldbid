<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProductRequest;
use App\Models\Auction\Auction;
use App\Models\Auction\Category;
use App\Models\Auction\Company;
use App\Models\Auction\Product;
use App\Models\Pages\Page;
use App\Settings\ImageTrait;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    use ImageTrait;
    private const DIR = 'admin.products.';
    public $width = 500;
    public $height = 500;

    public function index(Request $request)
    {
        if ($request->ajax()) {
            try {
                $products = Product::data();
                return datatables()->of($products)->editColumn('exchange', function ($product) {
                    $class = '';
                    $active = 'false';
                    $link = route('admin.products.update', $product['id']);
                    if ($product['exchange']) {
                        $class = 'active';
                        $active = 'true';
                    }
                    return "<button type='button' class='btn btn-sm btn-toggle {$class}'
                                data-toggle='button'
                                aria-pressed='{$active}'
                                onclick='oNoFF(`{$link}`,{exchange:($(this).attr(`aria-pressed`) === `true` ? 0 : 1)},`PUT`)'>
                                    <span class='handle'></span>
                                </button>";
                })->editColumn('img_1', function ($product) {
                    $img = asset($product['img_1']);
                    return "<img class='img-fluid img-thumbnail' src='{$img}' alt='{$product['alt_1']}'>";
                })->editColumn('top', function ($product) {
                    $class = '';
                    $active = 'false';
                    $link = route('admin.products.update', $product['id']);
                    if ($product['top']) {
                        $class = 'active';
                        $active = 'true';
                    }
                    return "<button type='button' class='btn btn-sm btn-toggle {$class}'
                                data-toggle='button'
                                aria-pressed='{$active}'
                                onclick='oNoFF(`{$link}`,{top:($(this).attr(`aria-pressed`) === `true` ? 0 : 1)},`PUT`)'>
                                    <span class='handle'></span>
                                </button>";
                })->editColumn('visibly', function ($product) {
                    $class = '';
                    $active = 'false';
                    $link = route('admin.products.update', $product['id']);
                    if ($product['visibly']) {
                        $class = 'active';
                        $active = 'true';
                    }
                    return "<button type='button' class='btn btn-sm btn-toggle {$class}'
                                data-toggle='button'
                                aria-pressed='{$active}'
                                onclick='oNoFF(`{$link}`,{visibly:($(this).attr(`aria-pressed`) === `true` ? 0 : 1)},`PUT`)'>
                                    <span class='handle'></span>
                                </button>";
                })->addColumn('action', function ($product) {
                    $linkDelete = route('admin.products.destroy', $product['id']);
                    $linkEdit = route('admin.products.edit', $product['id']);
                    return "<div class='btn-group btn-group-sm' role='group' aria-label='Basic example'>
                                        <a href='{$linkEdit}' class='btn btn-info'>изменить</a>
                                        <button type='button' class='btn btn-danger'
                                                data-toggle='modal'
                                                data-target='#resourceModal'
                                                data-action='{$linkDelete}'>
                                                    удалить
                                        </button>
                                    </div>";
                })->rawColumns(['img_1', 'exchange', 'top', 'visibly', 'action'])->make(true);
            } catch (Exception $e) {
                dd($e->getMessage());
            }
        }
        $productsInfo = Product::info();
        return view(self::DIR . 'index', compact('productsInfo'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \Throwable
     */
    public function edit($id)
    {
        $product = Product::query()->findOrFail($id);
        $companies = Company::all();
        $categories = Category::all();
        return view(self::DIR . 'edit', compact('product', 'companies', 'categories'));
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
        $product = Product::query()->with('auction')->findOrFail($id);
        if ($request->ajax()) {
            $request->validate([
                'exchange' => ['sometimes', 'required', 'boolean'],
                'top' => ['sometimes', 'required', 'boolean'],
                'visibly' => ['sometimes', 'required', 'boolean'],
            ]);
            $product->update($request->only(['exchange', 'top', 'visibly']));
        } else {
            $request->validate([
                'title' => ['required', 'string', 'max:30'],
                'short_desc' => ['nullable', 'string', 'max:30'],
                'desc' => ['string', 'nullable'],
                'specify' => ['string', 'nullable'],
                'terms' => ['string', 'nullable'],
                'start_price' => ['required', 'numeric', 'min:1'],
                'full_price' => ['required', 'numeric', 'min:0'],
                'bot_shutdown_price' => ['required', 'numeric', 'min:1'],
                'step_time' => ['required', 'integer', 'min:1'],
                'step_price' => ['required', 'integer', 'min:1'],
                'to_start' => ['required', 'integer', 'min:1'],
                'file_1' => ['sometimes', 'image', 'mimes:jpeg,jpg,png,gif,svg', 'max:2048'],
                'file_2' => ['sometimes', 'image', 'mimes:jpeg,jpg,png,gif,svg', 'max:2048'],
                'file_3' => ['sometimes', 'image', 'mimes:jpeg,jpg,png,gif,svg', 'max:2048'],
                'file_4' => ['sometimes', 'image', 'mimes:jpeg,jpg,png,gif,svg', 'max:2048'],
                'alt_1' => ['nullable', 'string', 'max:50'],
                'alt_2' => ['nullable', 'string', 'max:50'],
                'alt_3' => ['nullable', 'string', 'max:50'],
                'alt_4' => ['nullable', 'string', 'max:50'],
            ]);
            $request->merge([
                'visibly' => $request['visibly'] === 'on',
                'exchange' => $request['exchange'] === 'on',
            ]);
            if ($request->file('file_1')) {
                $request['img_1'] = $this->uploadImage($request->file('file_1'), 'site/img/product', $this->width, $this->height);
                if ($request['img_1'] && is_file(public_path($product->img_1))) unlink(public_path($product->image));
            }
            if ($request->file('file_2')) {
                $request['img_2'] = $this->uploadImage($request->file('file_2'), 'site/img/product', $this->width, $this->height);
                if ($request['img_2'] && is_file(public_path($product->img_2))) unlink(public_path($product->image));
            }
            if ($request->file('file_3')) {
                $request['img_3'] = $this->uploadImage($request->file('file_3'), 'site/img/product', $this->width, $this->height);
                if ($request['img_3'] && is_file(public_path($product->img_3))) unlink(public_path($product->image));
            }
            if ($request->file('file_4')) {
                $request['img_4'] = $this->uploadImage($request->file('file_4'), 'site/img/product', $this->width, $this->height);
                if ($request['img_4'] && is_file(public_path($product->img_4))) unlink(public_path($product->image));
            }
            $product->update($request->only([
                "title", "short_desc", "company_id",
                "category_id", "start_price", "full_price",
                "bot_shutdown_price", "step_time", "step_price",
                "to_start", "exchange", "visibly", "desc",
                "specify", "terms",
                "img_1", "img_2", "img_3", "img_4",
                "alt_1", "alt_2", "alt_3", "alt_4",
            ]));
        }
        if ($request['visibly']) {
            /** @var Product $product */
            $this->createAuction($product);
        }
        return $request->ajax()
            ? response()->json(['id_name' => 'visibly_count', 'change_info' => $product::info()['visibly_count']])
            : redirect()->route('admin.products.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     * @throws Exception
     */
    public function destroy($id)
    {
        $product = Product::query()->findOrFail($id);
        try {
            if (is_file(public_path($product->img_1))) unlink(public_path($product->img_1));
            if (is_file(public_path($product->img_2))) unlink(public_path($product->img_2));
            if (is_file(public_path($product->img_3))) unlink(public_path($product->img_3));
            if (is_file(public_path($product->img_4))) unlink(public_path($product->img_4));
            $product->delete();
        } catch (Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
        return redirect()->back()->with('status', 'успешные дествия !');
    }

    public function create()
    {
        $companies = Company::all();
        $categories = Category::all();
        return view(self::DIR . 'create', compact('companies', 'categories'));
    }

    public function store(ProductRequest $request)
    {
        $request->merge([
            'visibly' => $request['visibly'] === 'on',
            'exchange' => $request['exchange'] === 'on',
        ]);

        if ($request->file('file_1')) $request['img_1'] = $this->uploadImage($request->file('file_1'), 'site/img/product', $this->width, $this->height);
        if ($request->file('file_2')) $request['img_2'] = $this->uploadImage($request->file('file_2'), 'site/img/product', $this->width, $this->height);
        if ($request->file('file_3')) $request['img_3'] = $this->uploadImage($request->file('file_3'), 'site/img/product', $this->width, $this->height);
        if ($request->file('file_4')) $request['img_4'] = $this->uploadImage($request->file('file_4'), 'site/img/product', $this->width, $this->height);

        $product = Product::query()->create($request->only([
            "title", "short_desc", "company_id",
            "category_id", "start_price", "full_price",
            "bot_shutdown_price", "step_time", "step_price",
            "to_start", "exchange", "visibly", "desc",
            "specify", "terms",
            "img_1", "img_2", "img_3", "img_4",
            "alt_1", "alt_2", "alt_3", "alt_4",
        ]));
        if ($request['visibly'] === true) {
            /** @var Product $product */
            $this->createAuction($product);
        }

        return redirect()->route('admin.products.index')->with('status', 'успешные дествия !');
    }

    public function addGroup(Request $request)
    {
        $request->validate([
            'company_name' => ['string', 'max:100', 'nullable'],
            'category_name' => ['string', 'max:100', 'nullable'],
        ]);
        if ($request['company_name']) Company::query()->create(['name' => $request['company_name']]);
        if ($request['category_name']) Category::query()->create(['name' => $request['category_name']]);
        return redirect()->back()->with('status', 'успешные дествия !');
    }

    public function createAuction(Product $product)
    {
        $auction = $product->auction();
        if ($auction->whereIn('status', [Auction::STATUS_ACTIVE, Auction::STATUS_PENDING])->exists())
            return null;

        $path = 'site/img/auction';
        if (!is_dir($path)) mkdir($path, 0777, true);
        $img_1 = $img_2 = $img_3 = $img_4 = null;
        if (is_file(public_path($product->img_1))) {
            $name = md5($product->img_1.microtime());
            $img_1 = preg_replace('~^site/img/product/(.*?)\.([a-z]{1,6})$~i',"site/img/auction/{$name}.$2",$product->img_1);
            Storage::disk('local_public')->copy($product->img_1, $img_1);
        }
        if (is_file(public_path($product->img_2))) {
            $name = md5($product->img_2.microtime());
            $img_2 = preg_replace('~^site/img/product/(.*?)\.([a-z]{1,6})$~i',"site/img/auction/{$name}.$2",$product->img_2);
            Storage::disk('local_public')->copy($product->img_2, $img_2);
        }
        if (is_file(public_path($product->img_3))) {
            $name = md5($product->img_3.microtime());
            $img_3 = preg_replace('~^site/img/product/(.*?)\.([a-z]{1,6})$~i',"site/img/auction/{$name}.$2",$product->img_3);
            Storage::disk('local_public')->copy($product->img_3, $img_3);
        }
        if (is_file(public_path($product->img_4))) {
            $name = md5($product->img_4.microtime());
            $img_4 = preg_replace('~^site/img/product/(.*?)\.([a-z]{1,6})$~i',"site/img/auction/{$name}.$2",$product->img_4);
            Storage::disk('local_public')->copy($product->img_4, $img_4);
        }
        $data = [
            'title' => $product->title,
            'short_desc' => $product->short_desc,
            'desc' => $product->desc,
            'specify' => $product->specify,
            'terms' => $product->terms,
            'img_1' => $img_1,
            'img_2' => $img_2,
            'img_3' => $img_3,
            'img_4' => $img_4,
            'alt_1' => $product->alt_1,
            'alt_2' => $product->alt_2,
            'alt_3' => $product->alt_3,
            'alt_4' => $product->alt_4,
            'start_price' => $product->start_price,
            'full_price' => $product->full_price,
            'bot_shutdown_price' => $product->bot_shutdown_price,
            'bid_seconds' => $product->step_time,
            'step_price' => $product->step_price,
            'start' => Carbon::now()->addMinutes((int)$product->to_start),
            'exchange' => (bool)$product->exchange,
            'status' => ((int)$product->to_start === 0) ? Auction::STATUS_ACTIVE : Auction::STATUS_PENDING,
        ];
        $auction->create($data);
        Page::query()->where('slug', $auction->first()->id)->firstOrCreate([
            'slug' => $auction->first()->id,
            'title' => $auction->first()->title,
        ]);
        return $auction;
    }
}
