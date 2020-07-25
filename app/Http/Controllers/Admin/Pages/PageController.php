<?php

namespace App\Http\Controllers\Admin\Pages;

use App\Models\Auction\Step;
use App\Models\Pages\Footer;
use App\Models\Pages\Howitwork;
use App\Http\Controllers\Controller;
use App\Http\Requests\PostRequest;
use App\Models\Pages\Package;
use App\Models\Pages\Page;
use App\Models\Pages\Question;
use App\Models\Pages\Review;
use App\Settings\ImageTrait;
use App\Settings\Setting;
use App\Models\Pages\Slider;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;


class PageController extends Controller
{
    use ImageTrait;
    public const DIR = 'admin.pages.';
    public const INSERT = 'insert';
    public const UPDATE = 'update';
    public const DELETE = 'delete';

    public function seoUpdate(Request $request, $id)
    {
        $page = Page::query()->findOrFail($id);
        $page->update($request->only(["title", "keywords", "description"]));
        return redirect()->back()->with('status', 'успешные дествия !');
    }

    /**
     * @return \Illuminate\Support\Collection
     * @var \Illuminate\Support\Collection
     */
    private function footerData()
    {
        $social = Footer::query()
            ->where('social', true)
            ->orderBy('position')
            ->get();
        $left = Footer::query()
            ->where('social', false)
            ->where('float', 'left')
            ->with('page')
            ->orderBy('position')
            ->get();
        $right = Footer::query()
            ->where('social', false)
            ->where('float', 'right')
            ->with('page')
            ->orderBy('position')
            ->get();

        return collect(['left' => $left, 'right' => $right, 'social' => $social]);
    }

    public function footer()
    {
        $footers = $this->footerData();
        return view('admin.pages.footer', compact('footers'));
    }

    public function linkOnOff(Request $request)
    {
        $id = $request['id'];
        $show = (bool)($request['show'] === 'true');
        $success = Footer::query()->findOrFail($id)->update(['show' => $show]);
        return response(['success' => $success]);
    }

    public function linkPosition(Request $request)
    {
        $old = Footer::query()->findOrFail($request['id']);
        $new = Footer::query()
            ->where('position', $request['position'])
            ->where('social', $old->social)
            ->where('float', $old->float)
            ->firstOrFail();
        $before = $old->position;
        $after = $new->position;
        $success = $old->update(['position' => $after]) && $new->update(['position' => $before]);

        $footers = $this->footerData();
        try {
            $html = view('admin.includes.pages.dynamic_link', compact('footers'))->render();
        } catch (\Throwable $e) {
            $html = "<div class='alert alert-danger'>Error</div>";
            $success = false;
        }
        return response(['success' => $success, 'html' => $html]);
    }

    public function linkFloat(Request $request)
    {
        $request->validate([
            'float' => ['required', 'regex:~^(left|right)$~'],
        ]);
        $position = Footer::query()->where(['float' => $request['float']])->count();
        $success = Footer::query()->findOrFail($request['id'])->update([
            'float' => $request['float'],
            'position' => ++$position,
        ]);
        $all = Footer::query()->where('social', false)->orderBy('position')->get();
        $lefts = $all->where('float', 'left');
        $rights = $all->where('float', 'right');
        $iter = 0;
        foreach ($lefts as $left) {
            $left->update(['position' => ++$iter]);
        }
        $iter = 0;
        foreach ($rights as $right) {
            $right->update(['position' => ++$iter]);
        }
        $footers = $this->footerData();
        try {
            $html = view('admin.includes.pages.dynamic_link', compact('footers'))->render();
        } catch (\Throwable $e) {
            $html = "<div class='alert alert-danger'>Error</div>";
            $success = false;
        }
        return response(['success' => $success, 'html' => $html]);
    }

