<?php

namespace App\Services;

use App\Contracts\Repositories\EnrollmentRepositoryInterface;
use App\Contracts\Services\EnrollmentServiceInterface;
use App\Enums\EnrollmentStatus;
use App\Models\Course;
use App\Models\Enrollment;
use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Validation\ValidationException;

class EnrollmentService implements EnrollmentServiceInterface
{
    public function __construct(
        private readonly EnrollmentRepositoryInterface $enrollmentRepository
    ) {}

    /**
     * @throws ValidationException
     */
    public function enroll(User $user, int $courseId): Enrollment
    {
        // Check if already enrolled
        $existing = $this->enrollmentRepository->findByUserAndCourse($user->id, $courseId);

        if ($existing) {
            throw ValidationException::withMessages([
                'course_id' => 'You are already enrolled in this course.',
            ]);
        }

        // Check that the course is active
        $course = Course::findOrFail($courseId);

        if (! $course->is_active) {
            throw ValidationException::withMessages([
                'course_id' => 'This course is not available for enrollment.',
            ]);
        }

        return $this->enrollmentRepository->create([
            'user_id'     => $user->id,
            'course_id'   => $courseId,
            'status'      => EnrollmentStatus::Active->value,
            'enrolled_at' => now(),
        ]);
    }

    public function myEnrollments(User $user, int $perPage = 15): LengthAwarePaginator
    {
        return $this->enrollmentRepository->paginateByUser($user->id, $perPage);
    }

    public function updateProgress(Enrollment $enrollment): Enrollment
    {
        $course   = $enrollment->course()->with('sections.lessons')->first();
        $totalLessons = $course->sections->flatMap->lessons->count();

        if ($totalLessons === 0) {
            return $enrollment;
        }

        $completedLessons = \App\Models\LessonProgress::where('user_id', $enrollment->user_id)
            ->whereIn('lesson_id', $course->sections->flatMap->lessons->pluck('id'))
            ->where('is_completed', true)
            ->count();

        if ($completedLessons >= $totalLessons) {
            $enrollment = $this->enrollmentRepository->update($enrollment, [
                'status'       => EnrollmentStatus::Completed->value,
                'completed_at' => now(),
            ]);
        }

        return $enrollment;
    }
}
