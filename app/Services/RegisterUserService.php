<?php

namespace App\Services;

use App\Repositories\UserRepository;
use Illuminate\Support\Str;

final readonly class RegisterUserService
{
    public function __construct(private UserRepository $userRepository) {}

    /*
     * Registers new user
     */
    public function execute(array $data): void
    {
        $data += [
            'id' => Str::uuid(),
        ];

        $this->userRepository->create($data);
    }
}
