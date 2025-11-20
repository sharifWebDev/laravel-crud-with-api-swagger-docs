<?php

namespace App\Events;

use App\Models\User;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ProfileUpdated implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $user;

    public $updatedFields;

    /**
     * Create a new event instance.
     */
    public function __construct(User $user, array $updatedFields = [])
    {
        $this->user = $user;
        $this->updatedFields = $updatedFields;

        // Don't send sensitive data
        $this->user->makeHidden(['password', 'remember_token']);
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('user.'.$this->user->id),
            new Channel('admin.users'), // For admin panel updates
        ];
    }

    /**
     * The event's broadcast name.
     */
    public function broadcastAs(): string
    {
        return 'profile.updated';
    }

    /**
     * Get the data to broadcast.
     */
    public function broadcastWith(): array
    {
        return [
            'user_id' => $this->user->unique_id,
            'profile' => [
                'user_id' => $this->user->unique_id,
                'full_name' => $this->user->full_name,
                'email' => $this->user->email,
                'phone' => $this->user->phone_country_code ? [
                    'country_code' => $this->user->phone_country_code,
                    'number' => $this->user->phone_number,
                ] : null,
                'profile_img' => $this->user->profile_img,
                'auth_type' => $this->user->auth_type,
                'app_version' => $this->user->app_version,
                'acc_status' => $this->user->acc_status,
            ],
            'updated_fields' => $this->updatedFields,
            'updated_at' => $this->user->updated_at->toISOString(),
            'updated_by' => 'admin', // or 'user' if self-updated
        ];
    }
}
