<?php

namespace App\Http\Controllers\Api;

use App\Contracts\Services\NotificationServiceInterface;
use App\Http\Controllers\Controller;
use App\Http\Resources\NotificationResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function __construct(
        private readonly NotificationServiceInterface $notificationService
    ) {}

    public function index(Request $request): JsonResponse
    {
        $notifications = $this->notificationService->paginate(
            $request->user(),
            $request->integer('per_page', 15)
        );

        return $this->paginated(NotificationResource::collection($notifications));
    }

    public function markRead(Request $request, int $id): JsonResponse
    {
        $this->notificationService->markRead($id, $request->user());

        return $this->success(message: 'Notification marked as read');
    }

    public function markAllRead(Request $request): JsonResponse
    {
        $this->notificationService->markAllRead($request->user());

        return $this->success(message: 'All notifications marked as read');
    }
}
