<?php

namespace App\Repositories\Implementations;

use App\Models\User;
use App\Repositories\Contracts\UserRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class UserRepository implements UserRepositoryInterface
{
    public function findByEmail(string $email): ?User
    {
        return User::where('email', $email)->first();
    }

    public function create(array $data): User
    {
        return User::create($data);
    }

    public function update(User $user, array $data): bool
    {
        return $user->update($data);
    }

    public function delete(User $user): bool
    {
        return $user->delete();
    }

    public function findByFirebaseId(string $firebaseId): ?User
    {
        return User::where('firebase_id', $firebaseId)->first();
    }

    public function markForDeletion(User $user): bool
    {
        return $user->update([
            'acc_status' => 'pending_deletion',
            'delete_requested_at' => now(),
        ]);
    }

    public function cancelDeletion(User $user): bool
    {
        return $user->update([
            'acc_status' => 'active',
            'delete_requested_at' => null,
        ]);
    }

    public function getPendingDeletionUsers(): Collection
    {
        return User::where('acc_status', 'pending_deletion')
            ->where('delete_requested_at', '<=', now()->subDays(7))
            ->get();
    }

    public function findById(int $id): ?User
    {
        return User::find($id);
    }

    public function findByUniqueId(string $uniqueId): ?User
    {
        return User::where('unique_id', $uniqueId)->first();
    }
}
