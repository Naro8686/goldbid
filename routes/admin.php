<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "admin" middleware group. Now create something great!
|
*/


Route::group(['as' => 'admin.'], function () {
    Route::get('/', 'AdminController@dashboard')->name('dashboard');
    Route::resource('auctions', 'AuctionController')->only(['destroy', 'edit','show']);
    Route::match(['GET', 'POST'], '/profile', 'AdminController@adminProfileChange')->name('profile');
    Route::post('products/add-group', 'ProductController@addGroup')->name('products.add_group');
    Route::get('products/{id}/duplicate', 'ProductController@duplicate')->name('products.duplicate');
    Route::resource('products', 'ProductController')->only([
        'index', 'edit', 'update', 'create', 'store', 'destroy'
    ]);

    Route::resource('users', 'UserController')->only([
        'index', 'edit', 'update', 'destroy'
    ]);
    Route::resource('sliders', 'SliderController')->only([
        'create', 'store', 'edit', 'update', 'destroy'
    ]);
    Route::resource('howitworks', 'HowitworkController')->only([
        'create', 'store', 'edit', 'update', 'destroy'
    ]);
    Route::resource('questions', 'QuestionController')->only([
        'create', 'store', 'edit', 'update', 'destroy'
    ]);
    Route::resource('reviews', 'ReviewController')->only([
        'create', 'store', 'edit', 'update', 'destroy'
    ]);
    Route::resource('packages', 'PackageController')->only([
        'create', 'store', 'edit', 'update', 'destroy'
    ]);
    Route::resource('orders', 'OrderController')->only([
        'edit', 'update',
    ]);
    Route::delete('bots/name/{id}/delete', 'BotController@nameDelete')->name('bots.destroy.name');
    Route::resource('bots', 'BotController')->only([
        'index', 'create', 'store', 'edit', 'update', 'destroy'
    ]);
    Route::group(['as' => 'settings.', 'prefix' => 'settings'], function () {
        Route::match(['GET', 'POST'], '/mail', 'AdminController@mailConfig')->name('mail');
        Route::match(['GET', 'POST'], '/site', 'AdminController@siteConfig')->name('site');
        Route::get('/mailing', 'AdminController@mailing')->name('mailing');
    });
    Route::get('/mailings/{mailing}/send', 'MailingController@send')->name('mailings.send');
    Route::resource('mailings', 'MailingController')->only([
        'create', 'store', 'edit', 'update', 'destroy', 'send'
    ]);
    Route::group(['as' => 'pages.', 'prefix' => 'pages', 'namespace' => 'Pages'], function () {
        Route::post('/seo/{id}/update', 'PageController@seoUpdate')->name('seo.update');
        Route::get('/footer', 'PageController@footer')->name('footer');
        Route::post('/footer/linkOnOff', 'PageController@linkOnOff')->name('footer.linkOnOff');
        Route::post('/footer/linkPosition', 'PageController@linkPosition')->name('footer.linkPosition');
        Route::post('/footer/linkFloat', 'PageController@linkFloat')->name('footer.linkFloat');
        Route::post('/footer/crud', 'PageController@footerLinkCrud')->name('footer.crud');
        Route::get('/dynamic-page/image/browse', 'PageController@footerPageBrowseImg')->name('ckeditor.browse');
        Route::post('/dynamic-page/upload/image', 'PageController@footerPageUploadImg')->name('ckeditor.upload');
        Route::get('/home', 'PageController@homePage')->name('home');
        Route::get('/howitworks', 'PageController@howItWorksPage')->name('howitworks');
        Route::get('/reviews', 'PageController@reviewsPage')->name('reviews');
        Route::get('/feedback', 'PageController@feedbackPage')->name('feedback');
        Route::get('/coupon', 'PageController@couponPage')->name('coupon');
        Route::get('/order', 'PageController@orderPage')->name('order');
    });
});





