<?php

namespace App\Repositories;

use App\Models\Club;

final readonly class ClubsGamesRepository
{
    /*
     * Syncs available games in club
     */
    public function sync(Club $club, array $gameIds): array
    {
        return $club->games()->sync($gameIds);
    }
}
