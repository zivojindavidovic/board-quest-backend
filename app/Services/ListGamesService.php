<?php

namespace App\Services;

use App\Repositories\GamesRepository;
use Illuminate\Pagination\LengthAwarePaginator;

final readonly class ListGamesService
{
    public function __construct(private GamesRepository $gamesRepository)
    {
    }

    /**
     * Returns paginated games collection
     */
    public function execute(array $attributes): LengthAwarePaginator
    {
        $attributes = array_merge([
            'per_page' => 10,
            'page' => 1
        ], $attributes);

        return $this->gamesRepository->getMany($attributes);
    }
}
