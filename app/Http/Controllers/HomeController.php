<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

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
        $this->middleware('web');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view(self::DIR.'index');
    }
    public function howItWorks(){
        return view(self::DIR.'how_it_works');
    }
    public function feedback(){
        return view(self::DIR.'feedback');
    }
    public function reviews(){
        return view(self::DIR.'reviews');
    }
    public function coupon(){
        return view(self::DIR.'coupon');
    }
    public function termsOfUse(){
        return view(self::DIR.'terms_of_use');
    }
}
