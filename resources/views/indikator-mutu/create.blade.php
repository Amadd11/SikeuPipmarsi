<x-app-layout>
    <x-slot:title>Indikator Mutu</x-slot>

    {{-- Header --}}
    <div class="border-b border-gray-100 pb-6">
        <h2 class="text-lg font-semibold text-gray-900">Tambah Indikator Mutu Baru</h2>
        <p class="text-sm text-gray-500 mt-1">
            Tambahkan indikator mutu ke bidang kerja yang sesuai
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
            <form method="POST" action="{{ route('indikator-mutu.store') }}"
                class="bg-white rounded-3xl border border-gray-100 shadow-sm p-6 space-y-5">
                @csrf

                {{-- Kode & Bidang Kerja --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">

                    {{-- Kode --}}
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-1.5">
                            Kode <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="kode" value="{{ old('kode') }}"
                            placeholder="Contoh: B1.1"
                            maxlength="10"
                            class="w-full px-4 py-2.5 text-sm rounded-xl border border-gray-200 focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none @error('kode') border-red-300 @enderror">
                        @error('kode')
                            <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                        @enderror
                        <p class="text-[10px] text-gray-400 mt-1">Format: [KodeBidang].[Nomor], maks. 10 karakter.</p>
                    </div>

                    {{-- Bidang Kerja --}}
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-1.5">
                            Bidang Kerja <span class="text-red-500">*</span>
                        </label>
                        <select name="bidang_kerja_id"
                            class="w-full px-4 py-2.5 text-sm rounded-xl border border-gray-200 focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none bg-white @error('bidang_kerja_id') border-red-300 @enderror">
                            <option value="">Pilih bidang kerja</option>
                            @foreach ($bidangKerjaList as $bidang)
                                <option value="{{ $bidang->id }}" @selected(old('bidang_kerja_id') == $bidang->id)>
                                    {{ $bidang->nama }}
                                </option>
                            @endforeach
                        </select>
                        @error('bidang_kerja_id')
                            <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                </div>

                {{-- Nama Indikator --}}
                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1.5">
                        Nama Indikator <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="nama" value="{{ old('nama') }}"
                        placeholder="Contoh: Tingkat kehadiran rapat pengurus ≥ 80%"
                        class="w-full px-4 py-2.5 text-sm rounded-xl border border-gray-200 focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none @error('nama') border-red-300 @enderror">
                    @error('nama')
                        <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Target & Periode --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">

                    {{-- Target --}}
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-1.5">
                            Target <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="target" value="{{ old('target') }}"
                            placeholder="Contoh: ≥ 80%, Min. 2, Terlaksana"
                            class="w-full px-4 py-2.5 text-sm rounded-xl border border-gray-200 focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none @error('target') border-red-300 @enderror">
                        @error('target')
                            <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Periode --}}
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-1.5">
                            Periode Evaluasi
                            <span class="text-gray-400 font-normal">(opsional)</span>
                        </label>
                        <input type="text" name="periode" value="{{ old('periode') }}"
                            placeholder="Contoh: Tahunan, Per Kegiatan, Bulanan"
                            class="w-full px-4 py-2.5 text-sm rounded-xl border border-gray-200 focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none @error('periode') border-red-300 @enderror">
                        @error('periode')
                            <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                </div>

                {{-- Status --}}
                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1.5">Status Awal</label>
                    <div class="grid grid-cols-2 sm:grid-cols-4 gap-2">
                        @foreach ($statusOptions as $value => $label)
                            @php
                                $colors = [
                                    'belum'          => 'border-gray-200 text-gray-600 peer-checked:border-gray-400 peer-checked:bg-gray-50 peer-checked:text-gray-800',
                                    'proses'         => 'border-amber-200 text-amber-700 peer-checked:border-amber-400 peer-checked:bg-amber-50 peer-checked:text-amber-800',
                                    'tercapai'       => 'border-emerald-200 text-emerald-700 peer-checked:border-emerald-400 peer-checked:bg-emerald-50 peer-checked:text-emerald-800',
                                    'tidak tercapai' => 'border-red-200 text-red-600 peer-checked:border-red-400 peer-checked:bg-red-50 peer-checked:text-red-700',
                                ];
                                $color = $colors[$value] ?? 'border-gray-200 text-gray-600';
                            @endphp
                            <label class="relative cursor-pointer">
                                <input type="radio" name="status" value="{{ $value }}"
                                    class="sr-only peer"
                                    {{ old('status', 'belum') === $value ? 'checked' : '' }}>
                                <div class="flex items-center justify-center gap-1.5 px-3 py-2 rounded-xl border text-[11px] font-medium text-center transition-all {{ $color }}">
                                    {{ $label }}
                                </div>
                            </label>
                        @endforeach
                    </div>
                    @error('status')
                        <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Catatan --}}
                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1.5">
                        Catatan
                        <span class="text-gray-400 font-normal">(opsional)</span>
                    </label>
                    <textarea name="catatan" rows="3"
                        placeholder="Catatan tambahan atau keterangan tentang indikator ini..."
                        class="w-full px-4 py-2.5 text-sm rounded-xl border border-gray-200 focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none resize-none @error('catatan') border-red-300 @enderror">{{ old('catatan') }}</textarea>
                    @error('catatan')
                        <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Actions --}}
                <div class="flex justify-end gap-3 pt-2 border-t border-gray-100">
                    <a href="{{ route('indikator-mutu.index') }}"
                        class="px-6 py-2.5 rounded-full border border-gray-200 text-gray-600 text-sm font-medium hover:bg-gray-100 hover:border-gray-300 transition-all duration-200 active:scale-95">
                        Batal
                    </a>
                    <button type="submit"
                        class="inline-flex items-center gap-2 bg-gold text-gray-900 px-6 py-2.5 rounded-full text-sm font-semibold shadow hover:bg-gold-dark hover:shadow-md transition-all duration-200 active:scale-95">
                        <span class="material-symbols-outlined text-[18px]">save</span>
                        Simpan Indikator
                    </button>
                </div>

            </form>
        </div>

        {{-- Side Info --}}
        <div class="lg:col-span-1 space-y-4">

            <div class="bg-primary/5 border border-primary/10 rounded-3xl p-5 space-y-3">
                <div class="flex items-center gap-2 text-primary">
                    <span class="material-symbols-outlined text-[18px]">info</span>
                    <h3 class="text-sm font-semibold">Tips Pengisian</h3>
                </div>
                <ul class="text-xs text-gray-600 space-y-2 list-disc pl-4">
                    <li>
                        <strong>Kode</strong> harus unik dan mengikuti format bidang, misalnya
                        <code class="bg-gray-100 px-1 rounded text-[10px]">B1.3</code> untuk Bidang 1 indikator ke-3.
                    </li>
                    <li>Nama indikator harus spesifik, sertakan angka target jika ada.</li>
                    <li>
                        <strong>Periode</strong> menunjukkan frekuensi evaluasi: Tahunan, Semesteran,
                        Per Kegiatan, dll.
                    </li>
                    <li>
                        Status dapat diperbarui kapan saja melalui tombol <strong>Edit</strong> di halaman daftar.
                    </li>
                </ul>
            </div>

            {{-- Status Reference --}}
            <div class="bg-white border border-gray-100 rounded-3xl p-5 space-y-3 shadow-sm">
                <div class="flex items-center gap-2 text-gray-600">
                    <span class="material-symbols-outlined text-[18px]">label</span>
                    <h3 class="text-sm font-semibold">Panduan Status</h3>
                </div>
                <ul class="text-xs space-y-2">
                    <li class="flex items-start gap-2">
                        <span class="mt-0.5 w-2 h-2 rounded-full bg-gray-400 shrink-0"></span>
                        <span><strong class="text-gray-700">Belum</strong> — Indikator belum mulai dievaluasi.</span>
                    </li>
                    <li class="flex items-start gap-2">
                        <span class="mt-0.5 w-2 h-2 rounded-full bg-amber-400 shrink-0"></span>
                        <span><strong class="text-amber-700">Proses</strong> — Sedang dalam tahap pelaksanaan.</span>
                    </li>
                    <li class="flex items-start gap-2">
                        <span class="mt-0.5 w-2 h-2 rounded-full bg-emerald-500 shrink-0"></span>
                        <span><strong class="text-emerald-700">Tercapai</strong> — Target telah dipenuhi.</span>
                    </li>
                    <li class="flex items-start gap-2">
                        <span class="mt-0.5 w-2 h-2 rounded-full bg-red-500 shrink-0"></span>
                        <span><strong class="text-red-700">Tidak Tercapai</strong> — Target tidak berhasil dicapai.</span>
                    </li>
                </ul>
            </div>

        </div>

    </div>

</x-app-layout>
