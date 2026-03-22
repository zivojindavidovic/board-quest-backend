<?php

namespace App\DTO;

use App\Enums\LoginError;
use App\Models\User;

final readonly class LoginServiceResultDTO
{
    public function __construct(
        public bool $success,
        public ?string $token = null,
        public ?string $tokenType = null,
        public ?string $expiresIn = null,
        public ?User $user = null,
        public ?LoginError $error = null,
    ) {}

    public static function success(string $token, string $tokenType, int $expiresIn, User $user): self
    {
        return new self(true, $token, $tokenType, $expiresIn, $user);
    }

    public static function failure(LoginError $error): self
    {
        return new self(false, error: $error);
    }
}
