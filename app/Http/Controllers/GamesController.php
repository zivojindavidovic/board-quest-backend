<?php

namespace App\Http\Controllers;

use App\Http\Requests\ListGamesRequest;
use App\Http\Resources\ListGamesCollection;
use App\Services\ListGamesService;

final  class GamesController extends Controller
{
    /**
     * Lists available board games
     */
    public function list(ListGamesRequest $request, ListGamesService $listGamesService)
    {
        $attributes = $request->validated();
        $result = $listGamesService->execute($attributes);

        return new ListGamesCollection($result);
    }
}
