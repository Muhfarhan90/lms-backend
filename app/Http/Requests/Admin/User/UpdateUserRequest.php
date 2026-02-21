<?php

namespace App\Http\Requests\Admin\User;

use Illuminate\Foundation\Http\FormRequest;

class UpdateUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $userId = $this->route('user')?->id;

        return [
            'role_id'       => ['sometimes', 'integer', 'exists:roles,id'],
            'name'          => ['sometimes', 'string', 'max:255'],
            'email'         => ['sometimes', 'string', 'email', 'max:255', 'unique:users,email,' . $userId],
            'password'      => ['nullable', 'string', 'min:8'],
            'nisn'          => ['nullable', 'string', 'max:20', 'unique:users,nisn,' . $userId],
            'phone'         => ['nullable', 'string', 'max:20'],
            'school_origin' => ['nullable', 'string', 'max:255'],
            'is_active'     => ['boolean'],
        ];
    }
}
