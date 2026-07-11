<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserStoreRequest;
use App\Http\Requests\UserUpdateRequest;
use App\Models\User;
use App\Services\UserService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class UserController extends Controller
{
    public function __construct(
        private readonly UserService $service
    ) {}

    public function index(Request $request): View
    {
        $search   = $request->string('search')->toString() ?: null;
        $userList = $this->service->getList($search);

        return view('users.index', compact('userList', 'search'));
    }

    public function create(): View
    {
        $roles = [
            'super_admin' => 'Super Admin',
            'pengurus_inti' => 'Pengurus Inti',
            'pengurus_harian' => 'Pengurus Harian'
        ];

        return view('users.create', compact('roles'));
    }

    public function store(UserStoreRequest $request): RedirectResponse
    {
        try {
            $this->service->store($request->validated());
        } catch (\Exception $e) {
            return back()
                ->withInput()
                ->withErrors(['general' => $e->getMessage()]);
        }

        return redirect()
            ->route('users.index')
            ->with('success', 'User berhasil ditambahkan.');
    }

    public function edit(User $user): View
    {
        $roles = [
            'super_admin' => 'Super Admin',
            'pengurus_inti' => 'Pengurus Inti',
            'pengurus_harian' => 'Pengurus Harian'
        ];
        $currentRole = $user->roles->first()?->name ?? '';

        return view('users.edit', compact('user', 'roles', 'currentRole'));
    }

    public function update(UserUpdateRequest $request, User $user): RedirectResponse
    {
        try {
            $this->service->update($user, $request->validated());
        } catch (\Exception $e) {
            return back()
                ->withInput()
                ->withErrors(['general' => $e->getMessage()]);
        }

        return redirect()
            ->route('users.index')
            ->with('success', 'User berhasil diperbarui.');
    }

    public function destroy(User $user): RedirectResponse
    {
        try {
            $this->service->destroy($user);
        } catch (\Exception $e) {
            return back()->withErrors(['general' => $e->getMessage()]);
        }

        return redirect()
            ->route('users.index')
            ->with('success', 'User berhasil dihapus.');
    }
}
