<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ForumResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'          => $this->id,
            'course_id'   => $this->course_id,
            'name'        => $this->name,
            'posts_count' => $this->whenCounted('posts'),
            'posts'       => ForumPostResource::collection($this->whenLoaded('posts')),
        ];
    }
}
