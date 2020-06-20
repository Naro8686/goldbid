<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


Route::group(['as' => 'site.'], function ()
{
    Route::get('/', 'HomeController@index')->name('home');
    Route::get('/how-it-works', 'HomeController@howItWorks')->name('how_it_works');
    Route::get('/feedback', 'HomeController@feedback')->name('feedback');
    Route::get('/reviews', 'HomeController@reviews')->name('reviews');
    Route::get('/coupon', 'HomeController@coupon')->name('coupon');
    Route::get('/cookie-agree', 'HomeController@cookieAgree')->name('cookie_agree');

    //Route::group(['middleware' => 'auth'], function (){});
});
Auth::routes();
Route::get('/{slug?}', 'HomeController@dynamicPage')->where(['slug' => '^(?!admin.*$).*']);

