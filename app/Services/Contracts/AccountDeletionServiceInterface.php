<?php

namespace App\Services\Contracts;

use App\Models\User;

interface AccountDeletionServiceInterface
{
    public function requestDeletion(User $user): void;

    public function cancelDeletion(User $user): void;

    public function processScheduledDeletions(): void;

    public function getDeletionSchedule(User $user): array;
}
