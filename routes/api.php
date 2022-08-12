<?php

use App\Http\Controllers\api\v1\AuthController;
use App\Http\Controllers\api\v1\DomainsController;
use App\Http\Controllers\UserController;
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

Route::middleware('auth:api')->group(function () {
    Route::controller(UserController::class)->name('user.')->prefix('user')->group(function () {
        Route::get('/me', 'me')->name('me');
    });

    Route::controller(DomainsController::class)->name('domains.')->prefix('domains')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::post('/upload', 'upload')->name('upload');
        Route::get('/export', 'export')->name('export');
    });
});