<?php

namespace App\Services;

use App\Models\User;
use App\Repositories\UserRepository;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class UserService
{
    public function __construct(
        protected UserRepository $repository
    ) {}

    public function getList(?string $search = null, int $perPage = 15): LengthAwarePaginator
    {
        return $this->repository->getList($search, $perPage);
    }

    public function store(array $validated): User
    {
        return DB::transaction(function () use ($validated) {
            $user = $this->repository->create([
                'name'     => $validated['name'],
                'email'    => $validated['email'],
                'password' => $validated['password'],
            ]);

            $user->assignRole($validated['role']);

            return $user;
        });
    }

    public function update(User $user, array $validated): User
    {
        return DB::transaction(function () use ($user, $validated) {
            $data = [
                'name'  => $validated['name'],
                'email' => $validated['email'],
            ];

            // Hanya update password jika diisi
            if (! empty($validated['password'])) {
                $data['password'] = $validated['password'];
            }

            $updatedUser = $this->repository->update($user, $data);

            // Sync role
            $updatedUser->syncRoles([$validated['role']]);

            return $updatedUser;
        });
    }

    public function destroy(User $user): void
    {
        // Cegah super admin hapus akun sendiri
        if ($user->id === Auth::id()) {
            throw ValidationException::withMessages([
                'general' => 'Anda tidak dapat menghapus akun Anda sendiri.',
            ]);
        }

        DB::transaction(function () use ($user) {
            $this->repository->delete($user);
        });
    }
}
