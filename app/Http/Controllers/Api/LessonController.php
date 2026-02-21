<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Lesson\StoreLessonRequest;
use App\Http\Requests\Lesson\UpdateLessonRequest;
use App\Http\Resources\LessonResource;
use App\Models\Lesson;
use App\Models\LessonProgress;
use App\Models\Section;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class LessonController extends Controller
{
    public function index(Section $section): JsonResponse
    {
        $lessons = $section->lessons()->with('quiz')->orderBy('sort_order')->get();

        return $this->success(LessonResource::collection($lessons));
    }

    public function show(Request $request, Section $section, Lesson $lesson): JsonResponse
    {
        $lesson->load('quiz.questions.options');

        return $this->success(new LessonResource($lesson));
    }

    public function store(StoreLessonRequest $request, Section $section): JsonResponse
    {
        $nextOrder = $section->lessons()->max('sort_order') + 1;

        $data               = $request->validated();
        $data['section_id'] = $section->id;
        $data['sort_order'] = $data['sort_order'] ?? $nextOrder;

        $lesson = Lesson::create($data);

        return $this->created(new LessonResource($lesson));
    }

    public function update(UpdateLessonRequest $request, Section $section, Lesson $lesson): JsonResponse
    {
        $lesson->update($request->validated());

        return $this->success(new LessonResource($lesson->fresh()), 'Lesson updated successfully');
    }

    public function destroy(Section $section, Lesson $lesson): JsonResponse
    {
        $lesson->delete();

        return $this->success(message: 'Lesson deleted successfully');
    }

    public function markComplete(Request $request, Section $section, Lesson $lesson): JsonResponse
    {
        $progress = LessonProgress::updateOrCreate(
            ['user_id' => $request->user()->id, 'lesson_id' => $lesson->id],
            ['is_completed' => true, 'completed_at' => now()]
        );

        return $this->success(message: 'Lesson marked as completed');
    }
}
