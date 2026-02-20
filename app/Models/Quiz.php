<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Quiz extends Model
{
    protected $fillable = [
        'lesson_id',
        'title',
        'description',
        'duration_minutes',
        'passing_score',
        'weight',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'weight'    => 'decimal:2',
    ];

    public function lesson()
    {
        return $this->belongsTo(Lesson::class);
    }

    public function questions()
    {
        return $this->hasMany(Question::class)->orderBy('sort_order');
    }

    public function attempts()
    {
        return $this->hasMany(QuizAttempt::class);
    }

    /**
     * Total skor maksimum dari semua soal
     */
    public function maxScore(): int
    {
        return $this->questions()->sum('score');
    }

    /**
     * Konversi raw score ke nilai 0-100
     */
    public function toPercentage(int $rawScore): float
    {
        $max = $this->maxScore();
        return $max > 0 ? round(($rawScore / $max) * 100, 2) : 0;
    }

    /**
     * Apakah course ini menggunakan weighted grading
     * (dicek dari semua quiz aktif dalam course yg sama)
     */
    public function hasWeight(): bool
    {
        return $this->weight !== null;
    }
}
