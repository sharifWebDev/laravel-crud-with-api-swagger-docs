<?php

namespace App\Http\Requests\Quiz;

use Illuminate\Foundation\Http\FormRequest;

class SubmitQuizRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'answers' => 'required|array',
            'answers.*.question_id' => 'required|integer|exists:questions,id',
            'answers.*.answer' => 'required|string',
            'time_taken' => 'required|integer|min:0',
        ];
    }

    public function messages(): array
    {
        return [
            'answers.required' => 'Answers are required.',
            'answers.*.question_id.required' => 'Question ID is required for each answer.',
            'answers.*.question_id.exists' => 'Invalid question ID.',
            'answers.*.answer.required' => 'Answer is required for each question.',
            'time_taken.required' => 'Time taken is required.',
            'time_taken.integer' => 'Time taken must be an integer.',
        ];
    }
}
