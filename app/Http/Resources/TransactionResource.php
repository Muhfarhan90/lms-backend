<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TransactionResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'             => $this->id,
            'invoice_number' => $this->invoice_number,
            'user_id'        => $this->user_id,
            'subtotal'       => $this->subtotal,
            'discount_total' => $this->discount_total,
            'total_amount'   => $this->total_amount,
            'status'         => $this->status,
            'payment_method' => $this->payment_method,
            'payment_proof'  => $this->payment_proof,
            'paid_at'        => $this->paid_at?->toISOString(),
            'verified_at'    => $this->verified_at?->toISOString(),
            'user'           => new UserResource($this->whenLoaded('user')),
            'items'          => TransactionItemResource::collection($this->whenLoaded('items')),
            'voucher'        => new VoucherResource($this->whenLoaded('voucher')),
            'created_at'     => $this->created_at?->toISOString(),
        ];
    }
}
