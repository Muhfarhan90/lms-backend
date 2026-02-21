<?php

namespace App\Contracts\Services;

use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface NotificationServiceInterface
{
    public function send(User $user, string $type, string $title, string $message, array $data = []): void;

    public function markRead(int $notificationId, User $user): void;

    public function markAllRead(User $user): void;

    public function paginate(User $user, int $perPage = 15): LengthAwarePaginator;
}
