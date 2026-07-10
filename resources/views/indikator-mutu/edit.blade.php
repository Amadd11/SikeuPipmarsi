<x-app-layout>
    <x-slot:title>Indikator Mutu</x-slot>

    {{-- Header --}}
    <div class="border-b border-gray-100 pb-6">
        <h2 class="text-lg font-semibold text-gray-900">Edit Indikator Mutu</h2>
        <p class="text-sm text-gray-500 mt-1">
            Perbarui data indikator
            <span class="font-medium text-gray-700">{{ $indikator->kode }} — {{ Str::limit($indikator->nama, 60) }}</span>
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
            <form method="POST" action="{{ route('indikator-mutu.update', $indikator->id) }}"
                class="bg-white rounded-3xl border border-gray-100 shadow-sm p-6 space-y-5">
                @csrf
                @method('PUT')

                {{-- Kode & Bidang Kerja --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">

                    {{-- Kode --}}
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-1.5">
                            Kode <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="kode"
                            value="{{ old('kode', $indikator->kode) }}"
                            placeholder="Contoh: B1.1"
                            maxlength="10"
                            class="w-full px-4 py-2.5 text-sm rounded-xl border border-gray-200 focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none @error('kode') border-red-300 @enderror">
                        @error('kode')
                            <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                        @enderror
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
                                <option value="{{ $bidang->id }}" @selected(old('bidang_kerja_id', $indikator->bidang_kerja_id) == $bidang->id)>
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
                    <input type="text" name="nama"
                        value="{{ old('nama', $indikator->nama) }}"
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
                        <input type="text" name="target"
                            value="{{ old('target', $indikator->target) }}"
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
                        <input type="text" name="periode"
                            value="{{ old('periode', $indikator->periode) }}"
                            placeholder="Contoh: Tahunan, Per Kegiatan, Bulanan"
                            class="w-full px-4 py-2.5 text-sm rounded-xl border border-gray-200 focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none @error('periode') border-red-300 @enderror">
                        @error('periode')
                            <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                </div>

                {{-- Status --}}
                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1.5">Status</label>
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
                                    {{ old('status', $indikator->status) === $value ? 'checked' : '' }}>
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
                        class="w-full px-4 py-2.5 text-sm rounded-xl border border-gray-200 focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none resize-none @error('catatan') border-red-300 @enderror">{{ old('catatan', $indikator->catatan) }}</textarea>
                    @error('catatan')
                        <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Actions --}}
                <div class="flex justify-between items-center gap-3 pt-2 border-t border-gray-100">

                    <button type="button" onclick="document.getElementById('delete-form').submit()"
                        class="inline-flex items-center gap-2 text-red-500 text-sm font-medium hover:text-red-600 transition">
                        <span class="material-symbols-outlined text-[18px]">delete</span>
                        Hapus
                    </button>

                    <div class="flex gap-3">
                        <a href="{{ route('indikator-mutu.index') }}"
                            class="px-6 py-2.5 rounded-full border border-gray-200 text-gray-600 text-sm font-medium hover:bg-gray-100 hover:border-gray-300 transition-all duration-200 active:scale-95">
                            Batal
                        </a>
                        <button type="submit"
                            class="inline-flex items-center gap-2 bg-gold text-gray-900 px-6 py-2.5 rounded-full text-sm font-semibold shadow hover:bg-gold-dark hover:shadow-md transition-all duration-200 active:scale-95">
                            <span class="material-symbols-outlined text-[18px]">save</span>
                            Simpan Perubahan
                        </button>
                    </div>

                </div>

            </form>

            {{-- Hidden Delete Form --}}
            <form id="delete-form" method="POST" action="{{ route('indikator-mutu.destroy', $indikator->id) }}"
                class="hidden"
                onsubmit="return confirm('Hapus indikator {{ addslashes($indikator->kode) }}? Tindakan ini tidak dapat dibatalkan.')">
                @csrf
                @method('DELETE')
            </form>
        </div>

        {{-- Side Panel --}}
        <div class="lg:col-span-1 space-y-4">

            {{-- Info Indikator Saat Ini --}}
            <div class="bg-white rounded-3xl border border-gray-100 shadow-sm p-5">
                <h3 class="text-sm font-semibold text-gray-900 mb-4">Info Saat Ini</h3>

                <div class="space-y-3">

                    <div class="flex items-start justify-between gap-3">
                        <span class="text-xs text-gray-500 shrink-0">Kode</span>
                        <span class="text-xs font-bold text-indigo-700 bg-indigo-50 px-2 py-0.5 rounded-md border border-indigo-100">
                            {{ $indikator->kode }}
                        </span>
                    </div>

                    <div class="flex items-start justify-between gap-3">
                        <span class="text-xs text-gray-500 shrink-0">Bidang</span>
                        <span class="text-xs font-medium text-gray-700 text-right">
                            {{ $indikator->bidangKerja->nama ?? '-' }}
                        </span>
                    </div>

                    <div class="flex items-start justify-between gap-3">
                        <span class="text-xs text-gray-500 shrink-0">Status</span>
                        @php
                            $statusBadge = [
                                'tercapai'       => 'bg-emerald-100 text-emerald-700',
                                'proses'         => 'bg-amber-100 text-amber-700',
                                'tidak tercapai' => 'bg-red-100 text-red-700',
                                'belum'          => 'bg-gray-100 text-gray-600',
                            ];
                            $statusLabel = [
                                'tercapai'       => 'Tercapai',
                                'proses'         => 'Proses',
                                'tidak tercapai' => 'Tidak Tercapai',
                                'belum'          => 'Belum',
                            ];
                            $badge = $statusBadge[$indikator->status] ?? 'bg-gray-100 text-gray-600';
                            $lbl   = $statusLabel[$indikator->status]  ?? ucfirst($indikator->status);
                        @endphp
                        <span class="inline-flex px-2 py-0.5 rounded-md {{ $badge }} text-[10px] font-semibold">
                            {{ $lbl }}
                        </span>
                    </div>

                    <div class="flex items-start justify-between gap-3">
                        <span class="text-xs text-gray-500 shrink-0">Target</span>
                        <span class="text-xs font-medium text-gray-700">{{ $indikator->target }}</span>
                    </div>

                    @if ($indikator->periode)
                        <div class="flex items-start justify-between gap-3">
                            <span class="text-xs text-gray-500 shrink-0">Periode</span>
                            <span class="text-xs text-gray-700">{{ $indikator->periode }}</span>
                        </div>
                    @endif

                    <div class="pt-2 border-t border-gray-100 text-[10px] text-gray-400">
                        Dibuat: {{ $indikator->created_at->format('d M Y') }} ·
                        Diperbarui: {{ $indikator->updated_at->format('d M Y') }}
                    </div>

                </div>
            </div>

            {{-- Warning --}}
            <div class="bg-amber-50 border border-amber-100 rounded-3xl p-5 space-y-2">
                <div class="flex items-center gap-2 text-amber-700">
                    <span class="material-symbols-outlined text-[18px]">warning</span>
                    <h3 class="text-sm font-semibold">Perhatian</h3>
                </div>
                <p class="text-xs text-gray-600">
                    Mengubah kode indikator dapat mempengaruhi referensi di data pengeluaran
                    dan capaian yang sudah terkait.
                </p>
            </div>

        </div>

    </div>

</x-app-layout>
