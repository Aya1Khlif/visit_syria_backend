<?php

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


Route::apiResource('restaurants', RestaurantController::class);


Route::get('/reports', [ReportController::class, 'index']);





// Reviews Routes
Route::get('restaurants/{id}/reviews', [ReviewController::class, 'index']);
Route::post('restaurants/{id}/reviews', [ReviewController::class, 'store']);
Route::get('restaurants/{restaurantId}/reviews/{reviewId}', [ReviewController::class, 'show']);
Route::put('restaurants/{restaurantId}/reviews/{reviewId}', [ReviewController::class, 'update']);
Route::delete('restaurants/{restaurantId}/reviews/{reviewId}', [ReviewController::class, 'destroy']);
