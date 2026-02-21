<?php

namespace App\Contracts\Services;

use App\Models\Certificate;
use App\Models\Enrollment;

interface CertificateServiceInterface
{
    public function issue(Enrollment $enrollment): Certificate;

    public function findByUser(int $userId): \Illuminate\Database\Eloquent\Collection;
}
