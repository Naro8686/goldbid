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
    Route::get('/terms-of-use', 'HomeController@termsOfUse')->name('terms_of_use');
    Route::get('/personal-data', 'HomeController@personalData')->name('personal_data');
    Route::get('/privacy-policy', 'HomeController@privacyPolicy')->name('privacy_policy');
    Route::get('/cookie-terms-of-use', 'HomeController@cookieTerms')->name('cookie_terms');
    //Route::group(['middleware' => 'auth'], function (){});
});
Auth::routes();


