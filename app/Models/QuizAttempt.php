<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QuizAttempt extends Model
{
    protected $fillable = [
        'user_id',
        'quiz_id',
        'total_score',
        'status',
        'started_at',
        'submitted_at',
        'graded_at',
    ];

    protected $casts = [
        'started_at'   => 'datetime',
        'submitted_at' => 'datetime',
        'graded_at'    => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function quiz()
    {
        return $this->belongsTo(Quiz::class);
    }

    public function answers()
    {
        return $this->hasMany(QuizAnswer::class, 'attempt_id');
    }

    /**
     * Apakah masih ada soal essay/short_answer yang belum dinilai
     */
    public function needsManualGrading(): bool
    {
        return $this->answers()
            ->whereNull('score')
            ->whereHas('question', fn($q) => $q->whereIn('type', ['essay', 'short_answer']))
            ->exists();
    }
}
