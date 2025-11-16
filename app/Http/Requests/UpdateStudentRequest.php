<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateStudentRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; // Authorization logic if needed
    }

    public function rules()
    {
        return [
            'name' => ['sometimes', 'string', 'max:255'],
            'roll' => ['sometimes', 'integer'],
            'is_active' => ['nullable', 'boolean'],
            'updated_by' => ['nullable', 'integer'],
            'updated_at' => ['nullable', 'date'],
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'The name field is required.',
            'name.sometimes' => 'The name field is sometimes required.',
            'name.string' => 'The name must be a string.',
            'name.max' => 'The name must not exceed 255 characters.',
            'roll.required' => 'The roll field is required.',
            'roll.sometimes' => 'The roll field is sometimes required.',
            'roll.integer' => 'The roll must be an integer.',
            'is_active.required' => 'The is active field is required.',
            'is_active.sometimes' => 'The is active field is sometimes required.',
            'is_active.boolean' => 'The is active must be true or false.',
            'updated_by.required' => 'The updated by field is required.',
            'updated_by.sometimes' => 'The updated by field is sometimes required.',
            'updated_by.integer' => 'The updated by must be an integer.',
            'updated_at.required' => 'The updated at field is required.',
            'updated_at.sometimes' => 'The updated at field is sometimes required.',
            'updated_at.date' => 'The updated at must be a valid date and time.',
        ];
    }
}
