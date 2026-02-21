<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ReviewResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'         => $this->id,
            'course_id'  => $this->course_id,
            'user_id'    => $this->user_id,
            'rating'     => $this->rating,
            'comment'    => $this->comment,
            'user'       => new UserResource($this->whenLoaded('user')),
            'course'     => new CourseResource($this->whenLoaded('course')),
            'created_at' => $this->created_at?->toISOString(),
            'updated_at' => $this->updated_at?->toISOString(),
        ];
    }
}
