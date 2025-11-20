<?php

namespace App\Services\Implementations;

use App\Models\User;
use App\Repositories\Contracts\ApiKeyRepositoryInterface;
use App\Repositories\Contracts\UserRepositoryInterface;
use App\Services\Contracts\AuthServiceInterface;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Laravel\Sanctum\NewAccessToken;

class AuthService implements AuthServiceInterface
{
    public function __construct(
        private UserRepositoryInterface $userRepository,
        private ApiKeyRepositoryInterface $apiKeyRepository
    ) {}

    public function register(array $data): array
    {
        // Validate registration data
        $validatedData = $this->validateRegistrationData($data);

        // Handle password generation for social auth
        if (in_array($validatedData['auth_type'], ['gmail', 'apple']) && empty($validatedData['password'])) {
            $validatedData['password'] = $this->generateRandomPassword();
        }

        // Encrypt password
        $validatedData['password'] = Hash::make($validatedData['password']);

        // Set IP address if not provided
        if (empty($validatedData['ip_address'])) {
            $validatedData['ip_address'] = request()->ip(); // Use helper instead of dependency
        }

        // Create user
        $user = $this->userRepository->create($validatedData);

        // Generate token
        $token = $this->generateToken($user);

        // Update last login
        $this->updateLastLogin($user);

        return [
            'user' => $user,
            'token' => $token,
        ];
    }

    public function login(array $credentials): array
    {
        $user = $this->findUserByEmail($credentials['email']);

        if (! $user || ! Hash::check($credentials['password'], $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.'],
            ]);
        }

        // Check account status
        if (! $this->isUserActive($user)) {
            if ($user->isPendingDeletion()) {
                throw ValidationException::withMessages([
                    'email' => ['Your account is scheduled for deletion. Please cancel the deletion request to continue.'],
                ]);
            }

            if ($user->isDeleted()) {
                throw ValidationException::withMessages([
                    'email' => ['Your account has been deleted.'],
                ]);
            }
        }

        // Generate token
        $token = $this->generateToken($user);

        // Update last login
        $this->updateLastLogin($user);

