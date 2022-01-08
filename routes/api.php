<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::group(["prefix" => "v1"], function () {

    /** Cache */
    Route::get('/clear-cache', function () {
        Artisan::call('optimize:clear');
        return "Cache is cleared";
    });

    // authentication
    Route::group(['prefix' => 'auth', 'namespace' => 'App\Http\Controllers\Api\v1\Auth'], function () {

        Route::post('create', 'AuthController@createUser');
        Route::post('login', 'LoginController@loginUser');
    });

});
