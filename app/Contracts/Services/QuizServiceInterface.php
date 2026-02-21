<?php

namespace App\Contracts\Services;

use App\Models\Quiz;
use App\Models\QuizAttempt;
use App\Models\User;

interface QuizServiceInterface
{
    public function startAttempt(User $user, Quiz $quiz): QuizAttempt;

    public function submitAttempt(QuizAttempt $attempt, array $answers): QuizAttempt;

    public function gradeEssayAnswers(QuizAttempt $attempt, array $grades, User $gradedBy): QuizAttempt;
}
