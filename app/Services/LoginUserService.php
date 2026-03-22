<?php

namespace App\Services;

use App\DTO\LoginServiceResultDTO;
use App\Enums\LoginError;
use App\Models\User;
use App\Repositories\UserRepository;
use Illuminate\Support\Facades\Hash;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;

final readonly class LoginUserService
{
    public function __construct(private UserRepository $userRepository) {}

    /*
     * Executes user login
     */
    public function execute(array $data): LoginServiceResultDTO
    {
        $user = $this->getUserFromLoginAttempt($data['email']);

        if (! $this->loginAttemptHasValidCredentials($user, $data['password'])) {
            return LoginServiceResultDTO::failure(LoginError::InvalidCredentials);
        }

        if (! $this->userHasVerifiedEmail($user)) {
            return LoginServiceResultDTO::failure(LoginError::EmailNotVerified);
        }

        return LoginServiceResultDTO::success($this->getJWTToken($user), 'bearer', auth('api')->factory()->getTTL() * 60, $user);
    }

    /*
     * Gets user by email
     */
    private function getUserFromLoginAttempt(string $email): ?User
    {
        return $this->userRepository->getOneByCriteria([
            'email' => $email,
        ]);
    }

    /*
     * Validates if user entered correct credentials
     */
    private function loginAttemptHasValidCredentials(?User $user, string $password): bool
    {
        return $user && Hash::check($password, $user['password']);
    }

    /*
     * Verifies if user has verified email
     */
    private function userHasVerifiedEmail(User $user): bool
    {
        return $user['email_verified_at'] !== null;
    }

    /*
     * Generates JWT token
     */
    private function getJWTToken(User $user): string
    {
        return JWTAuth::fromUser($user);
    }
}
