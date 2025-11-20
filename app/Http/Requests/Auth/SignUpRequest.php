<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class SignUpRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'full_name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => [
                'required_if:auth_type,email_pass',
                'string',
                Password::min(8),
            ],
            'auth_type' => 'required|in:gmail,apple,email_pass',
            'phone_country_code' => 'nullable|string|max:5',
            'phone_number' => 'nullable|string|max:15',
            'profile_img' => 'nullable|string',
            'app_version' => 'nullable|string|max:20',
            'ip_address' => 'nullable|ip',
            'firebase_id' => 'required|string',
        ];
    }

    public function messages(): array
    {
        return [
            'auth_type.in' => 'The auth type must be one of: gmail, apple, email_pass.',
            'password.required_if' => 'Password is required when using email authentication.',
        ];
    }
}
