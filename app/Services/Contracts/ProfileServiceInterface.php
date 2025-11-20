<?php

namespace App\Services\Contracts;

use App\Models\User;

interface ProfileServiceInterface
{
    public function getUserProfile(User $user): array;

    public function updateProfile(User $user, array $data): User;

    public function changePassword(User $user, string $currentPassword, string $newPassword): void;

    public function updateProfileImage(User $user, string $profileImage): User;

    public function getUserStats(User $user): array;
}
