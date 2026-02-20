<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Course;

class Enrollment extends Model
{
    protected $fillable = [
        'user_id',
        'course_id',
        'enrolled_at',
        'completed_at',
        'status',
    ];

    protected $casts = [
        'enrolled_at' => 'datetime',
        'completed_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    /**
     * Hitung final score student untuk course ini.
     *
     * - Jika semua quiz aktif mempunyai weight → weighted average
     *   (weight tidak harus total 100, dinormalisasi otomatis)
     * - Jika tidak ada yang di-set weight → simple average
     *
     * Hanya quiz dengan status 'graded' yang dihitung.
     *
     * @return float|null  null jika belum ada quiz yang selesai
     */
    public function calculateFinalScore(): ?float
    {
        $quizzes = $this->course
            ->sections()
            ->with('lessons.quiz.attempts')
            ->get()
            ->flatMap(fn($section) => $section->lessons)
            ->map(fn($lesson) => $lesson->quiz)
            ->filter(fn($quiz) => $quiz && $quiz->is_active);

        if ($quizzes->isEmpty()) {
            return null;
        }

        $useWeight = $quizzes->every(fn($quiz) => $quiz->weight !== null);

        $totalWeightedScore = 0;
        $totalWeight        = 0;
        $counted            = 0;

        foreach ($quizzes as $quiz) {
            // Ambil attempt terakhir yang sudah graded milik user ini
            $attempt = $quiz->attempts
                ->where('user_id', $this->user_id)
                ->where('status', 'graded')
                ->sortByDesc('id')
                ->first();

            if (!$attempt) {
                continue;
            }

            // Nilai 0-100
            $percentage = $quiz->toPercentage($attempt->total_score ?? 0);
            $weight     = $useWeight ? (float) $quiz->weight : 1;

            $totalWeightedScore += $percentage * $weight;
            $totalWeight        += $weight;
            $counted++;
        }

        if ($counted === 0 || $totalWeight === 0) {
            return null;
        }

        return round($totalWeightedScore / $totalWeight, 2);
    }
}
