<?php

namespace App\Contracts\Repositories;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

interface CourseRepositoryInterface
{
    public function paginate(int $perPage = 15, array $filters = []): LengthAwarePaginator;

    public function findById(int $id, array $relations = []): ?\App\Models\Course;

    public function findByInstructor(int $instructorId): Collection;

    public function create(array $data): \App\Models\Course;

    public function update(\App\Models\Course $course, array $data): \App\Models\Course;

    public function delete(\App\Models\Course $course): bool;
}
