<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    const DIR = 'site.';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {

    }

    public function index()
    {
        return view(self::DIR . 'index');
    }

    public function howItWorks()
    {
        return view(self::DIR . 'how_it_works');
    }

    public function feedback()
    {
        return view(self::DIR . 'feedback');
    }

    public function reviews()
    {
        return view(self::DIR . 'reviews');
    }

    public function coupon()
    {
        return view(self::DIR . 'coupon');
    }

    public function termsOfUse()
    {
        return view(self::DIR . 'terms_of_use');
    }

    public function personalData()
    {
        return view(self::DIR . 'personal_data');
    }

    public function privacyPolicy()
    {
        return view(self::DIR . 'privacy_policy');
    }

    public function cookieTerms()
    {
        return view(self::DIR . 'cookie');
    }

}
