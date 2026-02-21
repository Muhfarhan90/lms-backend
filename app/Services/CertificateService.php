<?php

namespace App\Services;

use App\Contracts\Services\CertificateServiceInterface;
use App\Models\Certificate;
use App\Models\Enrollment;
use Illuminate\Database\Eloquent\Collection;

class CertificateService implements CertificateServiceInterface
{
    public function issue(Enrollment $enrollment): Certificate
    {
        // Check if certificate already exists
        $existing = Certificate::where('user_id', $enrollment->user_id)
            ->where('course_id', $enrollment->course_id)
            ->first();

        if ($existing) {
            return $existing;
        }

        return Certificate::create([
            'user_id'            => $enrollment->user_id,
            'course_id'          => $enrollment->course_id,
            'certificate_number' => $this->generateCertificateNumber($enrollment),
            'issued_at'          => now(),
        ]);
    }

    public function findByUser(int $userId): Collection
    {
        return Certificate::with('course')
            ->where('user_id', $userId)
            ->latest('issued_at')
            ->get();
    }

    private function generateCertificateNumber(Enrollment $enrollment): string
    {
        return sprintf(
            'CERT-%d-%d-%s',
            $enrollment->course_id,
            $enrollment->user_id,
            strtoupper(now()->format('YmdHis'))
        );
    }
}
