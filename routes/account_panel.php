<?php
use Illuminate\Support\Facades\Route;
use \App\Http\Controllers\AccountPanel\ShippingRateController;
use \App\Http\Controllers\FrontPanel\AuthController;

Route::group(['prefix' => '/shipping-rate'], function() {
    Route::get('/', [ShippingRateController::class, 'index']);
    Route::post('/calculate/price', [ShippingRateController::class, 'calculatePrice']);
});

Route::get('/logout', [AuthController::class, 'logout']);
