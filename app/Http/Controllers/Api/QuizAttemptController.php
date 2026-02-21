<?php

namespace App\Http\Controllers\Api;

use App\Contracts\Services\QuizServiceInterface;
use App\Http\Controllers\Controller;
use App\Http\Requests\Quiz\GradeAnswerRequest;
use App\Http\Requests\Quiz\StoreAttemptRequest;
use App\Http\Requests\Quiz\SubmitAnswerRequest;
use App\Http\Resources\QuizAttemptResource;
use App\Models\Quiz;
use App\Models\QuizAttempt;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class QuizAttemptController extends Controller
{
    public function __construct(
        private readonly QuizServiceInterface $quizService
    ) {}

    public function store(StoreAttemptRequest $request): JsonResponse
    {
        $quiz    = Quiz::findOrFail($request->integer('quiz_id'));
        $attempt = $this->quizService->startAttempt($request->user(), $quiz);

        return $this->created(new QuizAttemptResource($attempt));
    }

    public function show(Request $request, QuizAttempt $attempt): JsonResponse
    {
        if ($attempt->user_id !== $request->user()->id && ! $request->user()->isAdmin()) {
            return $this->forbidden();
        }

        $attempt->load(['quiz.questions.options', 'answers']);

        return $this->success(new QuizAttemptResource($attempt));
    }

    public function submit(SubmitAnswerRequest $request, QuizAttempt $attempt): JsonResponse
    {
        if ($attempt->user_id !== $request->user()->id) {
            return $this->forbidden();
        }

        $attempt = $this->quizService->submitAttempt($attempt, $request->validated('answers'));

        return $this->success(new QuizAttemptResource($attempt), 'Quiz submitted successfully');
    }

    public function grade(GradeAnswerRequest $request, QuizAttempt $attempt): JsonResponse
    {
        $attempt = $this->quizService->gradeEssayAnswers($attempt, $request->validated('grades'), $request->user());

        return $this->success(new QuizAttemptResource($attempt), 'Quiz graded successfully');
    }
}
