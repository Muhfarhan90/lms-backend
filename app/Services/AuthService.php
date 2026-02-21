<?php

namespace App\Services;

use App\Contracts\Repositories\UserRepositoryInterface;
use App\Contracts\Services\AuthServiceInterface;
use App\Models\User;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Support\Facades\Hash;

class AuthService implements AuthServiceInterface
{
    public function __construct(
        private readonly UserRepositoryInterface $userRepository
    ) {}

    /**
     * @throws AuthenticationException
     */
    public function login(array $credentials): array
    {
        $user = $this->userRepository->findByEmail($credentials['email']);

        if (! $user || ! Hash::check($credentials['password'], $user->password)) {
            throw new AuthenticationException('Invalid login credentials');
        }

        if (! $user->is_active) {
            throw new AuthenticationException('Your account has been deactivated');
        }

        $token = $user->createToken('auth_token')->plainTextToken;

        return [
            'user'  => $user->load('role'),
            'token' => $token,
        ];
    }

    public function register(array $data): array
    {
        $user = $this->userRepository->create([
            'role_id'       => 3, // student
            'name'          => $data['name'],
            'email'         => $data['email'],
            'password'      => bcrypt($data['password']),
            'nisn'          => $data['nisn'] ?? null,
            'school_origin' => $data['school_origin'] ?? null,
            'is_active'     => true,
        ]);

        $token = $user->createToken('auth_token')->plainTextToken;

        return [
            'user'  => $user->load('role'),
            'token' => $token,
        ];
    }

    public function logout(User $user): void
    {
        $user->tokens()->delete();
    }
}
