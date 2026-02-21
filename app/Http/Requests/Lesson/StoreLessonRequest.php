<?php

namespace App\Http\Requests\Lesson;

use App\Enums\LessonType;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreLessonRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'section_id'  => ['required', 'integer', 'exists:sections,id'],
            'title'       => ['required', 'string', 'max:255'],
            'type'        => ['required', Rule::enum(LessonType::class)],
            'content_url' => ['nullable', 'string', 'max:2048'],
            'sort_order'  => ['nullable', 'integer', 'min:0'],
        ];
    }
}
