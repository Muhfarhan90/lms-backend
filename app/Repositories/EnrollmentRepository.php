<?php

namespace App\Repositories;

use App\Contracts\Repositories\EnrollmentRepositoryInterface;
use App\Models\Enrollment;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

class EnrollmentRepository implements EnrollmentRepositoryInterface
{
    public function findByUserAndCourse(int $userId, int $courseId): ?Enrollment
    {
        return Enrollment::where('user_id', $userId)
            ->where('course_id', $courseId)
            ->first();
    }

    public function findByUser(int $userId): Collection
    {
        return Enrollment::with(['course.category', 'course.instructor'])
            ->where('user_id', $userId)
            ->get();
    }

    public function paginateByUser(int $userId, int $perPage = 15): LengthAwarePaginator
    {
        return Enrollment::with(['course.category', 'course.instructor'])
            ->where('user_id', $userId)
            ->latest()
            ->paginate($perPage);
    }

    public function create(array $data): Enrollment
    {
        return Enrollment::create($data);
    }

    public function update(Enrollment $enrollment, array $data): Enrollment
    {
        $enrollment->update($data);

        return $enrollment->fresh();
    }
}
