<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TransactionItemResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'              => $this->id,
            'course_id'       => $this->course_id,
            'price'           => $this->price,
            'discount_amount' => $this->discount_amount,
            'final_price'     => $this->final_price,
            'course'          => new CourseResource($this->whenLoaded('course')),
        ];
    }
}