    public function footerLinkCrud(PostRequest $request)
    {
        $success = true;
        $text = '';
        $request['social'] = $request['social'] === 'true';
        $request['link'] = $request['social']
            ? $request['link']
            : ltrim(preg_replace('~^(https?:\/\/)?([\da-z\.-]+)\.([a-z\.]{2,6})([\/\w \.-]*)$~', '$4', $request['link']), '/');

        if ($request['type'] === self::DELETE) {
            list($success, $text) = $this->deleteFooterLink($request['id']);
        } else {
            if ($request['type'] === self::INSERT) {
                if ($request['social']) $request->validate(['image' => ['required']]);
                $request->validate(['link' => ['required', 'unique:footers']]);
                $this->insertFooterLink($request);
            } elseif ($request['type'] === self::UPDATE) {
                $request->validate(['link' => [Rule::unique('footers', 'link')->ignore($request['id'])]]);
                $this->updateFooterLink($request);
            }

        }
        try {
            $footers = $this->footerData();
            $html = view('admin.includes.pages.dynamic_link', compact('footers'))->render();
        } catch (\Throwable $e) {
            $html = "<div class='alert alert-danger'>{$text}</div>";
            $success = false;
        }
        return response(['success' => $success, 'html' => $html]);
    }

    /**
     * @param int $id
     * @return array
     */
    private function deleteFooterLink(int $id): array
    {
        try {
            $position = 1;
            $success = true;
            $text = '';
            $footer = Footer::query()->findOrFail($id);
            if ($page = $footer->page) {
                $page->delete();
            } else {
                if (file_exists(public_path($footer->icon))) unlink(public_path($footer->icon));
                $footer->delete();
            }
            $result = Footer::query()
                ->where('social', $footer->social)
                ->where('float', $footer->float)
                ->orderBy('position')
                ->get();
            foreach ($result as $item) $item->update(['position' => $position++]);
        } catch (\Exception $e) {
            $success = false;
            $text = $e->getMessage();
        }
        return [$success, $text];
    }

    private function insertFooterLink(PostRequest $request)
    {
        unset($request['id']);
        $request['float'] = null;
        if (!$request['social']) {
            $request['float'] = 'right';
            $request['slug'] = Str::slug($request['link']);
        }

        $request['position'] = Footer::query()
                ->where('social', $request['social'])
                ->where('float', $request['float'])
                ->count() + 1;
        if ($image = $request->file('image')) {
            $request['icon'] = $this->icon($image);
        }

        if (!$request['social']) {
            $page = Page::with('footer')->create($request->all());
            $page->footer()->create($request->all());
        } else {
            Footer::create($request->all());
        }

    }

    private function updateFooterLink(PostRequest $request)
    {
        $footer = Footer::query()->findOrFail($request['id']);
        if ($image = $request->file('image')) {
            $request['icon'] = $this->icon($image);
        }
        $footer->update($request->all());
        if ($footer->page) $footer->page->update($request->all());
    }

    public function footerPageUploadImg(Request $request)
    {
        $image = $request->file('upload');
        $upload = $this->postUploadImage($image);
        return response()->json($upload);
    }

    public function homePage()
    {
        $sliders = Slider::all();
        $meta = (new Setting('/'))->mete();
        return view(self::DIR . 'home', compact('sliders', 'meta'));

    }

    public function howItWorksPage()
    {
        $meta = (new Setting('how-it-works'))->mete();
        $steps = Howitwork::all();
        $questions = Question::all();
        return view(self::DIR . 'howitworks', compact('meta', 'steps', 'questions'));
    }

    public function reviewsPage()
    {
        $meta = (new Setting('reviews'))->mete();
        $reviews = Review::all();
        return view(self::DIR . 'reviews', compact('meta', 'reviews'));
    }

    public function feedbackPage()
    {
        $meta = (new Setting('feedback'))->mete();
        return view(self::DIR . 'feedback', compact('meta'));
    }

    public function couponPage()
    {
        $meta = (new Setting('coupon'))->mete();
        $packages = Package::all();
        return view(self::DIR . 'coupon', compact('meta','packages'));
    }
    public function orderPage(){
        $meta = (new Setting('order'))->mete();
        $steps = Step::all()->groupBy('step');
        return view(self::DIR . 'order', compact('meta','steps'));
    }
}
