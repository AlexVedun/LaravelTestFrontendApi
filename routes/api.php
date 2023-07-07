<?php

use App\Http\Controllers\LoginController;
use App\Http\Controllers\WeatherRecordsController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/
Route::post('login', [LoginController::class, 'login']);
Route::post('register', [LoginController::class, 'register']);

Route::middleware('auth:sanctum')->group(function() {
    Route::post('logout', [LoginController::class, 'logout']);

    Route::group(['prefix' => 'weather-records'], function() {
        Route::get('all', [WeatherRecordsController::class, 'getAll']);
        Route::get('get', [WeatherRecordsController::class, 'get']);
        Route::post('update', [WeatherRecordsController::class, 'update']);
        Route::post('delete', [WeatherRecordsController::class, 'delete']);
        Route::get('get-by-date', [WeatherRecordsController::class, 'getAllByDate']);
    });
});
