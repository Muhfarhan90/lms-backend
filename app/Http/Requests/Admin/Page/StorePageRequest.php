<?php

namespace App\Http\Requests\Admin\Page;

use Illuminate\Foundation\Http\FormRequest;

class StorePageRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title'   => ['required', 'string', 'max:255'],
            'slug'    => ['required', 'string', 'max:255', 'unique:pages', 'regex:/^[a-z0-9-]+$/'],
            'content' => ['required', 'string'],
        ];
    }
}
