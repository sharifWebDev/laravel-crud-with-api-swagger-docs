<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;

class StudentResource extends BaseResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => (int) $this->id,
            'name' => $this->name,
            'roll' => (int) $this->roll,
            'is_active' => (bool) $this->is_active,
            'created_by' => (int) $this->user?->name,
            'updated_by' => (int) $this->user?->name,
            'created_at' => $this->created_at?->format('M d, Y h:i A'),
            'updated_at' => $this->updated_at?->format('M d, Y h:i A'),
        ];
    }
}
