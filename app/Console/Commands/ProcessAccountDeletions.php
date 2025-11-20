<?php

namespace App\Console\Commands;

use App\Repositories\Contracts\UserRepositoryInterface;
use Illuminate\Console\Command;

class ProcessAccountDeletions extends Command
{
    protected $signature = 'accounts:process-deletions';

    protected $description = 'Process account deletions that are pending for more than 7 days';

    // public function __construct(private UserRepositoryInterface $userRepository)
    // {
    //     parent::__construct();
    // }

    public function handle(): void
    {
        $users = $this->userRepository->getPendingDeletionUsers();

        foreach ($users as $user) {
            $user->update(['acc_status' => 'deleted']);
            $user->tokens()->delete();
            // Additional cleanup logic can be added here

        }

        $this->info("Processed {$users->count()} account deletions.");
    }
}
