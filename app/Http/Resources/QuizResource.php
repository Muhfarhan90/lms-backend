<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class QuizResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'               => $this->id,
            'lesson_id'        => $this->lesson_id,
            'title'            => $this->title,
            'description'      => $this->description,
            'duration_minutes' => $this->duration_minutes,
            'passing_score'    => $this->passing_score,
            'weight'           => $this->weight,
            'is_active'        => $this->is_active,
            'questions'        => QuestionResource::collection($this->whenLoaded('questions')),
        ];
    }
}
