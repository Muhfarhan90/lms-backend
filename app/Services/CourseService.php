<?php

namespace App\Services;

use App\Contracts\Repositories\CourseRepositoryInterface;
use App\Contracts\Services\CourseServiceInterface;
use App\Models\Course;
use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class CourseService implements CourseServiceInterface
{
    public function __construct(
        private readonly CourseRepositoryInterface $courseRepository
    ) {}

    public function list(array $filters = [], int $perPage = 15): LengthAwarePaginator
    {
        // Public listing: only active courses unless admin/instructor
        if (! isset($filters['is_active'])) {
            $user = request()->user();
            if (! $user || $user->isStudent()) {
                $filters['is_active'] = true;
            }
        }

        return $this->courseRepository->paginate($perPage, $filters);
    }

    public function findOrFail(int $id): Course
    {
        $course = $this->courseRepository->findById($id, [
            'category',
            'instructor',
            'sections.lessons',
        ]);

        if (! $course) {
            abort(404, 'Course not found');
        }

        return $course;
    }

    public function create(User $instructor, array $data): Course
    {
        if (isset($data['thumbnail']) && $data['thumbnail'] instanceof UploadedFile) {
            $data['thumbnail'] = $this->storeThumbnail($data['thumbnail']);
        }

        $data['instructor_id'] = $instructor->id;

        return $this->courseRepository->create($data);
    }

    public function update(Course $course, array $data): Course
    {
        if (isset($data['thumbnail']) && $data['thumbnail'] instanceof UploadedFile) {
            // Remove old thumbnail
            if ($course->thumbnail) {
                Storage::disk('public')->delete($course->thumbnail);
            }
            $data['thumbnail'] = $this->storeThumbnail($data['thumbnail']);
        }

        return $this->courseRepository->update($course, $data);
    }

    public function delete(Course $course): void
    {
        if ($course->thumbnail) {
            Storage::disk('public')->delete($course->thumbnail);
        }

        $this->courseRepository->delete($course);
    }

    private function storeThumbnail(UploadedFile $file): string
    {
        return $file->store('courses/thumbnails', 'public');
    }
}
