<?php

namespace App\Repositories;

use App\Models\User;
use Illuminate\Pagination\LengthAwarePaginator;

class UserRepository
{
    public function getList(?string $search = null, int $perPage = 15): LengthAwarePaginator
    {
        return User::query()
            ->with('roles')
            ->when($search, fn ($q) => $q->where(function ($q2) use ($search) {
                $q2->where('name', 'like', "%{$search}%")
                   ->orWhere('email', 'like', "%{$search}%");
            }))
            ->latest()
            ->paginate($perPage);
    }

    public function findById(int $id): User
    {
        return User::query()->with('roles')->findOrFail($id);
    }

    public function create(array $data): User
    {
        return User::query()->create($data);
    }

    public function update(User $user, array $data): User
    {
        $user->update($data);

        return $user->refresh();
    }

    public function delete(User $user): void
    {
        $user->delete();
    }
}
