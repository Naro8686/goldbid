<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProductRequest;
use App\Jobs\CreateAuctionJob;
use App\Models\Auction\Auction;
use App\Models\Auction\Category;
use App\Models\Auction\Company;
use App\Models\Auction\Product;
use App\Models\Pages\Page;
use App\Settings\ImageTrait;
use Exception;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Throwable;

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
                })->editColumn('buy_now', function ($product) {
                    $class = '';
                    $active = 'false';
                    $link = route('admin.products.update', $product['id']);
                    if ($product['buy_now']) {
                        $class = 'active';
                        $active = 'true';
                    }
                    return "<button type='button' class='btn btn-sm btn-toggle {$class}'
                                data-toggle='button'
                                aria-pressed='{$active}'
                                onclick='oNoFF(`{$link}`,{buy_now:($(this).attr(`aria-pressed`) === `true` ? 0 : 1)},`PUT`)'>
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
                    $linkDuplicate = route('admin.products.duplicate', $product['id']);
                    return "<div class='btn-group btn-group-sm' role='group' aria-label='Basic example'>
                                        <a href='{$linkDuplicate}' class='btn btn-secondary'>Дубликат</a>
                                        <a href='{$linkEdit}' class='btn btn-info'>изменить</a>
                                        <button type='button' class='btn btn-danger'
                                                data-toggle='modal'
                                                data-target='#resourceModal'
                                                data-action='{$linkDelete}'>
                                                    удалить
                                        </button>
                                    </div>";
                })->rawColumns(['img_1', 'exchange', 'buy_now', 'top', 'visibly', 'action'])->make(true);
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
     * @return Factory|View
     * @throws Throwable
     */
    public function edit(int $id)
    {
        $product = Product::query()->findOrFail($id);
        $companies = Company::all();
        $categories = Category::all();
        return view(self::DIR . 'edit', compact('product', 'companies', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param int $id
     * @return JsonResponse|RedirectResponse
     */
    public function update(Request $request, $id)
    {
        $product = Product::query()->with('auction')->findOrFail($id);
        if ($request->ajax()) {
            $request->validate([
                'exchange' => ['sometimes', 'required', 'boolean'],
                'buy_now' => ['sometimes', 'required', 'boolean'],
                'top' => ['sometimes', 'required', 'boolean'],
                'visibly' => ['sometimes', 'required', 'boolean'],
            ]);
            $product->update($request->only(['exchange', 'buy_now', 'top', 'visibly']));
        } else {
            $request->validate([
                'title' => ['required', 'string', 'max:30'],
                'short_desc' => ['nullable', 'string', 'max:30'],
                'desc' => ['string', 'nullable'],
                'specify' => ['string', 'nullable'],
                'terms' => ['string', 'nullable'],
                'start_price' => ['required', 'numeric', 'min:1'],
                'full_price' => ['required', 'numeric', 'min:1'],
                'bot_shutdown_count' => ['required', function ($attribute, $value, $fail) {
                    list($min, $max) = array_pad(str_replace(' ', '', explode('-', $value)), 2, null);
                    if (is_null($min) || is_null($max) || $min > $max)
                        $fail('заполните поля правильно');
                }],
                'bot_shutdown_price' => ['required', function ($attribute, $value, $fail) {
                    list($min, $max) = array_pad(str_replace(' ', '', explode('-', $value)), 2, null);
                    if (is_null($min) || is_null($max) || $min > $max)
                        $fail('заполните поля правильно');
                }],
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
            ], [
                'bot_shutdown_count.required' => 'Это поле обезательно для заполнения ',
                'bot_shutdown_price.required' => 'Это поле обезательно для заполнения ',
            ]);
            $request->merge([
                'visibly' => $request['visibly'] === 'on',
                'exchange' => $request['exchange'] === 'on',
                'buy_now' => $request['buy_now'] === 'on',
            ]);
            if ($request->file('file_1')) {
                $request['img_1'] = $this->uploadImage($request->file('file_1'), 'site/img/product', $this->width, $this->height);
                if ($request['img_1'] && is_file(public_path($product->img_1))) unlink(public_path($product->img_1));
            }
            if ($request->file('file_2')) {
                $request['img_2'] = $this->uploadImage($request->file('file_2'), 'site/img/product', $this->width, $this->height);
                if ($request['img_2'] && is_file(public_path($product->img_2))) unlink(public_path($product->img_2));
            }
            if ($request->file('file_3')) {
                $request['img_3'] = $this->uploadImage($request->file('file_3'), 'site/img/product', $this->width, $this->height);
                if ($request['img_3'] && is_file(public_path($product->img_3))) unlink(public_path($product->img_3));
            }
            if ($request->file('file_4')) {
                $request['img_4'] = $this->uploadImage($request->file('file_4'), 'site/img/product', $this->width, $this->height);
                if ($request['img_4'] && is_file(public_path($product->img_4))) unlink(public_path($product->img_4));
            }

            $product->update($request->only([
                "title", "short_desc", "company_id",
                "category_id", "start_price", "full_price",
                "bot_shutdown_price", "bot_shutdown_count",
                "step_time", "step_price",
                "to_start", "exchange", "visibly", "buy_now",
                "desc", "specify", "terms",
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
     * @return RedirectResponse
     * @throws Exception
     */
    public function destroy($id)
    {
        $product = Product::query()->findOrFail($id);
        try {
            $images = [
                public_path($product->img_1),
                public_path($product->img_2),
                public_path($product->img_3),
                public_path($product->img_4),
            ];
            foreach ($images as $image) if (is_file($image)) @unlink($image);
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
            'buy_now' => $request['buy_now'] === 'on',
        ]);

        if ($request->file('file_1')) $request['img_1'] = $this->uploadImage($request->file('file_1'), 'site/img/product', $this->width, $this->height);
        if ($request->file('file_2')) $request['img_2'] = $this->uploadImage($request->file('file_2'), 'site/img/product', $this->width, $this->height);
        if ($request->file('file_3')) $request['img_3'] = $this->uploadImage($request->file('file_3'), 'site/img/product', $this->width, $this->height);
        if ($request->file('file_4')) $request['img_4'] = $this->uploadImage($request->file('file_4'), 'site/img/product', $this->width, $this->height);

        $product = Product::query()->create($request->only([
            "title", "short_desc", "company_id",
            "category_id", "start_price", "full_price",
            "bot_shutdown_price", "bot_shutdown_count",
            "step_time", "step_price",
            "to_start", "exchange", "visibly", "buy_now",
            "desc", "specify", "terms",
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
        try {
            $auction = $product->auction();
            CreateAuctionJob::dispatchIf($auction
                ->whereIn('status', [Auction::STATUS_ACTIVE, Auction::STATUS_PENDING])
                ->doesntExist(), $product);
        }catch (Throwable $exception){
            Log::error($exception->getMessage());
        }
    }

    public function duplicate($id)
    {
        $old = Product::query()->findOrFail($id);
        $new = $old->replicate();
        $new->img_1 = $this->imageCopy($old->img_1);
        $new->img_2 = $this->imageCopy($old->img_2);
        $new->img_3 = $this->imageCopy($old->img_3);
        $new->img_4 = $this->imageCopy($old->img_4);
        $new->created_at = now();
        $new->updated_at = now();
        $new->save();
        if ($new->visibly === true) {
            /** @var Product $new */
            $this->createAuction($new);
        }
        return redirect()->route('admin.products.index')->with('status', 'успешные дествия !');
    }
}
