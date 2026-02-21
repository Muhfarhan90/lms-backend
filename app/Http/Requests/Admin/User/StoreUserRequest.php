<?php

namespace App\Http\Requests\Admin\User;

use Illuminate\Foundation\Http\FormRequest;

class StoreUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'role_id'       => ['required', 'integer', 'exists:roles,id'],
            'name'          => ['required', 'string', 'max:255'],
            'email'         => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password'      => ['required', 'string', 'min:8'],
            'nisn'          => ['nullable', 'string', 'max:20', 'unique:users'],
            'phone'         => ['nullable', 'string', 'max:20'],
            'school_origin' => ['nullable', 'string', 'max:255'],
            'is_active'     => ['boolean'],
        ];
    }
}
