<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Forum\StorePostRequest;
use App\Http\Requests\Forum\UpdatePostRequest;
use App\Http\Resources\ForumPostResource;
use App\Models\Course;
use App\Models\Forum;
use App\Models\ForumPost;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ForumPostController extends Controller
{
    public function index(Course $course): JsonResponse
    {
        $forum = $course->forums()->first();

        if (! $forum) {
            return $this->success([]);
        }

        $posts = $forum->posts()
            ->with(['user'])
            ->withCount('replies')
            ->latest()
            ->paginate(15);

        return $this->paginated(ForumPostResource::collection($posts));
    }

    public function store(StorePostRequest $request, Course $course): JsonResponse
    {
        $forum = $course->forums()->firstOrCreate(['name' => 'General Discussion']);

        $post = $forum->posts()->create([
            'user_id' => $request->user()->id,
            'title'   => $request->input('title'),
            'content' => $request->input('content'),
        ]);

        return $this->created(new ForumPostResource($post->load('user')));
    }

    public function show(Course $course, ForumPost $post): JsonResponse
    {
        $post->load(['user', 'replies.user']);

        return $this->success(new ForumPostResource($post));
    }

    public function update(UpdatePostRequest $request, Course $course, ForumPost $post): JsonResponse
    {
        if ($post->user_id !== $request->user()->id && ! $request->user()->isAdmin()) {
            return $this->forbidden();
        }

        $post->update($request->validated());

        return $this->success(new ForumPostResource($post->fresh('user')), 'Post updated successfully');
    }

    public function destroy(Request $request, Course $course, ForumPost $post): JsonResponse
    {
        if ($post->user_id !== $request->user()->id && ! $request->user()->isAdmin()) {
            return $this->forbidden();
        }

        $post->delete();

        return $this->success(message: 'Post deleted successfully');
    }
}
