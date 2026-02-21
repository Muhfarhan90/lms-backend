<?php

namespace App\Contracts\Services;

use App\Models\Enrollment;
use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface EnrollmentServiceInterface
{
    public function enroll(User $user, int $courseId): Enrollment;

    public function myEnrollments(User $user, int $perPage = 15): LengthAwarePaginator;

    public function updateProgress(Enrollment $enrollment): Enrollment;
}
