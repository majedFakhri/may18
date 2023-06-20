<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\categories\CategoryController;
use App\Http\Controllers\products\ProductController;
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
;
Route::post('/login', [AuthController::class, 'login'])->name('login');


Route::group([

    //'prefix' => 'products',
    //  'middleware' => ['auth:sanctum','throttle:60,1']
    'middleware' => ['auth:sanctum']
], function () {
    Route::match(['put', 'patch'], '/update-user/{id}', [UserController::class, 'updateRoles']);
    Route::group([
        'middleware' => 'isadmin'
    ], function () {
        ////category operation
        Route::post('/add-category', [CategoryController::class, 'store']);

        ////product operation
        Route::post('/add-product', [ProductController::class, 'store']);
        Route::match(['put', 'patch'], '/update-product/{id}', [ProductController::class, 'update']);
        Route::delete('/delete-product/{id}', [ProductController::class, 'destroy']);

         ////users operation
        Route::get('/u', [UserController::class, 'getUsersByRole']);
    });

    ////category operation
    Route::get('/all-category', [CategoryController::class, 'index']);
    Route::get('allCategoryWithProducts', [CategoryController::class, 'getAllCategoreisWithProducts']);


    ////product operation
    Route::get('/all-products', [ProductController::class, 'index']);
    //There is something wrong right here....i wish you discover it
    Route::get('/productByCategory/{letter}', [ProductController::class, 'filterProductsByCategory']);
    Route::get('/product/{id}', [ProductController::class, 'show']);

    ////users operation
    Route::get('/all-users', [UserController::class, 'index']);

});