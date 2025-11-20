<?php

namespace App\Events;

use App\Models\User;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class UserStatusUpdated implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $user;

    public $oldStatus;

    public $newStatus;

    public function __construct(User $user, string $oldStatus, string $newStatus)
    {
        $this->user = $user;
        $this->oldStatus = $oldStatus;
        $this->newStatus = $newStatus;

        $this->user->makeHidden(['password', 'remember_token']);
    }

    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('user.'.$this->user->id),
            new Channel('admin.users'),
        ];
    }

    public function broadcastAs(): string
    {
        return 'user.status.updated';
    }

    public function broadcastWith(): array
    {
        return [
            'user_id' => $this->user->unique_id,
            'old_status' => $this->oldStatus,
            'new_status' => $this->newStatus,
            'profile' => [
                'user_id' => $this->user->unique_id,
                'full_name' => $this->user->full_name,
                'email' => $this->user->email,
                'acc_status' => $this->user->acc_status,
            ],
            'updated_at' => now()->toISOString(),
        ];
    }
}
