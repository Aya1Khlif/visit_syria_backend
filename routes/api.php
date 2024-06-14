<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\SettingController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Restaurant\RestaurantController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\ReportController;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::controller(AuthController::class)->group(function () {
    Route::post('login', 'login');
    //Route::post('register', 'register');
    Route::post('logout', 'logout')->middleware('auth:api');
    //Route::post('refresh', 'refresh')->middleware('auth:api');

});

Route::middleware(['auth:api','admin'])->controller(SettingController::class)->prefix('users')->group(function(){
    Route::post('store_user', 'store');
    Route::post('update_user/{user}', 'update');
    Route::delete('delete_user/{user}', 'destroy');

});
