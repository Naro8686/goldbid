<?php

use Illuminate\Support\Facades\Auth;
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

Route::group(['as' => 'site.'], function () {
    Route::get('/', 'HomeController@index')->name('home');
    Route::get('/how-it-works', 'HomeController@howItWorks')->name('how_it_works');
    Route::match(['GET', 'POST'], '/feedback', 'HomeController@feedback')->name('feedback');
    Route::match(['GET', 'POST'], '/reviews', 'HomeController@reviews')->name('reviews');
    Route::get('/coupon', 'HomeController@coupon')->name('coupon');
    Route::get('/cookie-agree', 'HomeController@cookieAgree')->name('cookie_agree');
});

Route::group(['prefix' => '/cabinet', 'middleware' => 'auth', 'as' => 'profile.', 'namespace' => 'User'], function () {
    Route::match(['GET', 'POST'], '/', 'ProfileController@index')->name('index');
    Route::match(['GET', 'POST'], '/personal', 'ProfileController@personalData')->name('personal');
    Route::get('/balance', 'ProfileController@balance')->name('balance');
    Route::get('/auction-history', 'ProfileController@auctionsHistory')->name('auctions_history');
    Route::get('/referral-program', 'ProfileController@referralProgram')->name('referral_program');
    Route::match(['GET', 'POST'], '/code-email-confirm', 'ProfileController@codeEmailConfirm')->name('email_code_confirm');
    Route::post('/{id}/subscribe', 'ProfileController@subscribe')->name('subscribe');
});

Route::group(['as' => 'auction.',], function () {
    Route::get('/{id}/auction', 'AuctionController@auction')->name('index');
    Route::post('/{auction_id}/add-favorite', 'AuctionController@addFavorite')->name('add_favorite')->middleware('auth');
    Route::post('/change-status', 'AuctionController@changeStatus')->name('change_status');
});

Route::group(['prefix' => '/payment', 'middleware' => ['auth', 'banned'], 'as' => 'payment.', 'namespace' => 'Payments'], function () {
    Route::post('/buy-coupon', 'CouponController@buy')->name('coupon.buy');
});

Route::group(['prefix' => '/reset', 'as' => 'reset.', 'namespace' => 'PasswordReset'], function () {
    Route::get('/password', 'ResetController@reset')->name('password.sms');
    Route::post('/check-phone-number', 'ResetController@checkPhone')->name('check.phone');
    Route::get('/password-change', 'ResetController@passwordChange')->name('password.change');
    Route::post('/password-change', 'ResetController@passwordChangeSuccess')->name('password.change.success');
});
Auth::routes();
Route::group(['middleware' => ['auth', 'banned']], function () {
    Route::get('/bet/{id}', 'BetController@bet')->name('bet');
});

Route::get('/{slug?}', 'HomeController@dynamicPage')->where(['slug' => '^(?!admin.*$).*']);
