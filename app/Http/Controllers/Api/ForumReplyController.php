<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Forum\StoreReplyRequest;
use App\Http\Requests\Forum\UpdateReplyRequest;
use App\Http\Resources\ForumReplyResource;
use App\Models\Course;
use App\Models\ForumPost;
use App\Models\ForumReply;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ForumReplyController extends Controller
{
    public function store(StoreReplyRequest $request, Course $course, ForumPost $post): JsonResponse
    {
        $reply = $post->replies()->create([
            'user_id' => $request->user()->id,
            'content' => $request->input('content'),
        ]);

        return $this->created(new ForumReplyResource($reply->load('user')));
    }

    public function update(UpdateReplyRequest $request, Course $course, ForumPost $post, ForumReply $reply): JsonResponse
    {
        if ($reply->user_id !== $request->user()->id && ! $request->user()->isAdmin()) {
            return $this->forbidden();
        }

        $reply->update($request->validated());

        return $this->success(new ForumReplyResource($reply->fresh('user')), 'Reply updated successfully');
    }

    public function destroy(Request $request, Course $course, ForumPost $post, ForumReply $reply): JsonResponse
    {
        if ($reply->user_id !== $request->user()->id && ! $request->user()->isAdmin()) {
            return $this->forbidden();
        }

        $reply->delete();

        return $this->success(message: 'Reply deleted successfully');
    }
}
