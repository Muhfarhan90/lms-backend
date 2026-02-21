<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class QuizAnswerResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'                 => $this->id,
            'question_id'        => $this->question_id,
            'selected_option_id' => $this->selected_option_id,
            'answer_text'        => $this->answer_text,
            'is_correct'         => $this->is_correct,
            'score'              => $this->score,
            'feedback'           => $this->feedback,
            'graded_at'          => $this->graded_at?->toISOString(),
        ];
    }
}
