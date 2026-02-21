<?php

namespace App\Http\Controllers\Api;

use App\Contracts\Services\EnrollmentServiceInterface;
use App\Http\Controllers\Controller;
use App\Http\Requests\Enrollment\EnrollRequest;
use App\Http\Resources\EnrollmentResource;
use App\Models\Enrollment;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class EnrollmentController extends Controller
{
    public function __construct(
        private readonly EnrollmentServiceInterface $enrollmentService
    ) {}

    public function index(Request $request): JsonResponse
    {
        $enrollments = $this->enrollmentService->myEnrollments(
            $request->user(),
            $request->integer('per_page', 15)
        );

        return $this->paginated(EnrollmentResource::collection($enrollments));
    }

    public function store(EnrollRequest $request): JsonResponse
    {
        $enrollment = $this->enrollmentService->enroll($request->user(), $request->integer('course_id'));

        return $this->created(new EnrollmentResource($enrollment->load('course')));
    }

    public function show(Request $request, Enrollment $enrollment): JsonResponse
    {
        if ($enrollment->user_id !== $request->user()->id && ! $request->user()->isAdmin()) {
            return $this->forbidden();
        }

        $enrollment->load(['course.sections.lessons', 'course.instructor']);

        return $this->success(new EnrollmentResource($enrollment));
    }
}
