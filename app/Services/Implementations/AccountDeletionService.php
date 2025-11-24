<?php

namespace App\Services\Implementations;

use App\Events\UserStatusUpdated;
use App\Models\User;
use App\Repositories\Contracts\UserRepositoryInterface;
use App\Services\Contracts\AccountDeletionServiceInterface;

class AccountDeletionService implements AccountDeletionServiceInterface
{
    private const DELETION_DAYS = 7;

    public function __construct(private UserRepositoryInterface $userRepository) {}

    public function requestDeletion(User $user): void
    {
        $oldStatus = $user->acc_status;

        $this->userRepository->markForDeletion($user);

        // Refresh user to get updated data
        $user->refresh();

        // Broadcast status change
        event(new UserStatusUpdated($user, $oldStatus, $user->acc_status));
    }

    public function cancelDeletion(User $user): void
    {
        $oldStatus = $user->acc_status;

        $this->userRepository->cancelDeletion($user);

        // Refresh user to get updated data
        $user->refresh();

        // Broadcast status change
        event(new UserStatusUpdated($user, $oldStatus, $user->acc_status));
    }

    public function processScheduledDeletions(): void
    {
        $users = $this->userRepository->getPendingDeletionUsers();

        foreach ($users as $user) {
            // Perform any pre-deletion tasks (export data, send notifications, etc.)
            $this->beforeDelete($user);

            // Delete the user
            $this->userRepository->delete($user);

            // Perform any post-deletion tasks
            $this->afterDelete($user);
        }
    }

    public function getDeletionSchedule(User $user): array
    {
        if (! $user->isPendingDeletion()) {
            return [
                'status' => 'active',
                'scheduled_deletion_date' => null,
                'days_remaining' => null,
            ];
        }

        $scheduledDeletion = $user->delete_requested_at->addDays(self::DELETION_DAYS);
        $daysRemaining = now()->diffInDays($scheduledDeletion, false);

        return [
            'status' => 'pending_deletion',
            'requested_at' => $user->delete_requested_at->toISOString(),
            'scheduled_deletion_date' => $scheduledDeletion->toISOString(),
            'days_remaining' => max(0, $daysRemaining),
        ];
    }

    private function beforeDelete(User $user): void
    {
        // Export user data if needed
        // Send final notification
        // Log deletion activity
    }

    private function afterDelete(User $user): void
    {
        // Clean up related records
        // Update analytics
        // Send confirmation to admin
    }
}
