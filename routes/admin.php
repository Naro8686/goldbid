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
    Route::group(['as' => 'pages.', 'prefix' => 'pages','namespace'=>'Pages'], function () {
        Route::get('/footer', 'PageController@footer')->name('footer');
        Route::post('/footer/linkOnOff', 'PageController@linkOnOff')->name('footer.linkOnOff');
        Route::post('/footer/linkPosition', 'PageController@linkPosition')->name('footer.linkPosition');
        Route::post('/footer/linkFloat', 'PageController@linkFloat')->name('footer.linkFloat');
        Route::post('/footer/crud', 'PageController@footerLinkCrud')->name('footer.crud');
    });
});





