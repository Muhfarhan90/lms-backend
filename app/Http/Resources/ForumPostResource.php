<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ForumPostResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'           => $this->id,
            'forum_id'     => $this->forum_id,
            'user_id'      => $this->user_id,
            'title'        => $this->title,
            'content'      => $this->content,
            'author'       => new UserResource($this->whenLoaded('user')),
            'replies_count' => $this->whenCounted('replies'),
            'replies'      => ForumReplyResource::collection($this->whenLoaded('replies')),
            'created_at'   => $this->created_at?->toISOString(),
            'updated_at'   => $this->updated_at?->toISOString(),
        ];
    }
}
