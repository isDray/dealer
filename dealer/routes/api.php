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

Route::post('/try', 'ApiController@index');

// 改狀態為已出貨
Route::post('/toShipped', 'ApiController@toShipped');

// 進貨單修改
Route::post('/toUpdate', 'ApiController@toUpdate');

