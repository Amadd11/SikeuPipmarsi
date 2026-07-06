<x-app-layout>
    <x-slot:title>Manajemen User</x-slot>

    {{-- Flash Messages --}}
    @if (session('success'))
        <div class="mb-4 px-4 py-3 rounded-xl bg-green-50 border border-green-100 text-green-700 text-xs flex items-center gap-2">
            <span class="material-symbols-outlined text-[16px]">check_circle</span>
            {{ session('success') }}
        </div>
    @endif

    @if ($errors->has('general'))
        <div class="mb-4 px-4 py-3 rounded-xl bg-red-50 border border-red-100 text-red-600 text-xs flex items-center gap-2">
            <span class="material-symbols-outlined text-[16px]">error</span>
            {{ $errors->first('general') }}
        </div>
    @endif

    {{-- Page Header --}}
    <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4 mb-5">
        <div>
            <p class="text-xs text-gray-500 mt-1">
                Kelola akun pengguna dan hak akses sistem
            </p>
        </div>

        <a href="{{ route('users.create') }}"
            class="inline-flex items-center gap-2 bg-gold text-gray-900 px-4 py-2 rounded-full text-xs font-semibold shadow hover:bg-gold-dark hover:shadow-md transition-all duration-200 active:scale-95">
            <span class="material-symbols-outlined text-[16px]">person_add</span>
            Tambah User
        </a>
    </div>

    {{-- Search --}}
    <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-4 mb-5">
        <form method="GET" action="{{ route('users.index') }}" class="flex items-end gap-3">
            <div class="flex-1">
                <label class="block text-[10px] font-semibold text-gray-500 uppercase tracking-wider mb-1.5">Cari User</label>
                <input type="text" name="search" value="{{ $search ?? '' }}"
                    placeholder="Nama atau email..."
                    class="w-full px-3 py-2 text-xs rounded-xl border border-gray-200 focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none">
            </div>
            <div class="flex gap-2">
                <button type="submit"
                    class="inline-flex items-center gap-1.5 bg-gold text-gray-900 px-4 py-2 rounded-full text-xs font-semibold shadow-sm hover:bg-gold-dark hover:shadow transition-all duration-200 active:scale-95">
                    <span class="material-symbols-outlined text-[16px]" style="font-variation-settings: 'FILL' 1">search</span>
                    Cari
                </button>
                @if ($search)
                    <a href="{{ route('users.index') }}"
                        class="inline-flex items-center gap-1.5 bg-white text-gray-500 px-4 py-2 rounded-full text-xs font-medium border border-gray-200 hover:bg-gray-50 hover:border-gray-300 transition-all duration-200 active:scale-95">
                        <span class="material-symbols-outlined text-[15px]">close</span>
                        Reset
                    </a>
                @endif
            </div>
        </form>
    </div>

    {{-- Data Table --}}
    <div class="bg-white rounded-2xl border border-gray-200 overflow-hidden shadow-sm">

        {{-- Card Header --}}
        <div class="p-5 border-b border-gray-100">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="font-semibold text-gray-900 text-sm">Daftar Pengguna</h2>
                    <p class="text-xs text-gray-500 mt-0.5">
                        Menampilkan {{ $userList->total() }} user terdaftar.
                    </p>
                </div>
                <span class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-indigo-50 border border-indigo-100 text-indigo-600 text-[10px] font-semibold rounded-lg">
                    <span class="material-symbols-outlined text-[13px]">shield</span>
                    Super Admin Only
                </span>
            </div>
        </div>

        {{-- Table --}}
        <div class="overflow-x-auto">
            <table class="w-full text-xs">
                <thead>
                    <tr class="bg-gray-50 text-[10px] uppercase tracking-widest text-gray-500 font-semibold border-b border-gray-100">
                        <th class="px-4 py-2.5 text-center w-10">#</th>
                        <th class="px-4 py-2.5 text-left">Nama</th>
                        <th class="px-4 py-2.5 text-left">Email</th>
                        <th class="px-4 py-2.5 text-center">Role</th>
                        <th class="px-4 py-2.5 text-center">Dibuat</th>
                        <th class="px-4 py-2.5 text-center">Aksi</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-gray-100 text-gray-700">

                    @forelse ($userList as $item)
                        <tr class="hover:bg-gray-50/70 transition group">

                            {{-- No --}}
                            <td class="px-4 py-3 text-center text-gray-400 font-medium align-middle">
                                {{ $loop->iteration + ($userList->currentPage() - 1) * $userList->perPage() }}
                            </td>

                            {{-- Nama --}}
                            <td class="px-4 py-3 align-middle">
                                <div class="flex items-center gap-2.5">
                                    <div class="w-7 h-7 rounded-full bg-primary/10 flex items-center justify-center shrink-0">
                                        <span class="text-primary font-bold text-[11px]">{{ strtoupper(substr($item->name, 0, 1)) }}</span>
                                    </div>
                                    <span class="font-medium text-gray-900">{{ $item->name }}</span>
                                    @if ($item->id === auth()->id())
                                        <span class="text-[9px] bg-amber-100 text-amber-700 px-1.5 py-0.5 rounded-full font-semibold">Anda</span>
                                    @endif
                                </div>
                            </td>

                            {{-- Email --}}
                            <td class="px-4 py-3 align-middle text-gray-500">
                                {{ $item->email }}
                            </td>

                            {{-- Role --}}
                            <td class="px-4 py-3 text-center align-middle">
                                @php $role = $item->roles->first()?->name; @endphp
                                @if ($role === 'super_admin')
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 bg-indigo-50 border border-indigo-100 text-indigo-700 text-[10px] font-bold rounded-lg">
                                        <span class="material-symbols-outlined text-[12px]">admin_panel_settings</span>
                                        Super Admin
                                    </span>
                                @elseif ($role === 'bendahara')
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 bg-emerald-50 border border-emerald-100 text-emerald-700 text-[10px] font-bold rounded-lg">
                                        <span class="material-symbols-outlined text-[12px]">account_balance_wallet</span>
                                        Bendahara
                                    </span>
                                @else
                                    <span class="text-gray-300">—</span>
                                @endif
                            </td>

                            {{-- Dibuat --}}
                            <td class="px-4 py-3 text-center align-middle text-gray-500 whitespace-nowrap">
                                {{ $item->created_at->format('d M Y') }}
                            </td>

                            {{-- Aksi --}}
                            <td class="px-4 py-3 align-middle">
                                <div class="flex items-center justify-center gap-1.5">
                                    <a href="{{ route('users.edit', $item->id) }}"
                                        class="w-8 h-8 rounded-full bg-gray-50 border border-gray-200 flex items-center justify-center text-gray-500 hover:bg-indigo-50 hover:border-indigo-300 hover:text-indigo-600 transition-all duration-200"
                                        title="Edit">
                                        <span class="material-symbols-outlined text-[16px]">edit</span>
                                    </a>
                                    @if ($item->id !== auth()->id())
                                        <button
                                            @click="$dispatch('open-delete-modal', {
                                                action: '{{ route('users.destroy', $item->id) }}',
                                                message: 'Yakin ingin menghapus user {{ addslashes($item->name) }}? Tindakan ini tidak dapat dibatalkan.'
                                            })"
                                            class="w-8 h-8 rounded-full bg-red-50/50 border border-red-200 flex items-center justify-center text-red-400 hover:bg-red-100 hover:border-red-400 hover:text-red-600 transition-all duration-200"
                                            title="Hapus">
                                            <span class="material-symbols-outlined text-[16px]">delete</span>
                                        </button>
                                    @else
                                        <div class="w-7 h-7" title="Tidak dapat menghapus akun sendiri"></div>
                                    @endif
                                </div>
                            </td>

                        </tr>

                    @empty
                        <tr>
                            <td colspan="6" class="py-14 text-center">
                                <div class="flex flex-col items-center gap-2">
                                    <span class="material-symbols-outlined text-4xl text-gray-300">group</span>
                                    <h3 class="font-medium text-gray-700 text-sm">Belum ada user</h3>
                                    <p class="text-xs text-gray-500">Tambahkan user pertama untuk sistem ini.</p>
                                    <a href="{{ route('users.create') }}"
                                        class="mt-3 inline-flex items-center gap-2 bg-gold text-gray-900 px-5 py-2 rounded-full text-xs font-semibold shadow hover:bg-gold-dark hover:shadow-md transition-all duration-200 active:scale-95">
                                        <span class="material-symbols-outlined text-[16px]">person_add</span>
                                        Tambah Sekarang
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforelse

                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @if ($userList->hasPages())
            <div class="px-5 py-3 border-t border-gray-100 text-xs bg-gray-50/30">
                {{ $userList->appends(request()->query())->links() }}
            </div>
        @endif

    </div>

    <x-modal-delete />

</x-app-layout>
