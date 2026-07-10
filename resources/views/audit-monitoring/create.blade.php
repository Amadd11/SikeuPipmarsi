<x-app-layout>
    <x-slot:title>Audit & Monitoring</x-slot>

    {{-- Header --}}
    <div class="border-b border-gray-100 pb-6">
        <h2 class="text-lg font-semibold text-gray-900">Tambah Data Audit & Monitoring</h2>
        <p class="text-sm text-gray-500 mt-1">
            Catat hasil audit dan monitoring pelaksanaan indikator mutu
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
            <form method="POST" action="{{ route('audit-monitoring.store') }}"
                class="bg-white rounded-3xl border border-gray-100 shadow-sm p-6 space-y-5"
                x-data="auditForm({
                    tahunId: '{{ old('tahun_anggaran_id', $tahunAktif?->id) }}',
                    indikatorId: '{{ old('indikator_mutu_id') }}',
                    existingAudits: {{ Js::from($existingAudits) }},
                    indikatorList: {{ Js::from($indikatorMutuList) }}
                })">
                @csrf

                {{-- Indikator & Tahun Anggaran --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">

                    {{-- Indikator Mutu --}}
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-1.5">
                            Indikator Mutu <span class="text-red-500">*</span>
                        </label>
                        <select name="indikator_mutu_id" x-ref="indikatorSelect"
                            class="w-full px-4 py-2.5 text-sm rounded-xl border border-gray-200 focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none bg-white @error('indikator_mutu_id') border-red-300 @enderror">
                        </select>
                        @error('indikator_mutu_id')
                            <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Tahun Anggaran --}}
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-1.5">
                            Tahun Anggaran <span class="text-red-500">*</span>
                        </label>
                        <select name="tahun_anggaran_id" x-model="tahunId"
                            class="w-full px-4 py-2.5 text-sm rounded-xl border border-gray-200 focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none bg-white @error('tahun_anggaran_id') border-red-300 @enderror">
                            <option value="">Pilih tahun anggaran</option>
                            @foreach ($tahunAnggaranList as $ta)
                                <option value="{{ $ta->id }}"
                                    @selected(old('tahun_anggaran_id', $tahunAktif?->id) == $ta->id)>
                                    {{ $ta->label ?? $ta->tahun }}
                                </option>
                            @endforeach
                        </select>
                        @error('tahun_anggaran_id')
                            <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                </div>

                {{-- Uraian Pelaksanaan --}}
                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1.5">
                        Uraian Pelaksanaan
                        <span class="text-gray-400 font-normal">(opsional)</span>
                    </label>
                    <textarea name="uraian_pelaksanaan" rows="3"
                        placeholder="Deskripsikan kegiatan yang telah dilaksanakan..."
                        class="w-full px-4 py-2.5 text-sm rounded-xl border border-gray-200 focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none resize-none @error('uraian_pelaksanaan') border-red-300 @enderror">{{ old('uraian_pelaksanaan') }}</textarea>
                    @error('uraian_pelaksanaan')
                        <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Kendala & Faktor Pendukung --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">

                    {{-- Kendala --}}
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-1.5">
                            Kendala
                            <span class="text-gray-400 font-normal">(opsional)</span>
                        </label>
                        <textarea name="kendala" rows="3"
                            placeholder="Hambatan atau kendala yang ditemukan..."
                            class="w-full px-4 py-2.5 text-sm rounded-xl border border-gray-200 focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none resize-none @error('kendala') border-red-300 @enderror">{{ old('kendala') }}</textarea>
                        @error('kendala')
                            <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Faktor Pendukung --}}
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-1.5">
                            Faktor Pendukung
                            <span class="text-gray-400 font-normal">(opsional)</span>
                        </label>
                        <textarea name="faktor_pendukung" rows="3"
                            placeholder="Faktor-faktor yang mendukung keberhasilan..."
                            class="w-full px-4 py-2.5 text-sm rounded-xl border border-gray-200 focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none resize-none @error('faktor_pendukung') border-red-300 @enderror">{{ old('faktor_pendukung') }}</textarea>
                        @error('faktor_pendukung')
                            <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                </div>

                {{-- Perbaikan --}}
                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1.5">
                        Tindakan Perbaikan
                        <span class="text-gray-400 font-normal">(opsional)</span>
                    </label>
                    <textarea name="perbaikan" rows="3"
                        placeholder="Tindakan perbaikan yang telah atau akan dilakukan..."
                        class="w-full px-4 py-2.5 text-sm rounded-xl border border-gray-200 focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none resize-none @error('perbaikan') border-red-300 @enderror">{{ old('perbaikan') }}</textarea>
                    @error('perbaikan')
                        <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Rencana Tindak Lanjut --}}
                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1.5">
                        Rencana Tindak Lanjut
                        <span class="text-gray-400 font-normal">(opsional)</span>
                    </label>
                    <textarea name="rencana_tindak_lanjut" rows="3"
                        placeholder="Rencana tindakan yang akan diambil ke depan..."
                        class="w-full px-4 py-2.5 text-sm rounded-xl border border-gray-200 focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none resize-none @error('rencana_tindak_lanjut') border-red-300 @enderror">{{ old('rencana_tindak_lanjut') }}</textarea>
                    @error('rencana_tindak_lanjut')
                        <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- PIC & Tanggal Penyelesaian --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">

                    {{-- PIC --}}
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-1.5">
                            PIC (Penanggung Jawab)
                            <span class="text-gray-400 font-normal">(opsional)</span>
                        </label>
                        <input type="text" name="pic" value="{{ old('pic') }}"
                            placeholder="Nama penanggung jawab audit"
                            maxlength="150"
                            class="w-full px-4 py-2.5 text-sm rounded-xl border border-gray-200 focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none @error('pic') border-red-300 @enderror">
                        @error('pic')
                            <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Tanggal Penyelesaian --}}
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-1.5">
                            Tanggal Penyelesaian
                            <span class="text-gray-400 font-normal">(opsional)</span>
                        </label>
                        <input type="date" name="tanggal_penyelesaian" value="{{ old('tanggal_penyelesaian') }}"
                            class="w-full px-4 py-2.5 text-sm rounded-xl border border-gray-200 focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none @error('tanggal_penyelesaian') border-red-300 @enderror">
                        @error('tanggal_penyelesaian')
                            <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                        @enderror
                        <p class="text-[10px] text-gray-400 mt-1">Isi jika kegiatan sudah selesai dilaksanakan.</p>
                    </div>

                </div>

                {{-- Actions --}}
                <div class="flex justify-end gap-3 pt-2 border-t border-gray-100">
                    <a href="{{ route('audit-monitoring.index') }}"
                        class="px-6 py-2.5 rounded-full border border-gray-200 text-gray-600 text-sm font-medium hover:bg-gray-100 hover:border-gray-300 transition-all duration-200 active:scale-95">
                        Batal
                    </a>
                    <button type="submit"
                        class="inline-flex items-center gap-2 bg-gold text-gray-900 px-6 py-2.5 rounded-full text-sm font-semibold shadow hover:bg-gold-dark hover:shadow-md transition-all duration-200 active:scale-95">
                        <span class="material-symbols-outlined text-[18px]">save</span>
                        Simpan Audit
                    </button>
                </div>

            </form>
        </div>

        {{-- Side Info --}}
        <div class="lg:col-span-1 space-y-4">

            <div class="bg-primary/5 border border-primary/10 rounded-3xl p-5 space-y-3">
                <div class="flex items-center gap-2 text-primary">
                    <span class="material-symbols-outlined text-[18px]">info</span>
                    <h3 class="text-sm font-semibold">Petunjuk Pengisian</h3>
                </div>
                <ul class="text-xs text-gray-600 space-y-2 list-disc pl-4">
                    <li>Pilih <strong>Indikator Mutu</strong> dan <strong>Tahun Anggaran</strong> yang sesuai terlebih dahulu.</li>
                    <li><strong>Uraian Pelaksanaan</strong> mendeskripsikan kegiatan konkret yang sudah dilakukan.</li>
                    <li>Catat <strong>Kendala</strong> yang dihadapi agar dapat dijadikan bahan evaluasi.</li>
                    <li><strong>Rencana Tindak Lanjut</strong> diisi apabila masih ada langkah yang perlu dilanjutkan.</li>
                    <li>Isi <strong>Tanggal Penyelesaian</strong> jika kegiatan sudah selesai; biarkan kosong jika masih berjalan.</li>
                </ul>
            </div>

            {{-- Field Reference --}}
            <div class="bg-white border border-gray-100 rounded-3xl p-5 space-y-3 shadow-sm">
                <div class="flex items-center gap-2 text-gray-600">
                    <span class="material-symbols-outlined text-[18px]">label</span>
                    <h3 class="text-sm font-semibold">Status Otomatis</h3>
                </div>
                <ul class="text-xs space-y-2">
                    <li class="flex items-start gap-2">
                        <span class="mt-0.5 w-2 h-2 rounded-full bg-amber-400 shrink-0"></span>
                        <span><strong class="text-amber-700">Proses</strong> — Tanggal penyelesaian belum diisi.</span>
                    </li>
                    <li class="flex items-start gap-2">
                        <span class="mt-0.5 w-2 h-2 rounded-full bg-emerald-500 shrink-0"></span>
                        <span><strong class="text-emerald-700">Selesai</strong> — Tanggal penyelesaian sudah diisi.</span>
                    </li>
                </ul>
            </div>

        </div>

    </div>

    @push('scripts')
        <script src="{{ asset('js/audit-form.js') }}"></script>
    @endpush

</x-app-layout>
