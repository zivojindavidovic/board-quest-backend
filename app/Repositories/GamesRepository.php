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
            ->paginate(
                perPage: $attributes['per_page'] ?? 10,
                page: $attributes['page'] ?? 1
            );
    }
}
