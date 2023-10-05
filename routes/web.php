<?php

use App\Jobs\VerifyLiveAuctionEnd;
use App\Models\Auction;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    event(new  \App\Events\TestSocket('bids'));
    return view('welcome');
});
Route::get('/reports/coverage', function () {
    return \File::get(base_path('reports/Console/Kernel.php.html'));
});

Route::get('logs', [\Rap2hpoutre\LaravelLogViewer\LogViewerController::class, 'index']);

Route::get('/event', function (){
//    \Illuminate\Support\Facades\Event::dispatch(new \App\Events\BidRaised(1));
    $auction =  Auction::find(1);
//    dispatch(new VerifyLiveAuctionEnd($auction));
    $bid_raised = $auction->bidRaised();
    $next_bid = $auction->nextMinBid();
    $quick_bids = $auction->quickBid();
//    broadcast(new  \App\Events\BidRaised($auction->id,$bid_raised,$next_bid,$quick_bids));
    broadcast(new  \App\Events\BidRaised($auction->id,$bid_raised,$next_bid,$quick_bids));
    dd('hi');
})->name('event');
