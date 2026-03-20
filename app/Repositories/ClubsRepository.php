<?php

namespace App\Repositories;

use App\Models\Club;

final readonly class ClubsRepository
{
    public function create(array $data): Club
    {
        return Club::query()
            ->create($data);
    }
}
