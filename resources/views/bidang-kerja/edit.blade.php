<x-app-layout>
    <x-slot:title>Edit Bidang Kerja</x-slot>

    {{-- Header --}}
    <div class="border-b border-gray-100 pb-6">
        <a href="{{ route('bidang-kerja.index') }}"
            class="inline-flex items-center gap-1 text-xs text-gray-400 hover:text-primary transition mb-3">
            <span class="material-symbols-outlined text-[16px]">arrow_back</span>
            Kembali ke Manajemen Bidang Kerja
        </a>

        <h2 class="text-lg font-semibold text-gray-900">Edit Bidang Kerja: {{ $bidangKerja->nama }}</h2>
        <p class="text-sm text-gray-500 mt-1">
            Perbarui data bidang kerja sesuai kebutuhan
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
            <form method="POST" action="{{ route('bidang-kerja.update', $bidangKerja->id) }}"
                class="bg-white rounded-3xl border border-gray-100 shadow-sm p-6 space-y-5">
                @csrf
                @method('PUT')

                {{-- Kode & Urutan --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-1.5">
                            Kode <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="kode" value="{{ old('kode', $bidangKerja->kode) }}"
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
                        <input type="number" name="urutan" value="{{ old('urutan', $bidangKerja->urutan) }}"
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
                    <input type="text" name="nama" value="{{ old('nama', $bidangKerja->nama) }}"
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
                            value="{{ old('warna_hex', $bidangKerja->warna_hex ?? '#6366f1') }}"
                            class="w-10 h-10 rounded-xl border border-gray-200 cursor-pointer p-0.5 @error('warna_hex') border-red-300 @enderror">
                        <input type="text" id="warna_hex_text" name="warna_hex_display"
                            value="{{ old('warna_hex', $bidangKerja->warna_hex ?? '#6366f1') }}"
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
                    <input type="hidden" name="warna_hex" id="warna_hex_hidden"
                        value="{{ old('warna_hex', $bidangKerja->warna_hex ?? '#6366f1') }}">
                </div>

                {{-- Deskripsi --}}
                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1.5">
                        Deskripsi (Opsional)
                    </label>
                    <textarea name="deskripsi" rows="3"
                        placeholder="Uraian singkat mengenai bidang kerja ini..."
                        class="w-full px-4 py-2.5 text-sm rounded-xl border border-gray-200 focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none resize-none @error('deskripsi') border-red-300 @enderror">{{ old('deskripsi', $bidangKerja->deskripsi) }}</textarea>
                    @error('deskripsi')
                        <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Actions --}}
                <div class="flex justify-end gap-3 pt-2 border-t border-gray-100">
                    <a href="{{ route('bidang-kerja.index') }}"
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

            {{-- Bidang Kerja Info Card --}}
            <div class="bg-white border border-gray-100 rounded-3xl p-5 shadow-sm">
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-12 h-12 rounded-full flex items-center justify-center shrink-0"
                        style="background-color: {{ $bidangKerja->warna_hex ? $bidangKerja->warna_hex . '22' : '#6366f122' }};">
                        <span class="font-bold text-lg"
                            style="color: {{ $bidangKerja->warna_hex ?? '#6366f1' }};">
                            {{ strtoupper(substr($bidangKerja->kode, 0, 2)) }}
                        </span>
                    </div>
                    <div>
                        <p class="font-semibold text-gray-800 text-sm">{{ $bidangKerja->nama }}</p>
                        <p class="text-xs text-gray-500 font-mono">{{ $bidangKerja->kode }}</p>
                    </div>
                </div>
                <div class="text-xs text-gray-500 space-y-1.5 border-t border-gray-100 pt-3">
                    <p>
                        <span class="font-medium text-gray-700">Dibuat:</span>
                        <span class="ml-1">{{ $bidangKerja->created_at->format('d M Y') }}</span>
                    </p>
                    <p>
                        <span class="font-medium text-gray-700">Diperbarui:</span>
                        <span class="ml-1">{{ $bidangKerja->updated_at->format('d M Y') }}</span>
                    </p>
                    @if ($bidangKerja->warna_hex)
                        <div class="flex items-center gap-2 mt-1">
                            <span class="font-medium text-gray-700">Warna:</span>
                            <div class="flex items-center gap-1.5">
                                <div class="w-4 h-4 rounded border border-gray-200"
                                    style="background-color: {{ $bidangKerja->warna_hex }};"></div>
                                <span class="font-mono text-gray-500">{{ $bidangKerja->warna_hex }}</span>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <div class="bg-amber-50 border border-amber-100 rounded-3xl p-5 space-y-3">
                <div class="flex items-center gap-2 text-amber-600">
                    <span class="material-symbols-outlined text-[18px]">warning</span>
                    <h3 class="text-sm font-semibold">Perhatian</h3>
                </div>
                <ul class="text-xs text-gray-600 space-y-1.5 list-disc pl-4">
                    <li>Perubahan nama atau kode akan mempengaruhi tampilan di seluruh sistem.</li>
                    <li>Bidang kerja yang telah digunakan pada data rencana atau transaksi <strong>tidak dapat dihapus</strong>.</li>
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
