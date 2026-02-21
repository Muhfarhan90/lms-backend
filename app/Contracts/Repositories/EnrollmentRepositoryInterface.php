<?php

namespace App\Contracts\Repositories;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

interface EnrollmentRepositoryInterface
{
    public function findByUserAndCourse(int $userId, int $courseId): ?\App\Models\Enrollment;

    public function findByUser(int $userId): Collection;

    public function paginateByUser(int $userId, int $perPage = 15): LengthAwarePaginator;

    public function create(array $data): \App\Models\Enrollment;

    public function update(\App\Models\Enrollment $enrollment, array $data): \App\Models\Enrollment;
}
