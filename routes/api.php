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
use App\Http\Controllers\CartController;
use App\Http\Controllers\ArticleController;
use App\Http\Controllers\AuctionBidController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\UserAddressController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\InquiryController;
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
//truncate table
Route::get('/truncate-tables', function (){
    \Illuminate\Support\Facades\Artisan::call("db:seed",array('--class'=>\Database\Seeders\TruncateTables::class,'--force' => true));

    echo "Seeder run successfully";
    exit;
});
Route::get('/country-seeder', function (){
    \Illuminate\Support\Facades\Artisan::call("db:seed",array('--class'=>\Database\Seeders\CountrySeeder::class,'--force' => true));

    echo "Country Seeder run successfully";
    exit;
});
Route::get('/update-auction-pack', function (){
    (new App\Models\WineUser)->update(['available_auctions'=>0]);

    echo "update auction pack successfully.";
    exit;
});


//static page
Route::get('/app-variables', [StaticPageController::class, 'getAppVariables']);
Route::get('/app-variables/{key}', [StaticPageController::class, 'showAppVariables']);

// StaticFilter page
Route::get('/pages', [StaticPageController::class, 'index']);
Route::get('/pages/{slug}', [StaticPageController::class, 'show']);


Route::get('/product-attribute/{attribute_name}', [ProductAttributeController::class, 'attribute_list']);
Route::post('/product-attribute/wine-variety', [ProductAttributeController::class, 'storeWineVarietyAttribute']);
Route::post('/product-attribute/wine-tag', [ProductAttributeController::class, 'storeWineTagsAttribute']);


//product
Route::get('/product', [ProductController::class, 'index'])->withoutMiddleware('throttle:api');
Route::get('/product/{slug}', [ProductController::class, 'show']);
Route::get('/product/{slug}/recommended', [ProductController::class, 'recommended']);


//home page recommended product api
Route::get('/products/bestsellers', [ProductController::class, 'getBestSellers']);


// auction pack
Route::get('/auction-packs', [AuctionPackController::class, 'index']);
Route::get('/auction-pack/{slug}', [AuctionPackController::class, 'show']);


//auction
Route::get('/auction', [AuctionController::class, 'index'])->withoutMiddleware('throttle:api');
Route::get('/auction/{auction}', [AuctionController::class, 'show']);
Route::get('/auction/{auction}/product/{product}', [AuctionController::class, 'showSingleAuction']);
Route::get('/product/{product}/reviews', [ReviewController::class, 'getProductReview']);
Route::get('/auction/{auction}/history', [AuctionBidController::class, 'getAuctionHistory']);
Route::get('/auction-attribute', [AuctionController::class, 'attribute_list']);


//home page best seller and new release auction api
Route::get('/auctions/bestsellers', [AuctionController::class, 'getBestSellers']);
Route::get('/auctions/new-release', [AuctionController::class, 'getNewProducts']);

//Article
Route::get('/articles', [ArticleController::class, 'index']);
Route::get('/article/{slug}', [ArticleController::class, 'show']);

Route::get('/reviews', [ReviewController::class, 'index']);
Route::get('/countries', [StaticPageController::class, 'countries']);

Route::post('/inquiry', [InquiryController::class, 'store']);
Route::get('/csrf-token', function (Request $request) {
    return response()->json(['csrfToken' => $request->session()->token()]);
})->middleware('web');
Route::post('/auction-ended/{auction_id}', [AuctionBidController::class, 'endAuction']);

// Protected Routes
Route::middleware(['auth:sanctum','wine_user_id'])->group(function() {

    Route::post('/logout', [WineAuthController::class, 'logout']);
    Route::post('/auction-pack', [AuctionPackController::class, 'store']);

    //user profile api
    Route::get('/user', [WineAuthController::class, 'getUser']);
    Route::post('/user', [WineAuthController::class, 'updateUser']);
    Route::get('/user-address', [UserAddressController::class, 'index']);
    Route::post('/user-address', [UserAddressController::class, 'addAddress']);
    Route::delete('/user-address/{user_address_id}', [UserAddressController::class, 'destroy']);


    // Routes for connect as seller of wine products
    Route::post('/connect-as-seller', [SellerController::class, 'connect']);
    Route::get('/user/product-count', [UserController::class, 'allProductCount']);
    Route::get('/user/wishlist-count', [UserController::class, 'allWishlistCount']);
    Route::get('/user/wishlist', [UserController::class, 'getWishlist']);
    Route::get('/user/sell-auctions', [UserController::class, 'getSellAuctions']);
    Route::get('/user/buy-purchase-history', [UserController::class, 'getBuyPurchaseHistory']);
    Route::get('/user/private-auctions', [UserController::class, 'getPrivateAuctions']);
    Route::get('/user/participate-auctions', [UserController::class, 'getParticipateAuction']);
    Route::post('/user/update-auction-invitation', [UserController::class, 'updateAuctionInvitation']);

    // Order API
    Route::get('/orders', [OrderController::class, 'index']);
    Route::get('/order/{order}', [OrderController::class, 'show']);
    Route::post('/orders', [OrderController::class, 'store']);

    //product
    Route::post('/product', [ProductController::class, 'store']);

    //Review
    Route::post('/wine_user/{wine_user}/reviews', [ReviewController::class, 'storeWineUserReview']);
    Route::post('/product/{product}/reviews', [ReviewController::class, 'storeProductReview']);
    Route::post('/auction/{auction}/reviews', [ReviewController::class, 'storeAuctionReview']);
    Route::post('/auction_package/{auction_package}/reviews', [ReviewController::class, 'storeAuctionPackageReview']);
    Route::delete('/review/{review_id}',[ReviewController::class, 'deleteReview']);
    Route::put('/review/{review_id}',[ReviewController::class, 'updateReview']);


    //wishlist
    Route::post('/wishlist/products', [WishlistController::class, 'store']);
    Route::get('/wishlist/products', [WishlistController::class, 'index']);
    Route::delete('/wishlist/products', [WishlistController::class, 'destroy']);


    // auction api
    Route::post('/auction', [AuctionController::class, 'store']);
    Route::post('/auction/update-invite-member', [AuctionController::class, 'updateInviteMember']);
    Route::put('/auction/{auction}', [AuctionController::class, 'update']);
    Route::delete('/auction/{auction}', [AuctionController::class, 'destory']);
    Route::post('/auction/{auction}/bid', [AuctionBidController::class, 'storeAuctionBid']);
    Route::post('/auction/{auction}/automatic-bid', [AuctionBidController::class, 'automaticBid']);

    Route::post('/auction_package/{auction_package}/cart', [CartController::class, 'storeAuctionPackageCart']);
    Route::get('/cart', [CartController::class, 'getCartItems']);
    Route::put('/cart/{cart_id}', [CartController::class, 'update']);
    Route::delete('/cart/{cart_id}', [CartController::class, 'destroy']);


});
