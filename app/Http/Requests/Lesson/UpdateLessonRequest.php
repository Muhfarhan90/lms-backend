<?php

namespace App\Http\Requests\Lesson;

use App\Enums\LessonType;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateLessonRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title'       => ['sometimes', 'string', 'max:255'],
            'type'        => ['sometimes', Rule::enum(LessonType::class)],
            'content_url' => ['nullable', 'string', 'max:2048'],
            'sort_order'  => ['nullable', 'integer', 'min:0'],
        ];
    }
}
