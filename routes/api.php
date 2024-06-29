<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\LandmarksController;
use App\Http\Controllers\SettingController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Restaurant\RestaurantController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\HotelController;
use App\Http\Controllers\ImagesController;
use App\Http\Controllers\Restaurant\RestaurantReservationsController;

use App\Http\Controllers\Restaurant\ServiceController;
use App\Models\Blog;
use Spatie\Permission\Models\Role;

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
//authentication
Route::middleware(['auth:api', 'admin'])->controller(SettingController::class)->prefix('users')->group(function () {
    Route::post('store_user', 'store');
    Route::post('update_user/{user}', 'update');
    Route::delete('delete_user/{user}', 'destroy');
});


Route::middleware(['auth:api', 'admin'])->prefix('managing-restaurants')->group(function () {

Route::apiResource('services', ServiceController::class);
Route::post('restaurants/{restaurant}/sync-services', [ServiceController::class, 'syncServices']);


});

Route::apiResource('restaurants', RestaurantController::class);

Route::middleware(['auth:api', 'admin'])->prefix('managing-reviews')->group(function () {

    // Reviews Routes//
    Route::get('restaurants/{id}/reviews', [ReviewController::class, 'index']);
  //  Route::post('restaurants/{id}/reviews', [ReviewController::class, 'store'])->middleware(['auth:api']);
    Route::get('restaurants/{restaurantId}/reviews/{reviewId}', [ReviewController::class, 'show']);
   // Route::put('restaurants/{restaurantId}/reviews/{reviewId}', [ReviewController::class, 'update'])->middleware(['auth:api']);
    Route::delete('restaurants/{restaurantId}/reviews/{reviewId}', [ReviewController::class, 'destroy']);

    });

    Route::middleware(['auth:api', 'admin'])->prefix('managing-reports')->group(function () {

        Route::get('/reports', [ReportController::class, 'index']);
    });

    Route::middleware(['auth:api', 'admin'])->prefix('managing-restaurants-reservations')->group(function () {


        Route::apiResource('reservations', RestaurantReservationsController::class);




        });

// //blog
Route::apiResource('blog', BlogController::class);

//->middleware(['auth:api', 'admin']);;

//about syria
Route::apiResource('posts', PostController::class);
//->middleware(['auth:api','admin']);

//landmarks
Route::apiResource('landmarks', LandmarksController::class)->middleware(['auth:api', 'admin']);;

//Hotels
Route::apiResource('hotels', HotelController::class)->middleware(['auth:api', 'admin']);;

//////////////////////////////////////Blogs/////////////////////////////////
Route::controller(BlogController::class)->group(function(){

    Route::post('Blog','store');
    Route::post('Blog/{blog}','update');
    Route::get('show_bloge/{blog}','show');
    Route::get('all_blogs','index');
    Route::delete('delete_blog/{blog}','destroy');



});
////////////////////////////////////Posts//////////////////////////////////////

//Route::middleware(['auth:api', 'admin'])->
Route::controller(PostController::class)->group(function(){

    Route::post('add_Post','store');
    Route::post('Edit_Post/{post}','update');
    Route::get('show_post/{post}','show');
    Route::get('all_posts','index');
    Route::delete('delete_post/{post}','destroy');

});
