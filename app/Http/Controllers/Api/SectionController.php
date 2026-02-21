<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Section\StoreSectionRequest;
use App\Http\Requests\Section\UpdateSectionRequest;
use App\Http\Resources\SectionResource;
use App\Models\Course;
use App\Models\Section;
use Illuminate\Http\JsonResponse;

class SectionController extends Controller
{
    public function index(Course $course): JsonResponse
    {
        $sections = $course->sections()->with('lessons')->orderBy('sort_order')->get();

        return $this->success(SectionResource::collection($sections));
    }

    public function store(StoreSectionRequest $request, Course $course): JsonResponse
    {
        $nextOrder = $course->sections()->max('sort_order') + 1;

        $data               = $request->validated();
        $data['course_id']  = $course->id;
        $data['sort_order'] = $data['sort_order'] ?? $nextOrder;

        $section = Section::create($data);

        return $this->created(new SectionResource($section));
    }

    public function update(UpdateSectionRequest $request, Course $course, Section $section): JsonResponse
    {
        $section->update($request->validated());

        return $this->success(new SectionResource($section->fresh()), 'Section updated successfully');
    }

    public function destroy(Course $course, Section $section): JsonResponse
    {
        $section->delete();

        return $this->success(message: 'Section deleted successfully');
    }
}
