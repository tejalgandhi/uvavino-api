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
use App\Http\Controllers\SettingController;
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

//static page
Route::get('/app-variables', [StaticPageController::class, 'getAppVariables']);
Route::get('/app-variables/{key}', [StaticPageController::class, 'showAppVariables']);

// Static page
Route::get('/pages', [StaticPageController::class, 'index']);
Route::get('/pages/{slug}', [StaticPageController::class, 'show']);


Route::get('/product-attribute/{attribute_name}', [ProductAttributeController::class, 'attribute_list']);
Route::post('/product-attribute/wine-variety', [ProductAttributeController::class, 'storeWineVarietyAttribute']);
Route::post('/product-attribute/wine-tag', [ProductAttributeController::class, 'storeWineTagsAttribute']);


//product
Route::get('/product', [ProductController::class, 'index'])->withoutMiddleware('throttle:api');
Route::get('/product/{slug}', [ProductController::class, 'show']);
Route::get('/product/{slug}/recommended', [ProductController::class, 'recommended']);

// auction pack
Route::get('/auction-packs', [AuctionPackController::class, 'index']);
Route::get('/auction-pack/{slug}', [AuctionPackController::class, 'show']);


//auction
Route::get('/auction', [AuctionController::class, 'index']);
Route::get('/auction/{auction}', [AuctionController::class, 'show']);
Route::get('/auction/{auction}/product/{product}', [AuctionController::class, 'showSingleAuction']);
Route::get('/product/{product}/reviews', [ReviewController::class, 'getProductReview']);
Route::get('/auction-attribute', [AuctionController::class, 'attribute_list']);


//home page best seller and new release auction api
Route::get('/auctions/bestsellers', [AuctionController::class, 'getBestSellers']);
Route::get('/auctions/new-release', [AuctionController::class, 'getNewProducts']);

//home page recommended product api
Route::get('/products/bestsellers', [ProductController::class, 'getBestSellers']);

//Article
Route::get('/articles', [ArticleController::class, 'index']);
Route::get('/article/{slug}', [ArticleController::class, 'show']);

Route::get('/reviews', [ReviewController::class, 'index']);
Route::get('/csrf-token', function (Request $request) {
    return response()->json(['csrfToken' => $request->session()->token()]);
})->middleware('web');
Route::get('/auction', [AuctionController::class, 'index'])->withoutMiddleware('throttle:api');

// Protected Routes
Route::middleware(['auth:sanctum','wine_user_id'])->group(function() {

    Route::post('/logout', [WineAuthController::class, 'logout']);
    Route::post('/auction-pack', [AuctionPackController::class, 'store']);

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
    Route::post('/wine_user/{wine_user}/reviews', [ReviewController::class, 'storeWineUserReview']);
    Route::post('/product/{product}/reviews', [ReviewController::class, 'storeProductReview']);
    Route::post('/auction/{auction}/reviews', [ReviewController::class, 'storeAuctionReview']);
    Route::post('/auction_package/{auction_package}/reviews', [ReviewController::class, 'storeAuctionPackageReview']);



    //wishlist
    Route::post('/wishlist/products', [WishlistController::class, 'store']);
    Route::get('/wishlist/products', [WishlistController::class, 'index']);
    Route::delete('/wishlist/products', [WishlistController::class, 'destroy']);


    // auction api

    Route::post('/auction', [AuctionController::class, 'store']);
    Route::delete('/auction/{auction}', [AuctionController::class, 'destory']);
    Route::post('/auction/{auction}/bid', [AuctionController::class, 'bid']);
    Route::post('/auction/{auction}/automatic-bid', [AuctionController::class, 'automaticBid']);
});
