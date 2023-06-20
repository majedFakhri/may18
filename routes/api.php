<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\categories\CategoryController;
use App\Http\Controllers\orders\OrderController;
use App\Http\Controllers\products\ProductController;
use App\Http\Controllers\reviews\ReviewController;
use App\Http\Controllers\TestController;
use App\Http\Controllers\users\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout']);
Route::post('/login', [AuthController::class, 'login'])->name('login');


Route::group([
    'middleware' => ['auth:sanctum']
], function () {
    Route::group([
        'middleware' => 'isadmin'
    ], function () {
        ////order operation
        Route::get('/all-order', [OrderController::class, 'index']);

        
        ////review operation
        Route::get('/all-review', [ReviewController::class, 'index']);


        ////category operation
        Route::post('/add-category', [CategoryController::class, 'store']);
        Route::delete('/delete-category/{id}', [CategoryController::class, 'destroy']);
        Route::match(['put', 'patch'], '/update-category/{id}', [CategoryController::class, 'update']);


        ////product operation
        Route::post('/add-product', [ProductController::class, 'store']);
        Route::match(['put', 'patch'], '/update-product/{id}', [ProductController::class, 'update']);
        Route::delete('/delete-product/{id}', [ProductController::class, 'destroy']);

        ////users operation
        Route::get('/u', [UserController::class, 'getUsersByRole']);
        Route::get('/all-users', [UserController::class, 'index']);
        Route::match(['put', 'patch'], '/update-user/{id}', [UserController::class, 'updateRoles']);
    });
    ////order operation
    Route::post('/add-order', [OrderController::class, 'store']);
    Route::get('/order/{id}', [OrderController::class, 'show']);
    Route::match(['put', 'patch'], '/update-order/{id}', [OrderController::class, 'update']);
    Route::delete('/delete-order/{id}', [OrderController::class, 'destroy']);


    ////review operation
    Route::get('/review/{id}', [ReviewController::class, 'show']);
    Route::post('/add-review', [ReviewController::class, 'store']);
    Route::match(['put', 'patch'], '/update-review/{id}', [ReviewController::class, 'update']);
    Route::delete('/delete-review/{id}', [ReviewController::class, 'destroy']);


    ////category operation
    Route::get('/all-category', [CategoryController::class, 'index']);
    Route::get('/category/{id}', [CategoryController::class, 'show']);


    ////product operation
    Route::get('/all-products', [ProductController::class, 'index']);
    Route::get('/productByCategory/{letter}', [ProductController::class, 'filterProductsByCategory']);
    Route::get('/product/{id}', [ProductController::class, 'show']);




});