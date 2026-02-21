<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class EnrollmentResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'           => $this->id,
            'user_id'      => $this->user_id,
            'course_id'    => $this->course_id,
            'status'       => $this->status,
            'enrolled_at'  => $this->enrolled_at?->toISOString(),
            'completed_at' => $this->completed_at?->toISOString(),
            'course'       => new CourseResource($this->whenLoaded('course')),
            'user'         => new UserResource($this->whenLoaded('user')),
        ];
    }
}
