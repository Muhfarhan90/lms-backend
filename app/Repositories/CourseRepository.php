<?php

namespace App\Repositories;

use App\Contracts\Repositories\CourseRepositoryInterface;
use App\Models\Course;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

class CourseRepository implements CourseRepositoryInterface
{
    public function paginate(int $perPage = 15, array $filters = []): LengthAwarePaginator
    {
        $query = Course::with(['category', 'instructor'])
            ->withCount(['enrollments', 'reviews']);

        if (! empty($filters['category_id'])) {
            $query->where('category_id', $filters['category_id']);
        }

        if (! empty($filters['search'])) {
            $query->where('title', 'like', '%' . $filters['search'] . '%');
        }

        if (isset($filters['is_active'])) {
            $query->where('is_active', $filters['is_active']);
        }

        if (! empty($filters['instructor_id'])) {
            $query->where('instructor_id', $filters['instructor_id']);
        }

        return $query->latest()->paginate($perPage);
    }

    public function findById(int $id, array $relations = []): ?Course
    {
        return Course::with($relations)->find($id);
    }

    public function findByInstructor(int $instructorId): Collection
    {
        return Course::where('instructor_id', $instructorId)->get();
    }

    public function create(array $data): Course
    {
        return Course::create($data);
    }

    public function update(Course $course, array $data): Course
    {
        $course->update($data);

        return $course->fresh();
    }

    public function delete(Course $course): bool
    {
        return $course->delete();
    }
}
