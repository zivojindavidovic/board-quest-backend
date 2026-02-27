<?php

namespace App\Http\Controllers;

use App\Http\Requests\ListGamesRequest;
use App\Http\Requests\ListOneGameRequest;
use App\Http\Resources\ListGameResource;
use App\Http\Resources\ListGamesCollection;
use App\Services\ListGamesService;
use App\Services\ListOneGameService;

final class GamesController extends Controller
{
    /**
     * Lists available board games
     */
    public function list(ListGamesRequest $request, ListGamesService $listGamesService)
    {
        $result = $listGamesService->execute($request->validated());
        return new ListGamesCollection($result);
    }

    /**
     * List game
     */
    public function listByUUID(ListOneGameRequest $request, ListOneGameService $listOneGameService)
    {
        $game = $listOneGameService->execute($request->validated()['uuid']);
        return new ListGameResource($game);
    }
}
