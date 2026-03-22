<?php

namespace App\Repositories;

use App\Models\User;

final readonly class UserRepository
{
    /*
     * Creates user
     */
    public function create(array $data): User
    {
        return User::query()
            ->create($data);
    }

    /*
     * Searches user by criteria
     */
    public function getOneByCriteria(array $data): ?User
    {
        return User::query()
            ->where($data)
            ->first();
    }
}
