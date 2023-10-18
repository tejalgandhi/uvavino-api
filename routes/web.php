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
    $auction =  Auction::find(2);
    dispatch(new VerifyLiveAuctionEnd($auction));
    dd('hi');
})->name('event');
