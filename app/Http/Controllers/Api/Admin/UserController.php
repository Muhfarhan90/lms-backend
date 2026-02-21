<?php

namespace App\Http\Controllers\Api\Admin;

use App\Contracts\Repositories\UserRepositoryInterface;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\User\StoreUserRequest;
use App\Http\Requests\Admin\User\UpdateUserRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function __construct(
        private readonly UserRepositoryInterface $userRepository
    ) {}

    public function index(Request $request): JsonResponse
    {
        $filters = $request->only(['role_id', 'search', 'is_active']);
        $users   = $this->userRepository->paginate($request->integer('per_page', 15), $filters);

        return $this->paginated(UserResource::collection($users));
    }

    public function show(User $user): JsonResponse
    {
        $user->load('role');

        return $this->success(new UserResource($user));
    }

    public function store(StoreUserRequest $request): JsonResponse
    {
        $data             = $request->validated();
        $data['password'] = Hash::make($data['password']);

        $user = $this->userRepository->create($data);

        return $this->created(new UserResource($user->load('role')));
    }

    public function update(UpdateUserRequest $request, User $user): JsonResponse
    {
        $data = $request->validated();

        if (! empty($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        } else {
            unset($data['password']);
        }

        $user = $this->userRepository->update($user, $data);

        return $this->success(new UserResource($user->load('role')), 'User updated successfully');
    }

    public function destroy(User $user): JsonResponse
    {
        $this->userRepository->delete($user);

        return $this->success(message: 'User deleted successfully');
    }

    public function toggleActive(User $user): JsonResponse
    {
        $this->userRepository->update($user, ['is_active' => ! $user->is_active]);

        $status = $user->fresh()->is_active ? 'activated' : 'deactivated';

        return $this->success(new UserResource($user->fresh('role')), "User {$status} successfully");
    }
}
