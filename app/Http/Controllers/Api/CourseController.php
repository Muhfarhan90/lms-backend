<?php

namespace App\Http\Controllers\Api;

use App\Contracts\Services\CourseServiceInterface;
use App\Http\Controllers\Controller;
use App\Http\Requests\Course\StoreCourseRequest;
use App\Http\Requests\Course\UpdateCourseRequest;
use App\Http\Resources\CourseResource;
use App\Models\Course;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CourseController extends Controller
{
    public function __construct(
        private readonly CourseServiceInterface $courseService
    ) {}

    public function index(Request $request): JsonResponse
    {
        $filters = $request->only(['category_id', 'search', 'is_active', 'instructor_id']);
        $courses  = $this->courseService->list($filters, $request->integer('per_page', 15));

        return $this->paginated(CourseResource::collection($courses));
    }

    public function show(Course $course): JsonResponse
    {
        $course->load(['category', 'instructor', 'sections.lessons']);
        $course->loadCount(['enrollments', 'reviews']);

        return $this->success(new CourseResource($course));
    }

    public function store(StoreCourseRequest $request): JsonResponse
    {
        $course = $this->courseService->create($request->user(), $request->validated());

        return $this->created(new CourseResource($course->load(['category', 'instructor'])));
    }

    public function update(UpdateCourseRequest $request, Course $course): JsonResponse
    {
        $updated = $this->courseService->update($course, $request->validated());

        return $this->success(new CourseResource($updated->load(['category', 'instructor'])), 'Course updated successfully');
    }

    public function destroy(Course $course): JsonResponse
    {
        $this->courseService->delete($course);

        return $this->success(message: 'Course deleted successfully');
    }
}
