<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class VoucherResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'             => $this->id,
            'code'           => $this->code,
            'discount_type'  => $this->discount_type,
            'discount_value' => $this->discount_value,
            'max_discount'   => $this->max_discount,
            'usage_limit'    => $this->usage_limit,
            'expired_at'     => $this->expired_at?->toISOString(),
            'is_active'      => $this->is_active,
        ];
    }
}
