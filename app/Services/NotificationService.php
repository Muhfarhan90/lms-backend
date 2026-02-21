<?php

namespace App\Services;

use App\Contracts\Services\NotificationServiceInterface;
use App\Models\Notification;
use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class NotificationService implements NotificationServiceInterface
{
    public function send(User $user, string $type, string $title, string $message, array $data = []): void
    {
        Notification::create([
            'user_id' => $user->id,
            'type'    => $type,
            'title'   => $title,
            'message' => $message,
            'data'    => $data,
            'is_read' => false,
        ]);
    }

    public function markRead(int $notificationId, User $user): void
    {
        Notification::where('id', $notificationId)
            ->where('user_id', $user->id)
            ->update(['is_read' => true, 'read_at' => now()]);
    }

    public function markAllRead(User $user): void
    {
        Notification::where('user_id', $user->id)
            ->where('is_read', false)
            ->update(['is_read' => true, 'read_at' => now()]);
    }

    public function paginate(User $user, int $perPage = 15): LengthAwarePaginator
    {
        return Notification::where('user_id', $user->id)
            ->latest()
            ->paginate($perPage);
    }
}
