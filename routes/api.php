<?php

use App\Http\Controllers\api\v1\AuthController;
use App\Http\Controllers\api\v1\DomainsController;
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

Route::controller(AuthController::class)->name('auth.')->prefix('auth')->group(function () {
    Route::post('/register', 'store')->name('register');
    Route::post('/login', 'login')->name('login');
});

Route::controller(DomainsController::class)->name('domains.')->prefix('domains')->group(function () {
    Route::post('/upload', 'upload')->name('upload');
});
