<?php

namespace App\Services\Contracts;

use App\Models\User;
use Illuminate\Validation\ValidationException;
use Laravel\Sanctum\NewAccessToken;

interface AuthServiceInterface
{
    /**
     * Register a new user and return token with profile data
     *
     * @throws ValidationException
     */
    public function register(array $data): array;

    /**
     * Authenticate user and return token with profile data
     *
     * @throws ValidationException
     */
    public function login(array $credentials): array;

    /**
     * Generate authentication token for user
     */
    public function generateToken(User $user): string;

    /**
     * Create custom token with specific abilities
     */
    public function createCustomToken(User $user, string $tokenName, array $abilities = ['*']): NewAccessToken;

    /**
     * Validate API key for the given platform
     */
    public function validateApiKey(string $apiKey, string $platform): bool;

    /**
     * Revoke current user token
     *
     * @param  mixed  $tokenId
     */
    public function revokeToken(User $user, $tokenId = null): bool;

    /**
     * Revoke all user tokens
     */
    public function revokeAllTokens(User $user): bool;

    /**
     * Get current token abilities
     */
    public function getTokenAbilities(User $user): array;

    /**
     * Check if user credentials are valid
     */
    public function validateCredentials(string $email, string $password): bool;

    /**
     * Find user by email
     */
    public function findUserByEmail(string $email): ?User;

    /**
     * Find user by Firebase ID
     */
    public function findUserByFirebaseId(string $firebaseId): ?User;

    /**
     * Update user's last login timestamp
     */
    public function updateLastLogin(User $user): bool;

    /**
     * Check if user account is active and not scheduled for deletion
     */
    public function isUserActive(User $user): bool;

    /**
     * Validate user registration data
     *
     * @throws ValidationException
     */
    public function validateRegistrationData(array $data): array;

    /**
     * Handle social authentication (Google/Apple)
     */
    public function handleSocialAuth(array $socialData): array;

    /**
     * Change user password
     *
     * @throws ValidationException
     */
    public function changePassword(User $user, string $currentPassword, string $newPassword): bool;

    /**
     * Reset user password
     */
    public function resetPassword(string $email, string $newPassword): bool;

    /**
     * Verify user email
     */
    public function verifyEmail(User $user): bool;

    /**
     * Check if email already exists
     */
    public function emailExists(string $email): bool;

    /**
     * Get user platform from request
     */
    public function getPlatformFromRequest(): string;

    /**
     * Generate random password for social auth
     */
    public function generateRandomPassword(): string;

    /**
     * Format user profile data for response
     */
    public function formatUserProfile(User $user): array;
}
