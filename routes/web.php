<?php

use App\Http\Controllers\PaynowController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('home');
});

Route::post('/checkout', [PaynowController::class, 'checkout'])->name('checkout');
Route::get('/paynow/return', [PaynowController::class, 'return'])->name('paynow.return');
Route::post('/paynow/result', [PaynowController::class, 'result'])->name('paynow.result');
Route::get('/paynow/download/{reference}', [PaynowController::class, 'download'])
    ->name('paynow.download')
    ->middleware('signed');
