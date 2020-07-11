<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group(['prefix' => 'v1'], function () {

    // Route Login
    Route::namespace('API')
        ->name('api.user.')
        ->prefix('auth')
        ->group(function () {

            // Login and Register
            Route::post('login', 'AuthController@login')->name('login');
            Route::post('signup', 'AuthController@signup')->name('signup');

            // Get data Login, Update, Logout
            Route::middleware(['auth:api'])
                ->group(function () {
                    Route::get('logout', 'AuthController@logout')->name('logout');
                    Route::get('user', 'AuthController@user')->name('index');
                });
        });
});
