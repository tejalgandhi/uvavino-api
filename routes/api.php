<?php

use App\Http\Controllers\WineAuthController;
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
});


// Protected Routes
Route::middleware('auth:sanctum')->group(function() {
    Route::get('/user', function() { return request()->user(); });
});