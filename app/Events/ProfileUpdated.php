<?php

namespace App\Events;

use App\Models\User;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ProfileUpdated implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(public User $user) {}

    public function broadcastOn(): Channel
    {
        return new Channel('user.'.$this->user->id);
    }

    public function broadcastWith(): array
    {
        return [
            'user_id' => $this->user->unique_id,
            'profile' => [
                'full_name' => $this->user->full_name,
                'email' => $this->user->email,
                'phone' => $this->user->phone_country_code ? [
                    'country_code' => $this->user->phone_country_code,
                    'number' => $this->user->phone_number,
                ] : null,
                'profile_img' => $this->user->profile_img,
            ],
            'updated_at' => $this->user->updated_at->toISOString(),
        ];
    }
}
