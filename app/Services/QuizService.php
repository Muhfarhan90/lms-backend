<?php

namespace App\Services;

use App\Contracts\Services\QuizServiceInterface;
use App\Enums\QuizAttemptStatus;
use App\Enums\QuestionType;
use App\Models\Option;
use App\Models\Quiz;
use App\Models\QuizAnswer;
use App\Models\QuizAttempt;
use App\Models\User;
use Illuminate\Validation\ValidationException;

class QuizService implements QuizServiceInterface
{
    /**
     * @throws ValidationException
     */
    public function startAttempt(User $user, Quiz $quiz): QuizAttempt
    {
        // Check if there's an existing in-progress attempt
        $existing = QuizAttempt::where('user_id', $user->id)
            ->where('quiz_id', $quiz->id)
            ->where('status', QuizAttemptStatus::InProgress->value)
            ->first();

        if ($existing) {
            return $existing->load(['quiz', 'answers']);
        }

        return QuizAttempt::create([
            'user_id'    => $user->id,
            'quiz_id'    => $quiz->id,
            'status'     => QuizAttemptStatus::InProgress->value,
            'started_at' => now(),
        ]);
    }

    /**
     * @throws ValidationException
     */
    public function submitAttempt(QuizAttempt $attempt, array $answers): QuizAttempt
    {
        if ($attempt->status !== QuizAttemptStatus::InProgress->value) {
            throw ValidationException::withMessages([
                'attempt' => 'This quiz attempt has already been submitted.',
            ]);
        }

        $totalScore    = 0;
        $hasEssay      = false;

        foreach ($answers as $answerData) {
            $question = \App\Models\Question::findOrFail($answerData['question_id']);
            $isCorrect = null;
            $score     = 0;

            if ($question->type === QuestionType::MultipleChoice->value) {
                $selectedOption = isset($answerData['selected_option_id'])
                    ? Option::find($answerData['selected_option_id'])
                    : null;

                $isCorrect = $selectedOption?->is_correct ?? false;
                $score     = $isCorrect ? $question->score : 0;
                $totalScore += $score;
            } else {
                // Essay: pending manual grading
                $hasEssay = true;
            }

            QuizAnswer::updateOrCreate(
                [
                    'attempt_id'  => $attempt->id,
                    'question_id' => $question->id,
                ],
                [
                    'selected_option_id' => $answerData['selected_option_id'] ?? null,
                    'answer_text'        => $answerData['answer_text'] ?? null,
                    'is_correct'         => $isCorrect,
                    'score'              => $score,
                ]
            );
        }

        $status = $hasEssay ? QuizAttemptStatus::Submitted->value : QuizAttemptStatus::Graded->value;

        $attempt->update([
            'total_score'  => $totalScore,
            'status'       => $status,
            'submitted_at' => now(),
            'graded_at'    => $hasEssay ? null : now(),
        ]);

        return $attempt->fresh(['quiz', 'answers']);
    }

    public function gradeEssayAnswers(QuizAttempt $attempt, array $grades, User $gradedBy): QuizAttempt
    {
        $additionalScore = 0;

        foreach ($grades as $grade) {
            $answer = QuizAnswer::findOrFail($grade['answer_id']);

            if ($answer->attempt_id !== $attempt->id) {
                continue;
            }

            $answer->update([
                'score'      => $grade['score'],
                'feedback'   => $grade['feedback'] ?? null,
                'graded_by'  => $gradedBy->id,
                'graded_at'  => now(),
                'is_correct' => $grade['score'] > 0,
            ]);

            $additionalScore += $grade['score'];
        }

        // Check if all essays are graded
        $pendingCount = QuizAnswer::where('attempt_id', $attempt->id)
            ->whereNull('graded_at')
            ->whereHas('question', fn($q) => $q->where('type', QuestionType::Essay->value))
            ->count();

        $newTotalScore = $attempt->total_score + $additionalScore;
        $updates       = ['total_score' => $newTotalScore];

        if ($pendingCount === 0) {
            $updates['status']    = QuizAttemptStatus::Graded->value;
            $updates['graded_at'] = now();
        }

        $attempt->update($updates);

        return $attempt->fresh(['quiz', 'answers']);
    }
}
