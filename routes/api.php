<?php

use App\Http\Controllers\AuctionController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\AuctionPackController;
use App\Http\Controllers\StaticPageController;
use App\Http\Controllers\WineAuthController;
use App\Http\Controllers\ProductAttributeController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\SellerController;
use App\Http\Controllers\WishlistController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\ArticleController;
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
    Route::post('/forget-password', [WineAuthController::class, 'forget']);
    Route::post('/reset-password', [WineAuthController::class, 'reset']);
});

Route::get('/product-attribute/{attribute_name}', [ProductAttributeController::class, 'attribute_list']);

//product
Route::get('/product', [ProductController::class, 'index']);
Route::get('/product/{slug}', [ProductController::class, 'show']);
Route::get('/product/{slug}/recommended', [ProductController::class, 'recommended']);

// auction pack
Route::get('/auction-packs', [AuctionPackController::class, 'index']);
Route::get('/auction-pack/{slug}', [AuctionPackController::class, 'show']);
Route::post('/auction-pack', [AuctionPackController::class, 'store']);


//auction
Route::get('/auction', [AuctionController::class, 'index']);

//Article
Route::get('/articles', [ArticleController::class, 'index']);
Route::get('/article/{slug}', [ArticleController::class, 'show']);

// Static page
Route::get('/pages', [StaticPageController::class, 'index']);
Route::get('/pages/{slug}', [StaticPageController::class, 'show']);

Route::get('/reviews', [ReviewController::class, 'index']);
Route::get('/csrf-token', function (Request $request) {
    return response()->json(['csrfToken' => $request->session()->token()]);
})->middleware('web');

// Protected Routes
Route::middleware(['auth:sanctum','wine_user_id'])->group(function() {
    Route::post('/logout', [WineAuthController::class, 'logout']);

    Route::get('/user', [WineAuthController::class, 'getUser']);

    // Routes for connect as seller of wine products
    Route::post('/connect-as-seller', [SellerController::class, 'connect']);

    // Order API
    Route::get('/orders', [OrderController::class, 'index']);
    Route::get('/orders/{order}', [OrderController::class, 'show']);
    Route::post('/orders', [OrderController::class, 'store']);

    //product
    Route::post('/product', [ProductController::class, 'store']);

    //Review
    Route::post('/reviews', [ReviewController::class, 'store']);


    //wishlist
    Route::post('/wishlist/products', [WishlistController::class, 'store']);
    Route::get('/wishlist/products', [WishlistController::class, 'index']);
    Route::delete('/wishlist/products', [WishlistController::class, 'destroy']);


    // auction api
    Route::post('/auction', [AuctionController::class, 'store']);
    Route::get('/auction', [AuctionController::class, 'index']);
    Route::get('/auction/{auction}', [AuctionController::class, 'show']);
    Route::post('/auction/{auction}/bid', [AuctionController::class, 'bid']);
    Route::post('/auction/{auction}/automatic-bid', [AuctionController::class, 'automaticBid']);
});
