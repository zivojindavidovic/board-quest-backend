<?php

namespace App\Services;

use App\Models\Game;
use App\Repositories\GamesRepository;

final readonly class ListOneGameService
{
    public function __construct(private GamesRepository $gamesRepository)
    {
    }

    /**
     * Returns paginated games collection
     */
    public function execute(string $uuid): Game
    {
        return $this->gamesRepository->getOneByUUID($uuid);
    }
}
