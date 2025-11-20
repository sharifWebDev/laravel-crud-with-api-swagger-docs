<?php

namespace App\Repositories\Contracts;

use App\Models\User;

interface UserRepositoryInterface
{
    public function findByEmail(string $email): ?User;

    public function create(array $data): User;

    public function update(User $user, array $data): bool;

    public function delete(User $user): bool;

    public function findByFirebaseId(string $firebaseId): ?User;

    public function markForDeletion(User $user): bool;

    public function cancelDeletion(User $user): bool;

    public function getPendingDeletionUsers();
}
