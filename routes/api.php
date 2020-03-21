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

Route::apiResource('/roles', 'RoleController')->middleware('auth:api');

Route::apiResource('/interactions', 'InteractionController')->middleware('auth:api');

Route::middleware('auth:api')->prefix('access')->group(function () {
    Route::post('/assign-role', 'AccessController@assignRole')->name('assign.role');
    Route::post('/assign-permission', 'AccessController@assignPermission')->name('assign.permission');
});

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
