<?php

use App\Http\Controllers\StaticPageController;
use App\Http\Controllers\WineAuthController;
use App\Http\Controllers\ProductAttributeController;
use App\Http\Controllers\ProductController;
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

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });


// Authentication Routes
Route::prefix('auth')->group(function() {
    Route::post('/register', [WineAuthController::class, 'register']);
    Route::post('/login', [WineAuthController::class, 'login']);
    Route::post('/logout', [WineAuthController::class, 'logout']);
    Route::post('/forget-password', [WineAuthController::class, 'forget']);
    Route::post('/reset-password', [WineAuthController::class, 'reset']);


});
Route::get('/product-attribute/{attribute_name}', [ProductAttributeController::class, 'attribute_list']);

//product
Route::get('/product', [ProductController::class, 'index']);
Route::get('/product/{product_id}', [ProductController::class, 'show']);

// Static page
Route::get('/pages', [StaticPageController::class, 'index']);
Route::get('/pages/{slug}', [StaticPageController::class, 'show']);

// Protected Routes
Route::middleware('auth:sanctum')->group(function() {
    Route::get('/user', function() { return request()->user(); });

    //product
    Route::post('/product', [ProductController::class, 'store']);
});
