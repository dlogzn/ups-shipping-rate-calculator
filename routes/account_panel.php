<?php
use Illuminate\Support\Facades\Route;
use \App\Http\Controllers\AccountPanel\ShippingRateController;

Route::group(['prefix' => '/shipping-rate'], function() {
    Route::get('/', [ShippingRateController::class, 'index']);
    Route::post('/calculate/price', [ShippingRateController::class, 'calculatePrice']);
});
