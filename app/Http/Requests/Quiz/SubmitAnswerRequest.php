<?php

namespace App\Http\Requests\Quiz;

use Illuminate\Foundation\Http\FormRequest;

class SubmitAnswerRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'answers'                    => ['required', 'array'],
            'answers.*.question_id'      => ['required', 'integer', 'exists:questions,id'],
            'answers.*.selected_option_id' => ['nullable', 'integer', 'exists:options,id'],
            'answers.*.answer_text'      => ['nullable', 'string'],
        ];
    }
}
