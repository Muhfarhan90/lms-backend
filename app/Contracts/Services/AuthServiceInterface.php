<?php

namespace App\Contracts\Services;

use App\Models\User;

interface AuthServiceInterface
{
    public function login(array $credentials): array;

    public function register(array $data): array;

    public function logout(User $user): void;
}
