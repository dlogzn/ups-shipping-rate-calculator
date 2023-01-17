<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Artisan;

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

Route::get('/storage/link', function () {
    exec(symlink('/home/goodgros/public_html/application/storage/app/public', '/home/goodgros/public_html/storage'));
});

Route::get('/clear/all', function() {
    Artisan::call('config:clear');
    Artisan::call('cache:clear');
    Artisan::call('view:clear');
    Artisan::call('route:clear');
    return response()->json('all cache cleared');
});

Route::group([
    'middleware' => 'allow.front.panel.access'
], __DIR__ . '/front_panel.php');
Route::group([
    'prefix' => '/account/panel',
    'middleware' => 'allow.account.panel.access'
], __DIR__ . '/account_panel.php');
