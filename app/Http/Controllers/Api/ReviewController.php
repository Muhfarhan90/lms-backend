<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Review\StoreReviewRequest;
use App\Http\Requests\Review\UpdateReviewRequest;
use App\Http\Resources\ReviewResource;
use App\Models\Course;
use App\Models\Review;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function index(Course $course): JsonResponse
    {
        $reviews = $course->reviews()->with('user')->latest()->paginate(15);

        return $this->paginated(ReviewResource::collection($reviews));
    }

    public function store(StoreReviewRequest $request): JsonResponse
    {
        $courseId = $request->integer('course_id');

        // Check if user already reviewed this course
        $existing = Review::where('user_id', $request->user()->id)
            ->where('course_id', $courseId)
            ->first();

        if ($existing) {
            return $this->error('You have already reviewed this course.', 422);
        }

        $review = Review::create([
            'user_id'   => $request->user()->id,
            'course_id' => $courseId,
            'rating'    => $request->input('rating'),
            'comment'   => $request->input('comment'),
        ]);

        return $this->created(new ReviewResource($review->load('user')));
    }

    public function update(UpdateReviewRequest $request, Review $review): JsonResponse
    {
        if ($review->user_id !== $request->user()->id) {
            return $this->forbidden();
        }

        $review->update($request->validated());

        return $this->success(new ReviewResource($review->fresh('user')), 'Review updated successfully');
    }

    public function destroy(Request $request, Review $review): JsonResponse
    {
        if ($review->user_id !== $request->user()->id && ! $request->user()->isAdmin()) {
            return $this->forbidden();
        }

        $review->delete();

        return $this->success(message: 'Review deleted successfully');
    }
}
