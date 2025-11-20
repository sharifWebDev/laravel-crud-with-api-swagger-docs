<?php

namespace App\Services\Contracts;

use App\Models\User;
use Laravel\Sanctum\NewAccessToken;

interface TokenServiceInterface
{
    public function createToken(User $user, string $name, array $abilities = ['*']): NewAccessToken;

    public function revokeToken(User $user, $tokenId = null): bool;

    public function revokeAllTokens(User $user): bool;

    public function getTokenAbilities(User $user): array;

    public function tokenExists(string $token): bool;

    public function getTokenUser(string $token): ?User;

    public function getCurrentToken(User $user): ?object;

    public function isTokenExpired(User $user): bool;
}
