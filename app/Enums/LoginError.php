<?php

namespace App\Enums;

enum LoginError: string
{
    case InvalidCredentials = 'INVALID_CREDENTIALS';
    case EmailNotVerified = 'EMAIL_NOT_VERIFIED';

    public function message(): string
    {
        return match ($this) {
            self::InvalidCredentials => 'Invalid credentials.',
            self::EmailNotVerified => 'Email not verified.',
        };
    }

    public function httpStatus(): int
    {
        return match ($this) {
            self::InvalidCredentials => 401,
            self::EmailNotVerified => 403,
        };
    }

    public function errors(): array
    {
        return match ($this) {
            self::InvalidCredentials => [
                'credentials' => ['The provided credentials are incorrect.'],
            ],
            self::EmailNotVerified => [
                'email' => ['The email address is not verified.'],
            ],
        };
    }
}
