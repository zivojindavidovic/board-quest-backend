<?php

use App\Http\Controllers\GamesController;
use Illuminate\Support\Facades\Route;

Route::get('/v1/games', [GamesController::class, 'list']);
Route::get('/v1/games/{uuid}', [GamesController::class, 'listByUUID']);
