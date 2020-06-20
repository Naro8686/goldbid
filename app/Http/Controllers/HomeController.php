<?php

namespace App\Http\Controllers;

use App\Footer;
use App\Howitwork;
use App\Mail\ReviewSendMail;
use App\Models\User;
use App\Page;
use App\Question;
use App\Review;
use App\Settings\Setting;
use App\Slider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class HomeController extends Controller
{
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
    }

    public function index()
    {
        $sliders = Slider::all();
        $page = $this->page;
        return view(self::DIR . 'index', compact('page', 'sliders'));
    }

    public function howItWorks()
    {
        $page = $this->page;
        $steps = Howitwork::all();
        $questions = Question::all();
        return view(self::DIR . 'how_it_works', compact('page', 'steps', 'questions'));
    }

    public function feedback()
    {
        $page = $this->page;
        return view(self::DIR . 'feedback', compact('page'));
    }

    public function reviews(Request $request)
    {
        if ($request->isMethod('POST')) {
            $request->validate([
                'name' => ['required', 'string', 'max:100'],
                'email' => ['required', 'email', 'max:150'],
                'message' => ['required', 'string', 'max:250'],
                'file' => ['sometimes', 'image', 'mimes:jpeg,jpg,png,gif,svg', 'max:2048'],
                'g-recaptcha-response' => ['required', 'recaptcha']
            ]);
            if (Setting::siteContacts()->email)
                Mail::to(Setting::siteContacts()->email)->send(new ReviewSendMail($request->only(['name', 'email', 'message', 'file'])));
        }
        $page = $this->page;
        $reviews = Review::all();
        return view(self::DIR . 'reviews', compact('page', 'reviews'))->with(['status'=>'sdsd']);
    }

    public function coupon()
    {
        $page = $this->page;
        return view(self::DIR . 'coupon', compact('page'));
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
        $time = ($agree = (bool)$request['agree']) ? now()->addMonth() : now()->addDay();
        $cookie = cookie('cookiesPolicy', $time, $time->diffInMinutes());
        return response(['agree' => $agree])->cookie($cookie);
    }

}
