<?php

namespace App\Http\Requests\Quiz;

use Illuminate\Foundation\Http\FormRequest;

class GradeAnswerRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'grades'              => ['required', 'array'],
            'grades.*.answer_id'  => ['required', 'integer', 'exists:quiz_answers,id'],
            'grades.*.score'      => ['required', 'numeric', 'min:0'],
            'grades.*.feedback'   => ['nullable', 'string'],
        ];
    }
}
