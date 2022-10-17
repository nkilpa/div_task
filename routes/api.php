<?php

use App\Http\Controllers\RequestsController;
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

Route::prefix('requests')->group(function () {
        Route::get('/index', [RequestsController::class, 'index']);
        Route::get('/show/{id}', [RequestsController::class, 'show']);
        Route::post('/create', [RequestsController::class, 'create']);
        Route::put('/update/{id}', [RequestsController::class, 'update']);
        Route::delete('/delete/{id}', [RequestsController::class, 'delete']);
    });
