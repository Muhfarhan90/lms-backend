<?php

namespace App\Contracts\Repositories;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface UserRepositoryInterface
{
    public function paginate(int $perPage = 15, array $filters = []): LengthAwarePaginator;

    public function findById(int $id, array $relations = []): ?\App\Models\User;

    public function findByEmail(string $email): ?\App\Models\User;

    public function create(array $data): \App\Models\User;

    public function update(\App\Models\User $user, array $data): \App\Models\User;

    public function delete(\App\Models\User $user): bool;
}
