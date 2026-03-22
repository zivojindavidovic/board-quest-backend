<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ClubsController;
use App\Http\Controllers\GamesController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {
    Route::post('auth/register', [AuthController::class, 'register']);
    Route::post('auth/login', [AuthController::class, 'login']);
    Route::post('auth/logout', [AuthController::class, 'logout']);

    Route::middleware('auth:api')->group(function () {
        Route::post('clubs', [ClubsController::class, 'register']);
    });

    Route::get('games', [GamesController::class, 'list']);
    Route::get('games/{uuid}', [GamesController::class, 'listByUUID']);
});
