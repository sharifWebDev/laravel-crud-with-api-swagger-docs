<?php

namespace App\Services\Implementations;

use App\Events\ProfileUpdated;
use App\Models\User;
use App\Repositories\Contracts\UserRepositoryInterface;
use App\Services\Contracts\ProfileServiceInterface;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class ProfileService implements ProfileServiceInterface
{
    public function __construct(private UserRepositoryInterface $userRepository) {}

    public function getUserProfile(User $user): array
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
            'email_verified_at' => $user->email_verified_at
                ? $user->email_verified_at->format('d-m-Y')
                : null,
        ];
    }

    public function updateProfile(User $user, array $data): User
    {
        $oldData = $user->toArray();

        $updateResult = $this->userRepository->update($user, $data);

        if ($updateResult === true) {
            $updatedUser = $user->fresh();
        } elseif ($updateResult instanceof User) {
            $updatedUser = $updateResult;
        } else {
            $updatedUser = $user->fresh();
        }

        // Determine which fields were updated
        $updatedFields = [];
        foreach ($data as $field => $value) {
            if (isset($oldData[$field]) && $oldData[$field] != $value) {
                $updatedFields[] = $field;
            }
        }

        // Broadcast profile update event
        if (!empty($updatedFields)) {
            event(new ProfileUpdated($updatedUser, $updatedFields));
        }

        return $updatedUser;
    }

    public function changePassword(User $user, string $currentPassword, string $newPassword): void
    {
        if (!Hash::check($currentPassword, $user->password)) {
            throw ValidationException::withMessages([
                'current_password' => ['The current password is incorrect.'],
            ]);
        }

        if (Hash::check($newPassword, $user->password)) {
            throw ValidationException::withMessages([
                'new_password' => ['The new password must be different from the current password.'],
            ]);
        }

        $this->userRepository->update($user, [
            'password' => Hash::make($newPassword),
        ]);
    }

    public function updateProfileImage(User $user, string $profileImage): User
    {
        // Handle base64 image or URL
        if (str_starts_with($profileImage, 'data:image')) {

            $profileImage = $this->processBase64Image($profileImage, $user->unique_id);
        }

        $updateResult = $this->userRepository->update($user, [
            'profile_img' => $profileImage,
        ]);

        // Handle the return type from repository
        if ($updateResult === true) {
            $updatedUser = $user->fresh();
        } elseif ($updateResult instanceof User) {
            $updatedUser = $updateResult;
        } else {
            $updatedUser = $user->fresh();
        }

        event(new ProfileUpdated($updatedUser));

        return $updatedUser;
    }

    public function getUserStats(User $user): array
    {
        return [
            'quiz_stats' => [
                'total_quizzes_taken' => $user->quizResults()->count(),
                'average_score' => round($user->quizResults()->avg('score') ?? 0, 2),
                'total_correct_answers' => $user->quizResults()->sum('correct_answers'),
                'total_time_spent' => $user->quizResults()->sum('time_taken'), // in seconds
            ],
            'account_stats' => [
                'member_since' => $user->created_at->diffForHumans(),
                'account_status' => $user->acc_status,
                'email_verified' => !is_null($user->email_verified_at),
            ],
        ];
    }

    private function processBase64Image(string $base64Image, string $userId): string
    {
        // Extract image data and extension
        $imageData = explode(',', $base64Image);
        $imageInfo = explode(';', $imageData[0]);
        $imageExtension = explode('/', $imageInfo[0])[1];

        // Decode base64 image
        $imageContent = base64_decode($imageData[1]);

        // Generate unique filename
        $filename = "profile_{$userId}_".time().".{$imageExtension}";
        $filePath = "profiles/{$filename}";

        // Store image (you can use Laravel's Storage facade)
        \Storage::disk('public')->put($filePath, $imageContent);

        // Return the public URL
        return \Storage::disk('public')->url($filePath);
    }
}
