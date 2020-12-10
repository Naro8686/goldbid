<?php

namespace App\Http\Controllers;

use App\Mail\FeedbackSendMail;
use App\Models\Auction\Auction;
use App\Models\Pages\Howitwork;
use App\Mail\ReviewSendMail;
use App\Models\Pages\Package;
use App\Models\Pages\Page;
use App\Models\Pages\Question;
use App\Models\Pages\Review;
use App\Settings\ImageTrait;
use App\Settings\Setting;
use App\Models\Pages\Slider;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;


class HomeController extends Controller
{
    use ImageTrait;

    const DIR = 'site.';
    public $page = null;

    /**
     * Create a new controller instance.
     *
     * @param Request $request
     */
    public function __construct(Request $request)
    {
        $this->page = (new Setting($request->segment(1)))->page();
        view()->share('page', $this->page);
    }

    public function index(Request $request)
    {
        $sliders = Slider::all();
        if ($request->ajax()) {
            $html = $error = null;
            try {
                $auctions = Auction::auctionsForHomePage();
                $html = view('site.include.auctions', ['auctions' => $auctions])->render();
            } catch (\Throwable $e) {
                $error = $e->getMessage();
            }
            return response()->json(['html' => $html, 'error' => $error]);
        }
        return view(self::DIR . 'index', compact('sliders'));
    }

    public function howItWorks()
    {
        $steps = Howitwork::all();
        $questions = Question::all();
        return view(self::DIR . 'how_it_works', compact('steps', 'questions'));
    }

    public function feedback(Request $request)
    {
        if ($request->isMethod('POST')) {
            $request['upload'] = null;
            $request->validate([
                'name' => ['required', 'string', 'max:100'],
                'email' => ['required', 'email', 'max:150'],
                'theme' => ['required', 'integer'],
                'message' => ['required', 'string', 'max:250'],
                'file' => ['sometimes', 'mimes:jpeg,jpg,png,gif,svg,doc,docx,pdf', 'max:2048'],
                'g-recaptcha-response' => ['required', 'recaptcha'],
                'personal_data' => ['required'],
            ]);
            try {
                $request['theme'] = Setting::feedbackTheme($request['theme']);
                if ($request->hasFile('file')) {
                    $request['upload'] = $request['file']->getError() === 0 ? [
                        'path' => public_path($this->uploadImage($request['file'], 'site/img/tmp/feedback', 0, 0, false)),
                        'as' => $request['file']->getClientOriginalName(),
                        'mime' => $request['file']->getClientMimeType(),
                    ] : null;
                }
                Mail::to(config('mail.from.address'))->later(5, new FeedbackSendMail($request->only(['name', 'email', 'theme', 'message', 'upload'])));
            } catch (Exception $exception) {
                Log::error($exception->getMessage());
            }
            return redirect()->back();
        }
        $themes = Setting::feedbackTheme(null);
        $contact = Setting::siteContacts();

        return view(self::DIR . 'feedback', compact('themes', 'contact'));
    }

    public function reviews(Request $request)
    {
        if ($request->isMethod('POST')) {
            $request['upload'] = null;
            $request->validate([
                'name' => ['required', 'string', 'max:100'],
                'email' => ['required', 'email', 'max:150'],
                'message' => ['required', 'string', 'max:250'],
                'file' => ['sometimes', 'image', 'mimes:jpeg,jpg,png,gif,svg', 'max:2048'],
                'g-recaptcha-response' => ['required', 'recaptcha'],
                'personal_data' => ['required'],
            ]);
            try {
                if ($request->hasFile('file')) {
                    $request['upload'] = $request['file']->getError() === 0 ? [
                        'path' => public_path($this->uploadImage($request['file'], 'site/img/tmp/review', 0, 0, false)),
                        'as' => $request['file']->getClientOriginalName(),
                        'mime' => $request['file']->getClientMimeType(),
                    ] : null;
                }
                Mail::to(config('mail.from.address'))->later(5, new ReviewSendMail($request->only(['name', 'email', 'message', 'upload'])));
            } catch (Exception $exception) {
                Log::error('reviews send file ' . $exception->getMessage());
            }
            return redirect()->back();
        }
        $reviews = Review::all();
        return view(self::DIR . 'reviews', compact('reviews'));
    }

    public function coupon()
    {
        $payments = Setting::paymentCoupon(null);
        $packages = Package::where('visibly', true)->get();
        return view(self::DIR . 'coupon', compact('packages', 'payments'));
    }


    public function dynamicPage($link = null)
    {
        $dynamicPage = Page::whereHas('footer', function ($query) use ($link) {
            return $query->where('link', '=', $link);
        })->firstOrFail();
        $setting = (new Setting($dynamicPage->slug));
        $page = $setting->content();
        return view(self::DIR . 'dynamic_page', compact('page'));
    }

    public function cookieAgree(Request $request)
    {
        $time = ($agree = (bool)$request['agree']) ? now("Europe/Moscow")->addMonth() : now()->addDay();
        $cookie = cookie('cookiesPolicy', $time, $time->diffInMinutes());
        return response(['agree' => $agree])->cookie($cookie);
    }

}
