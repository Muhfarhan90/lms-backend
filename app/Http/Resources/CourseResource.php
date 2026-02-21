<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CourseResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'             => $this->id,
            'title'          => $this->title,
            'description'    => $this->description,
            'price'          => $this->price,
            'discount_price' => $this->discount_price,
            'thumbnail'      => $this->thumbnail,
            'is_active'      => $this->is_active,
            'average_rating' => $this->averageRating(),
            'category'       => new CategoryResource($this->whenLoaded('category')),
            'instructor'     => $this->whenLoaded('instructor', fn() => [
                'id'   => $this->instructor->id,
                'name' => $this->instructor->name,
            ]),
            'sections'         => SectionResource::collection($this->whenLoaded('sections')),
            'enrollments_count' => $this->whenCounted('enrollments'),
            'reviews_count'    => $this->whenCounted('reviews'),
            'created_at'       => $this->created_at?->toISOString(),
        ];
    }
}
