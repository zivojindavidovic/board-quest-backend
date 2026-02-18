<?php

namespace App\Http\Controllers;

use App\Http\Requests\ListGamesRequest;
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
        $attributes = $request->validated();
        $result = $listGamesService->execute($attributes);

        return new ListGamesCollection($result);
    }

    /**
     * List game
     */
    public function listByUUID(string $uuid, ListOneGameService $listOneGameService)
    {
        $game = $listOneGameService->execute($uuid);
        return new ListGameResource($game);
    }
}
