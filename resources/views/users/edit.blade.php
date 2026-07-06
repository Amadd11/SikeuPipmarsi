<x-app-layout>
    <x-slot:title>Edit User</x-slot>

    {{-- Header --}}
    <div class="border-b border-gray-100 pb-6">
        <a href="{{ route('users.index') }}"
            class="inline-flex items-center gap-1 text-xs text-gray-400 hover:text-primary transition mb-3">
            <span class="material-symbols-outlined text-[16px]">arrow_back</span>
            Kembali ke Manajemen User
        </a>

        <h2 class="text-lg font-semibold text-gray-900">Edit User: {{ $user->name }}</h2>
        <p class="text-sm text-gray-500 mt-1">
            Perbarui data akun pengguna. Kosongkan password jika tidak ingin mengubah.
        </p>
    </div>

    {{-- Error Summary --}}
    @if ($errors->any())
        <div class="mt-6 px-4 py-3 rounded-xl bg-red-50 border border-red-100 text-red-600 text-xs">
            <p class="font-medium mb-1">Periksa kembali isian Anda:</p>
            <ul class="list-disc pl-4 space-y-0.5">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mt-6">

        {{-- Form --}}
        <div class="lg:col-span-2">
            <form method="POST" action="{{ route('users.update', $user->id) }}"
                class="bg-white rounded-3xl border border-gray-100 shadow-sm p-6 space-y-5">
                @csrf
                @method('PUT')

                {{-- Nama --}}
                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1.5">
                        Nama Lengkap <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="name" value="{{ old('name', $user->name) }}"
                        placeholder="Contoh: Budi Santoso"
                        maxlength="100"
                        class="w-full px-4 py-2.5 text-sm rounded-xl border border-gray-200 focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none @error('name') border-red-300 @enderror">
                    @error('name')
                        <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Email --}}
                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1.5">
                        Email <span class="text-red-500">*</span>
                    </label>
                    <input type="email" name="email" value="{{ old('email', $user->email) }}"
                        placeholder="Contoh: budi@pipmarsi.id"
                        class="w-full px-4 py-2.5 text-sm rounded-xl border border-gray-200 focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none @error('email') border-red-300 @enderror">
                    @error('email')
                        <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Password (opsional) --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-1.5">
                            Password Baru
                            <span class="text-gray-400 font-normal">(opsional)</span>
                        </label>
                        <input type="password" name="password"
                            placeholder="Kosongkan jika tidak ganti"
                            class="w-full px-4 py-2.5 text-sm rounded-xl border border-gray-200 focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none @error('password') border-red-300 @enderror">
                        @error('password')
                            <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-1.5">
                            Konfirmasi Password Baru
                            <span class="text-gray-400 font-normal">(opsional)</span>
                        </label>
                        <input type="password" name="password_confirmation"
                            placeholder="Ulangi password baru"
                            class="w-full px-4 py-2.5 text-sm rounded-xl border border-gray-200 focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none">
                    </div>
                </div>

                {{-- Role --}}
                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1.5">
                        Role <span class="text-red-500">*</span>
                    </label>
                    <select name="role"
                        class="w-full px-4 py-2.5 text-sm rounded-xl border border-gray-200 focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none bg-white @error('role') border-red-300 @enderror">
                        <option value="">-- Pilih Role --</option>
                        @foreach ($roles as $value => $label)
                            <option value="{{ $value }}" {{ old('role', $currentRole) === $value ? 'selected' : '' }}>
                                {{ $label }}
                            </option>
                        @endforeach
                    </select>
                    @error('role')
                        <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Actions --}}
                <div class="flex justify-end gap-3 pt-2 border-t border-gray-100">
                    <a href="{{ route('users.index') }}"
                        class="px-5 py-2.5 rounded-xl border border-gray-200 text-gray-600 text-sm font-medium hover:bg-gray-50 transition">
                        Batal
                    </a>
                    <button type="submit"
                        class="inline-flex items-center gap-2 bg-primary text-white px-5 py-2.5 rounded-xl text-sm font-medium hover:bg-primary-dark transition">
                        <span class="material-symbols-outlined text-[18px]">save</span>
                        Simpan Perubahan
                    </button>
                </div>

            </form>
        </div>

        {{-- Side Info --}}
        <div class="lg:col-span-1 space-y-4">

            {{-- User Info Card --}}
            <div class="bg-white border border-gray-100 rounded-3xl p-5 shadow-sm">
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-12 h-12 rounded-full bg-primary/10 flex items-center justify-center shrink-0">
                        <span class="text-primary font-bold text-lg">{{ strtoupper(substr($user->name, 0, 1)) }}</span>
                    </div>
                    <div>
                        <p class="font-semibold text-gray-800 text-sm">{{ $user->name }}</p>
                        <p class="text-xs text-gray-500">{{ $user->email }}</p>
                    </div>
                </div>
                <div class="text-xs text-gray-500 space-y-1.5 border-t border-gray-100 pt-3">
                    <p>
                        <span class="font-medium text-gray-700">Role saat ini:</span>
                        <span class="ml-1 {{ $currentRole === 'super_admin' ? 'text-indigo-600' : 'text-emerald-600' }} font-semibold">
                            {{ $currentRole === 'super_admin' ? 'Super Admin' : ($currentRole === 'bendahara' ? 'Bendahara' : '—') }}
                        </span>
                    </p>
                    <p>
                        <span class="font-medium text-gray-700">Bergabung:</span>
                        <span class="ml-1">{{ $user->created_at->format('d M Y') }}</span>
                    </p>
                    @if ($user->id === auth()->id())
                        <p class="text-amber-600 font-semibold flex items-center gap-1 mt-2">
                            <span class="material-symbols-outlined text-[14px]">warning</span>
                            Ini adalah akun Anda sendiri
                        </p>
                    @endif
                </div>
            </div>

            <div class="bg-amber-50 border border-amber-100 rounded-3xl p-5 space-y-3">
                <div class="flex items-center gap-2 text-amber-600">
                    <span class="material-symbols-outlined text-[18px]">lock</span>
                    <h3 class="text-sm font-semibold">Ganti Password</h3>
                </div>
                <ul class="text-xs text-gray-600 space-y-1.5 list-disc pl-4">
                    <li>Biarkan kosong jika <strong>tidak ingin mengganti</strong> password.</li>
                    <li>Password baru minimal <strong>8 karakter</strong>.</li>
                    <li>Password lama otomatis tergantikan.</li>
                </ul>
            </div>

        </div>

    </div>

</x-app-layout>
