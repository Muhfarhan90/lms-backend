<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class QuizAttemptResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'           => $this->id,
            'quiz_id'      => $this->quiz_id,
            'user_id'      => $this->user_id,
            'total_score'  => $this->total_score,
            'status'       => $this->status,
            'started_at'   => $this->started_at?->toISOString(),
            'submitted_at' => $this->submitted_at?->toISOString(),
            'graded_at'    => $this->graded_at?->toISOString(),
            'quiz'         => new QuizResource($this->whenLoaded('quiz')),
            'answers'      => QuizAnswerResource::collection($this->whenLoaded('answers')),
        ];
    }
}
