<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    protected $fillable = [
        'quiz_id',
        'question_text',
        'type',
        'score',
        'sort_order',
    ];

    public function quiz()
    {
        return $this->belongsTo(Quiz::class);
    }

    public function options()
    {
        return $this->hasMany(Option::class);
    }

    public function quizAnswers()
    {
        return $this->hasMany(QuizAnswer::class);
    }

    /**
     * Apakah soal ini dinilai otomatis (bukan essay/short_answer)
     */
    public function isAutoGraded(): bool
    {
        return in_array($this->type, ['single_choice', 'multiple_choice']);
    }
}