        return [
            'user' => $user,
            'token' => $token,
        ];
    }

    public function generateToken(User $user): string
    {
        $platform = $this->getPlatformFromRequest();
        $tokenName = "{$platform}_auth_token";

        $token = $user->createToken($tokenName, ['*']);

        return $token->plainTextToken;
    }

    public function createCustomToken(User $user, string $tokenName, array $abilities = ['*']): NewAccessToken
    {
        return $user->createToken($tokenName, $abilities);
    }

    public function validateApiKey(string $apiKey, string $platform): bool
    {
        return $this->apiKeyRepository->isValidKey($apiKey, $platform);
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

    public function validateCredentials(string $email, string $password): bool
    {
        $user = $this->findUserByEmail($email);

        if (! $user) {
            return false;
        }

        return Hash::check($password, $user->password);
    }

    public function findUserByEmail(string $email): ?User
    {
        return $this->userRepository->findByEmail($email);
    }

    public function findUserByFirebaseId(string $firebaseId): ?User
    {
        return $this->userRepository->findByFirebaseId($firebaseId);
    }

    public function updateLastLogin(User $user): bool
    {
        return $this->userRepository->update($user, [
            'last_login_at' => now(),
        ]);
    }

    public function isUserActive(User $user): bool
    {
        return $user->acc_status === 'active' && ! $user->isPendingDeletion() && ! $user->isDeleted();
    }

    public function validateRegistrationData(array $data): array
    {
        $validator = Validator::make($data, [
            'full_name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => [
                'required_if:auth_type,email_pass',
                'string',
                'min:8',
            ],
            'auth_type' => 'required|in:gmail,apple,email_pass',
            'phone_country_code' => 'nullable|string|max:5',
            'phone_number' => 'nullable|string|max:15',
            'profile_img' => 'nullable|string',
            'app_version' => 'nullable|string|max:20',
            'ip_address' => 'nullable|ip',
            'firebase_id' => 'required|string',
        ], [
            'auth_type.in' => 'The auth type must be one of: gmail, apple, email_pass.',
            'password.required_if' => 'Password is required when using email authentication.',
        ]);

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }

        return $validator->validated();
    }

    public function handleSocialAuth(array $socialData): array
    {
        // Check if user exists with this email
        $user = $this->findUserByEmail($socialData['email']);

        if ($user) {
            // User exists, update firebase_id if different
            if ($user->firebase_id !== $socialData['firebase_id']) {
                $this->userRepository->update($user, [
                    'firebase_id' => $socialData['firebase_id'],
                ]);
            }

            // Generate token
            $token = $this->generateToken($user);

            // Update last login
            $this->updateLastLogin($user);

            return [
                'user' => $user,
                'token' => $token,
                'is_new_user' => false,
            ];
        }

        // Create new user for social auth
        $registrationData = [
            'full_name' => $socialData['full_name'],
            'email' => $socialData['email'],
            'auth_type' => $socialData['auth_type'],
            'firebase_id' => $socialData['firebase_id'],
            'profile_img' => $socialData['profile_img'] ?? null,
            'app_version' => $socialData['app_version'] ?? null,
            'ip_address' => request()->ip(), // Use helper instead of dependency
        ];

        return array_merge(
            $this->register($registrationData),
            ['is_new_user' => true]
        );
    }

    public function changePassword(User $user, string $currentPassword, string $newPassword): bool
    {
        if (! Hash::check($currentPassword, $user->password)) {
            throw ValidationException::withMessages([
                'current_password' => ['The current password is incorrect.'],
            ]);
        }

        if (Hash::check($newPassword, $user->password)) {
            throw ValidationException::withMessages([
                'new_password' => ['The new password must be different from the current password.'],
            ]);
        }

        return $this->userRepository->update($user, [
            'password' => Hash::make($newPassword),
        ]);
    }

    public function resetPassword(string $email, string $newPassword): bool
    {
        $user = $this->findUserByEmail($email);

        if (! $user) {
            return false;
        }

        return $this->userRepository->update($user, [
            'password' => Hash::make($newPassword),
        ]);
    }

    public function verifyEmail(User $user): bool
    {
        if ($user->hasVerifiedEmail()) {
            return true;
        }

        return $this->userRepository->update($user, [
            'email_verified_at' => now(),
        ]);
    }

    public function emailExists(string $email): bool
    {
        return $this->findUserByEmail($email) !== null;
    }

    public function getPlatformFromRequest(): string
    {
        $userAgent = request()->header('User-Agent', '');
        $apiKey = request()->header('API_KEY');

        // First try to get platform from API key
        if ($apiKey) {
            $platformFromKey = $this->apiKeyRepository->getPlatformByKey($apiKey);
            if ($platformFromKey) {
                return $platformFromKey;
            }
        }

        // Fallback to User-Agent detection
        if (str_contains(strtolower($userAgent), 'android')) {
            return 'android';
        } elseif (str_contains(strtolower($userAgent), 'iphone') ||
                  str_contains(strtolower($userAgent), 'ipad')) {
            return 'ios';
        }

        return 'web';
    }

    public function generateRandomPassword(): string
    {
        return Hash::make(bin2hex(random_bytes(16)));
    }

    public function formatUserProfile(User $user): array
    {
        return [
            'user_id' => $user->unique_id,
            'full_name' => $user->full_name,
            'email' => $user->email,
            'phone' => $user->phone_country_code ? [
                'country_code' => $user->phone_country_code,
                'number' => $user->phone_number,
            ] : null,
            'profile_img' => $user->profile_img,
            'auth_type' => $user->auth_type,
            'app_version' => $user->app_version,
            'ip_address' => $user->ip_address,
            'firebase_id' => $user->firebase_id,
            'acc_status' => $user->acc_status,
            'email_verified_at' => $user->email_verified_at?->toISOString(),
            'last_login_at' => $user->last_login_at?->toISOString(),
        ];
    }
}
