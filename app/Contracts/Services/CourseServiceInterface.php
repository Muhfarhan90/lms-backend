<?php

namespace App\Contracts\Services;

use App\Models\Course;
use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface CourseServiceInterface
{
    public function list(array $filters = [], int $perPage = 15): LengthAwarePaginator;

    public function findOrFail(int $id): Course;

    public function create(User $instructor, array $data): Course;

    public function update(Course $course, array $data): Course;

    public function delete(Course $course): void;
}
