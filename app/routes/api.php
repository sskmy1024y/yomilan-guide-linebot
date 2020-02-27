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

Route::group(['middleware' => ['api']], function () {
    Route::group(['namespace' => 'Api\Internal', 'prefix' => 'internal'], function () {
        Route::resource('facility', 'FacilitiesController', ['except' => ['create', 'edit']]);
        Route::resource('route', 'RouteController');
    });

    Route::group(['namespace' => 'Api', 'prefix' => 'linebot'], function () {
        Route::post('callback', 'LinebotController@callback');

        Route::post('postback', 'LinebotController@postback');
    });
});

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });
