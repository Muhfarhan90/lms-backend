<?php

namespace App\Http\Requests\Admin\Page;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePageRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $pageId = $this->route('page')?->id;

        return [
            'title'   => ['sometimes', 'string', 'max:255'],
            'slug'    => ['sometimes', 'string', 'max:255', 'unique:pages,slug,' . $pageId, 'regex:/^[a-z0-9-]+$/'],
            'content' => ['sometimes', 'string'],
        ];
    }
}
