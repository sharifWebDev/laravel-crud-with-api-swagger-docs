<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProfileUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'full_name' => 'sometimes|required|string|max:255',
            'phone_country_code' => 'nullable|string|max:5',
            'phone_number' => 'nullable|string|max:15',
            'profile_img' => 'nullable|string',
            'app_version' => 'nullable|string|max:20',
            'ip_address' => 'nullable|ip',
        ];
    }
}
