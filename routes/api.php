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

Route::middleware('auth:api')->prefix('roles')->group(function () {
    Route::apiResource('', 'RoleController')->names('roles');
    Route::put('/{role}/assign', 'RoleController@assignInteractions')->name('roles.bulk');
});

Route::middleware('auth:api')->prefix('access')->group(function () {
    Route::post('/assign-role', 'AccessController@assignRole')->name('assign.role');
    Route::post('/assign-permission', 'AccessController@assignPermission')->name('assign.permission');
    Route::post('/assign-test', 'AccessController@testPermission')->name('assign.test');
});

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
