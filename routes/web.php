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

Route::group(['namespace' => 'App\Http\Controllers'], function()
{   
    /**
     * Home Routes
     */
    Route::get('/', 'HomeController@index')->name('home.index');

    Route::group(['middleware' => ['guest']], function() {
        /**
         * Register Routes
         */
        Route::get('/register', 'RegisterController@show')->name('register.show');
        Route::post('/register', 'RegisterController@register')->name('register.perform');

        /**
         * Login Routes
         */
        Route::get('/login', 'LoginController@show')->name('login.show');
        Route::post('/login', 'LoginController@login')->name('login.perform');


        /**
         * Two Factor Routes
         */
        Route::get('/token', 'TwoFactorController@show')->name('token.show');
        Route::post('/token', 'TwoFactorController@perform')->name('token.perform');
    });

    Route::group(['middleware' => ['auth']], function() {

        /**
         * Profile Routes
         */
        Route::get('/profile', 'ProfileController@index')
            ->name('profile.index');
        Route::post('/profile/two-factor/enable', 'ProfileController@enableTwoFactor')
            ->name('profile.enableTwoFactor');
        Route::post('/profile/two-factor/disable', 'ProfileController@disableTwoFactor')
            ->name('profile.disableTwoFactor');
        Route::get('/profile/two-factor/verification', 'ProfileController@getVerifyTwoFactor')
            ->name('profile.getVerifyTwoFactor');
        Route::post('/profile/two-factor/verification', 'ProfileController@postVerifyTwoFactor')
            ->name('profile.postVerifyTwoFactor');

        /**
         * Logout Routes
         */
        Route::get('/logout', 'LogoutController@perform')->name('logout.perform');
    });
});
