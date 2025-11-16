<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreStudentRequest extends FormRequest
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
            'name' => ['required', 'string', 'max:255'],
            'roll' => ['required', 'integer'],
            'created_by' => ['nullable', 'integer'],
            'updated_by' => ['nullable', 'integer'],
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'The name field is required.',
            'name.string' => 'The name must be a string.',
            'name.max' => 'The name must not exceed 255 characters.',
            'roll.required' => 'The roll field is required.',
            'roll.integer' => 'The roll must be an integer.',
            'is_active.required' => 'The is active field is required.',
            'is_active.boolean' => 'The is active must be true or false.',
            'created_by.required' => 'The created by field is required.',
            'created_by.integer' => 'The created by must be an integer.',
            'updated_by.required' => 'The updated by field is required.',
            'updated_by.integer' => 'The updated by must be an integer.',
        ];
    }
}
