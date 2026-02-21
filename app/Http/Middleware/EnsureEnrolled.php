<?php

namespace App\Http\Middleware;

use App\Models\Enrollment;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureEnrolled
{
    /**
     * Ensure the authenticated user is enrolled in the course
     * identified by {course} route parameter.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user   = $request->user();
        $course = $request->route('course');

        if (! $user || ! $course) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        // Admins and instructors bypass enrollment check
        if ($user->isAdmin() || $user->isInstructor()) {
            return $next($request);
        }

        $courseId = is_object($course) ? $course->id : $course;

        $enrolled = Enrollment::where('user_id', $user->id)
            ->where('course_id', $courseId)
            ->whereIn('status', ['active', 'completed'])
            ->exists();

        if (! $enrolled) {
            return response()->json(['message' => 'You are not enrolled in this course.'], 403);
        }

        return $next($request);
    }
}
