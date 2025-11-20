<?php

namespace App\Services\Implementations;

use App\Models\User;
use App\Services\Contracts\TokenServiceInterface;
use Carbon\Carbon;
use Laravel\Sanctum\NewAccessToken;
use Laravel\Sanctum\PersonalAccessToken;

class TokenService implements TokenServiceInterface
{
    public function createToken(User $user, string $name, array $abilities = ['*']): NewAccessToken
    {
        return $user->createToken($name, $abilities);
    }

    public function revokeToken(User $user, $tokenId = null): bool
    {
        if ($tokenId) {
            return $user->tokens()->where('id', $tokenId)->delete() > 0;
        }

        $user->currentAccessToken()->delete();

        return true;
    }

    public function revokeAllTokens(User $user): bool
    {
        return $user->tokens()->delete() > 0;
    }

    public function getTokenAbilities(User $user): array
    {
        $token = $user->currentAccessToken();

        return $token ? $token->abilities : [];
    }

    public function tokenExists(string $token): bool
    {
        return PersonalAccessToken::findToken($token) !== null;
    }

    public function getTokenUser(string $token): ?User
    {
        $accessToken = PersonalAccessToken::findToken($token);

        return $accessToken?->tokenable;
    }

    public function getCurrentToken(User $user): ?object
    {
        return $user->currentAccessToken();
    }

    public function isTokenExpired(User $user): bool
    {
        $token = $this->getCurrentToken($user);

        if (! $token || ! $token->expires_at) {
            return false;
        }

        return $token->expires_at->isPast();
    }

    public function createExpiringToken(User $user, string $name, array $abilities = ['*'], int $expiresInHours = 24): NewAccessToken
    {
        $token = $user->createToken($name, $abilities);

        // Set expiration time
        $token->accessToken->update([
            'expires_at' => Carbon::now()->addHours($expiresInHours),
        ]);

        return $token;
    }
}
