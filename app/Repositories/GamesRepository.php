<?php

namespace App\Repositories;

use App\Models\Game;
use Illuminate\Pagination\LengthAwarePaginator;

class GamesRepository
{
    /**
     * Returns paginated games collection
     */
    public function getMany(array $attributes): LengthAwarePaginator
    {
        return Game::query()
            ->when(!empty($attributes['search']), fn($q) => $q->whereFullText(['title', 'description'], $attributes['search']))
            ->paginate(
                perPage: $attributes['per_page'] ?? 10,
                page: $attributes['page'] ?? 1
            );
    }

    /**
     * Returns game by UUID
     */
    public function getOneByUUID(string $uuid): ?Game
    {
        return Game::query()
            ->where(Game::KEY_ID, $uuid)
            ->first();
    }

    /**
     * Returns existing games by board game geek id
     */
    public function getManyByBGGId(array $bggIds): array
    {
        return Game::query()
            ->whereIn(Game::KEY_BGG_ID, $bggIds)
            ->pluck(Game::KEY_BGG_ID)
            ->toArray();
    }

    /**
     * Inserts many games
     */
    public function insert(array $games): bool
    {
        return Game::query()->insert($games);
    }
}
