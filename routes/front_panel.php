<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FrontPanel\AuthController;

Route::group(['prefix' => 'login'], function () {
    Route::get('/', [AuthController::class, 'index']);
    Route::post('/authenticate', [AuthController::class, 'authenticate']);
});


