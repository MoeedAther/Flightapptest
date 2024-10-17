<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\GetPriceController;
use App\Http\Controllers\AccessTokenController;
use App\Http\Controllers\FlightSearchController;
use App\Http\Controllers\VideoController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/add', function () {
    return view('video');
});

Route::post('add', [VideoController::class, 'upload']);
Route::get('get-videos', [VideoController::class, 'getVideos']);
Route::get('stream-video', [VideoController::class, 'scheduleDelivery']);
Route::get('/facebook/stream-key-and-url', [VideoController::class, 'getStreamKeyAndUrl']);
Route::get('facebook/callback', [VideoController::class, 'getStreamKeyUrl']);

Route::get('/init', AccessTokenController::class);
Route::get('/search', FlightSearchController::class);
Route::get('/price', GetPriceController::class);
