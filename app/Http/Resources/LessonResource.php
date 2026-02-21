<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class LessonResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'           => $this->id,
            'section_id'   => $this->section_id,
            'title'        => $this->title,
            'type'         => $this->type,
            'content_url'  => $this->content_url,
            'sort_order'   => $this->sort_order,
            'has_quiz'     => $this->whenLoaded('quiz', fn() => ! is_null($this->quiz), false),
            'quiz'         => new QuizResource($this->whenLoaded('quiz')),
            'is_completed' => $this->when(
                isset($this->pivot) && isset($this->pivot->is_completed),
                fn() => (bool) $this->pivot?->is_completed
            ),
        ];
    }
}
