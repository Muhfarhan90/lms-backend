<?php

namespace App\Http\Requests\Transaction;

use Illuminate\Foundation\Http\FormRequest;

class StoreTransactionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'course_ids'     => ['required', 'array', 'min:1'],
            'course_ids.*'   => ['integer', 'exists:courses,id'],
            'voucher_code'   => ['nullable', 'string', 'exists:vouchers,code'],
            'payment_method' => ['required', 'string', 'max:50'],
        ];
    }
}
