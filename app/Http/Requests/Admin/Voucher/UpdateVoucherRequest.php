<?php

namespace App\Http\Requests\Admin\Voucher;

use App\Enums\DiscountType;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateVoucherRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $voucherId = $this->route('voucher')?->id;

        return [
            'code'           => ['sometimes', 'string', 'max:50', 'unique:vouchers,code,' . $voucherId],
            'discount_type'  => ['sometimes', Rule::enum(DiscountType::class)],
            'discount_value' => ['sometimes', 'numeric', 'min:0'],
            'max_discount'   => ['nullable', 'numeric', 'min:0'],
            'usage_limit'    => ['nullable', 'integer', 'min:1'],
            'expired_at'     => ['nullable', 'date', 'after:now'],
            'is_active'      => ['boolean'],
        ];
    }
}
