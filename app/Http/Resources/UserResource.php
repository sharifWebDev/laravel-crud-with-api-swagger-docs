<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'user_id' => $this->unique_id,
            'full_name' => $this->full_name,
            'email' => $this->email,
            'phone' => $this->when($this->phone_country_code, [
                'country_code' => $this->phone_country_code,
                'number' => $this->phone_number,
            ]),
            'profile_img' => $this->profile_img,
            'auth_type' => $this->auth_type,
            'app_version' => $this->app_version,
            'ip_address' => $this->ip_address,
            'firebase_id' => $this->firebase_id,
            'acc_status' => $this->acc_status ?? 'active',
            'email_verified_at' => $this->email_verified_at?->toISOString(),
            'last_login_at' => $this->last_login_at?->toISOString(),
        ];
    }

    /**
     * Customize the outgoing response for the resource.
     */
    public function withResponse($request, $response)
    {
        $response->setStatusCode(201);
    }
}
