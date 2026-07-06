<x-app-layout>
    <x-slot:title>Tambah User</x-slot>

    {{-- Header --}}
    <div class="border-b border-gray-100 pb-6">
        <a href="{{ route('users.index') }}"
            class="inline-flex items-center gap-1 text-xs text-gray-400 hover:text-primary transition mb-3">
            <span class="material-symbols-outlined text-[16px]">arrow_back</span>
            Kembali ke Manajemen User
        </a>

        <h2 class="text-lg font-semibold text-gray-900">Tambah User Baru</h2>
        <p class="text-sm text-gray-500 mt-1">
            Isi data di bawah untuk membuat akun pengguna baru
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
            <form method="POST" action="{{ route('users.store') }}"
                class="bg-white rounded-3xl border border-gray-100 shadow-sm p-6 space-y-5">
                @csrf

                {{-- Nama --}}
                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1.5">
                        Nama Lengkap <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="name" value="{{ old('name') }}"
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
                    <input type="email" name="email" value="{{ old('email') }}"
                        placeholder="Contoh: budi@pipmarsi.id"
                        class="w-full px-4 py-2.5 text-sm rounded-xl border border-gray-200 focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none @error('email') border-red-300 @enderror">
                    @error('email')
                        <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Password & Confirm --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-1.5">
                            Password <span class="text-red-500">*</span>
                        </label>
                        <input type="password" name="password"
                            placeholder="Min. 8 karakter"
                            class="w-full px-4 py-2.5 text-sm rounded-xl border border-gray-200 focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none @error('password') border-red-300 @enderror">
                        @error('password')
                            <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-1.5">
                            Konfirmasi Password <span class="text-red-500">*</span>
                        </label>
                        <input type="password" name="password_confirmation"
                            placeholder="Ulangi password"
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
                            <option value="{{ $value }}" {{ old('role') === $value ? 'selected' : '' }}>
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
                        class="px-6 py-2.5 rounded-full border border-gray-200 text-gray-600 text-sm font-medium hover:bg-gray-100 hover:border-gray-300 transition-all duration-200 active:scale-95">
                        Batal
                    </a>
                    <button type="submit"
                        class="inline-flex items-center gap-2 bg-gold text-gray-900 px-6 py-2.5 rounded-full text-sm font-semibold shadow hover:bg-gold-dark hover:shadow-md transition-all duration-200 active:scale-95">
                        <span class="material-symbols-outlined text-[18px]">person_add</span>
                        Simpan User
                    </button>
                </div>

            </form>
        </div>

        {{-- Side Info --}}
        <div class="lg:col-span-1 space-y-4">

            <div class="bg-primary/5 border border-primary/10 rounded-3xl p-5 space-y-3">
                <div class="flex items-center gap-2 text-primary">
                    <span class="material-symbols-outlined text-[18px]">info</span>
                    <h3 class="text-sm font-semibold">Informasi Role</h3>
                </div>
                <ul class="text-xs text-gray-600 space-y-3">
                    <li class="flex items-start gap-2">
                        <span class="material-symbols-outlined text-[14px] text-indigo-500 mt-0.5 shrink-0">admin_panel_settings</span>
                        <span><strong>Super Admin</strong> — akses penuh termasuk manajemen user, semua menu.</span>
                    </li>
                    <li class="flex items-start gap-2">
                        <span class="material-symbols-outlined text-[14px] text-emerald-500 mt-0.5 shrink-0">account_balance_wallet</span>
                        <span><strong>Bendahara</strong> — akses ke modul keuangan, indikator, dan laporan.</span>
                    </li>
                </ul>
            </div>

            <div class="bg-amber-50 border border-amber-100 rounded-3xl p-5 space-y-3">
                <div class="flex items-center gap-2 text-amber-600">
                    <span class="material-symbols-outlined text-[18px]">lock</span>
                    <h3 class="text-sm font-semibold">Keamanan Password</h3>
                </div>
                <ul class="text-xs text-gray-600 space-y-1.5 list-disc pl-4">
                    <li>Password minimal <strong>8 karakter</strong>.</li>
                    <li>Gunakan kombinasi huruf, angka, dan simbol.</li>
                    <li>Informasikan password ke user secara langsung.</li>
                </ul>
            </div>

        </div>

    </div>

</x-app-layout>
