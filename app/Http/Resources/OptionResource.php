<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OptionResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'          => $this->id,
            'option_text' => $this->option_text,
            // is_correct is only exposed to instructors/admins (controlled at Service layer)
            'is_correct'  => $this->when(
                request()->user()?->isInstructor() || request()->user()?->isAdmin(),
                $this->is_correct
            ),
        ];
    }
}
