<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CertificateResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'                 => $this->id,
            'user_id'            => $this->user_id,
            'course_id'          => $this->course_id,
            'certificate_number' => $this->certificate_number,
            'certificate_url'    => $this->certificate_url,
            'issued_at'          => $this->issued_at?->toISOString(),
            'expired_at'         => $this->expired_at?->toISOString(),
            'course'             => new CourseResource($this->whenLoaded('course')),
        ];
    }
}
