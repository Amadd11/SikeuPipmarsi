<x-app-layout>
    <x-slot:title>Manajemen Bidang Kerja</x-slot>

    {{-- Header --}}
    <div class="border-b border-gray-100 pb-6">
        <h2 class="text-lg font-semibold text-gray-900">Tambah Bidang Kerja Baru</h2>
        <p class="text-sm text-gray-500 mt-1">
            Isi data di bawah untuk menambahkan bidang kerja baru ke dalam sistem
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
            <form method="POST" action="{{ route('bidang-kerja.store') }}"
                class="bg-white rounded-3xl border border-gray-100 shadow-sm p-6 space-y-5">
                @csrf

                {{-- Kode & Urutan --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-1.5">
                            Kode <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="kode" value="{{ old('kode') }}"
                            placeholder="Contoh: BK01"
                            maxlength="5"
                            style="text-transform: uppercase;"
                            class="w-full px-4 py-2.5 text-sm rounded-xl border border-gray-200 focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none font-mono @error('kode') border-red-300 @enderror">
                        @error('kode')
                            <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                        @enderror
                        <p class="text-[10px] text-gray-400 mt-1">Maks. 5 karakter, harus unik.</p>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-1.5">
                            Urutan
                        </label>
                        <input type="number" name="urutan" value="{{ old('urutan', 0) }}"
                            min="0" max="127"
                            placeholder="0"
                            class="w-full px-4 py-2.5 text-sm rounded-xl border border-gray-200 focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none @error('urutan') border-red-300 @enderror">
                        @error('urutan')
                            <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                        @enderror
                        <p class="text-[10px] text-gray-400 mt-1">Untuk mengurutkan tampilan (0 = default).</p>
                    </div>
                </div>

                {{-- Nama --}}
                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1.5">
                        Nama Bidang Kerja <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="nama" value="{{ old('nama') }}"
                        placeholder="Contoh: Bidang Administrasi"
                        maxlength="100"
                        class="w-full px-4 py-2.5 text-sm rounded-xl border border-gray-200 focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none @error('nama') border-red-300 @enderror">
                    @error('nama')
                        <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Warna --}}
                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1.5">
                        Warna (Opsional)
                    </label>
                    <div class="flex items-center gap-3">
                        <input type="color" id="warna_hex_picker"
                            value="{{ old('warna_hex', '#6366f1') }}"
                            class="w-10 h-10 rounded-xl border border-gray-200 cursor-pointer p-0.5 @error('warna_hex') border-red-300 @enderror">
                        <input type="text" id="warna_hex_text" name="warna_hex_display"
                            value="{{ old('warna_hex', '#6366f1') }}"
                            placeholder="#6366f1"
                            maxlength="7"
                            class="flex-1 px-4 py-2.5 text-sm rounded-xl border border-gray-200 focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none font-mono"
                            readonly>
                    </div>
                    <p class="text-[10px] text-gray-400 mt-1">Pilih warna untuk membedakan bidang kerja secara visual.</p>
                    @error('warna_hex')
                        <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                    @enderror

                    {{-- Hidden input yang benar-benar dikirim --}}
                    <input type="hidden" name="warna_hex" id="warna_hex_hidden" value="{{ old('warna_hex', '#6366f1') }}">
                </div>

                {{-- Deskripsi --}}
                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1.5">
                        Deskripsi (Opsional)
                    </label>
                    <textarea name="deskripsi" rows="3"
                        placeholder="Uraian singkat mengenai bidang kerja ini..."
                        class="w-full px-4 py-2.5 text-sm rounded-xl border border-gray-200 focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none resize-none @error('deskripsi') border-red-300 @enderror">{{ old('deskripsi') }}</textarea>
                    @error('deskripsi')
                        <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

    {{-- Actions --}}
                <div class="flex flex-col-reverse sm:flex-row sm:justify-end gap-3 pt-2 border-t border-gray-100">
                    <a href="{{ route('bidang-kerja.index') }}"
                        class="inline-flex items-center justify-center px-6 py-2.5 rounded-full border border-gray-200 text-gray-600 text-sm font-medium hover:bg-gray-100 hover:border-gray-300 transition-all duration-200 active:scale-95">
                        Batal
                    </a>
                    <button type="submit"
                        class="inline-flex items-center justify-center gap-2 bg-gold text-gray-900 px-6 py-2.5 rounded-full text-sm font-semibold shadow hover:bg-gold-dark hover:shadow-md transition-all duration-200 active:scale-95">
                        <span class="material-symbols-outlined text-[18px]">add_circle</span>
                        Simpan Bidang Kerja
                    </button>
                </div>

            </form>
        </div>

        {{-- Side Info --}}
        <div class="lg:col-span-1 space-y-4">

            <div class="bg-primary/5 border border-primary/10 rounded-3xl p-5 space-y-3">
                <div class="flex items-center gap-2 text-primary">
                    <span class="material-symbols-outlined text-[18px]">info</span>
                    <h3 class="text-sm font-semibold">Informasi Bidang Kerja</h3>
                </div>
                <ul class="text-xs text-gray-600 space-y-3">
                    <li class="flex items-start gap-2">
                        <span class="material-symbols-outlined text-[14px] text-primary mt-0.5 shrink-0">badge</span>
                        <span><strong>Kode</strong> — identifikasi singkat yang unik untuk setiap bidang kerja.</span>
                    </li>
                    <li class="flex items-start gap-2">
                        <span class="material-symbols-outlined text-[14px] text-primary mt-0.5 shrink-0">palette</span>
                        <span><strong>Warna</strong> — digunakan untuk membedakan bidang kerja secara visual di seluruh sistem.</span>
                    </li>
                    <li class="flex items-start gap-2">
                        <span class="material-symbols-outlined text-[14px] text-primary mt-0.5 shrink-0">sort</span>
                        <span><strong>Urutan</strong> — menentukan posisi tampilan, angka lebih kecil ditampilkan lebih awal.</span>
                    </li>
                </ul>
            </div>

            <div class="bg-amber-50 border border-amber-100 rounded-3xl p-5 space-y-3">
                <div class="flex items-center gap-2 text-amber-600">
                    <span class="material-symbols-outlined text-[18px]">warning</span>
                    <h3 class="text-sm font-semibold">Perhatian</h3>
                </div>
                <ul class="text-xs text-gray-600 space-y-1.5 list-disc pl-4">
                    <li>Bidang kerja yang telah digunakan pada data rencana atau transaksi <strong>tidak dapat dihapus</strong>.</li>
                    <li>Kode bersifat <strong>unik</strong> dan tidak dapat sama dengan bidang kerja lain.</li>
                </ul>
            </div>

        </div>

    </div>

    <script>
        const picker = document.getElementById('warna_hex_picker');
        const text   = document.getElementById('warna_hex_text');
        const hidden = document.getElementById('warna_hex_hidden');

        picker.addEventListener('input', function () {
            text.value   = this.value;
            hidden.value = this.value;
        });
    </script>

</x-app-layout>
