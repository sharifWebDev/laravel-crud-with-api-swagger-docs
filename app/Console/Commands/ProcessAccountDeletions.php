<?php

namespace App\Console\Commands;

use App\Repositories\Contracts\UserRepositoryInterface;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class ProcessAccountDeletions extends Command
{
    protected $signature = 'accounts:process-deletions';
    protected $description = 'Process account deletions that are pending for more than 7 days';

    public function __construct(private UserRepositoryInterface $userRepository)
    {
        parent::__construct();
    }

    public function handle(): void
    {
        $users = $this->userRepository->getPendingDeletionUsers();
        $deletedCount = 0;

        foreach ($users as $user) {
            try {
                // Delete user tokens
                $user->tokens()->delete();

                // Soft delete the user
                $user->delete();

                $this->info("✅ Deleted account for user: {$user->unique_id} ({$user->email})");
                $deletedCount++;

            } catch (\Exception $e) {
                $this->error("❌ Failed to delete user {$user->unique_id}: " . $e->getMessage());
                Log::error("Account deletion failed for user {$user->unique_id}: " . $e->getMessage());
            }
        }

        $this->info("🎯 Processed {$deletedCount} account deletions successfully.");
        Log::info("Account deletion cron: Processed {$deletedCount} accounts.");
    }
}
