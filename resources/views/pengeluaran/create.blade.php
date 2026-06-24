<x-app-layout>
    <x-slot:title>Tambah Pengeluaran</x-slot>

    {{-- Header --}}
    <div class="border-b border-gray-100 pb-6">

        <a href="{{ route('pengeluaran.index') }}"
            class="inline-flex items-center gap-1 text-xs text-gray-400 hover:text-primary transition mb-3">
            <span class="material-symbols-outlined text-[16px]">arrow_back</span>
            Kembali ke Rencana Pengeluaran
        </a>

        <h2 class="text-lg font-semibold text-gray-900">Tambah Pos Anggaran Baru</h2>
        <p class="text-sm text-gray-500 mt-1">
            Tambahkan rencana pengeluaran ke bidang kerja yang sesuai
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
            <form method="POST" action="{{ route('pengeluaran.store') }}"
                class="bg-white rounded-3xl border border-gray-100 shadow-sm p-6 space-y-5">
                @csrf

                {{-- Tahun Anggaran --}}
                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1.5">
                        Tahun Anggaran <span class="text-red-500">*</span>
                    </label>
                    <select name="tahun_anggaran_id"
                        class="w-full px-4 py-2.5 text-sm rounded-xl border border-gray-200 focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none bg-white @error('tahun_anggaran_id') border-red-300 @enderror">
                        <option value="">Pilih tahun anggaran</option>
                        @foreach ($tahunAnggaranList as $tahun)
                            <option value="{{ $tahun->id }}" @selected(old('tahun_anggaran_id') == $tahun->id || $tahun->is_aktif)>
                                TA {{ $tahun->tahun }}{{ $tahun->is_aktif ? ' (Aktif)' : '' }}
                            </option>
                        @endforeach
                    </select>
                    @error('tahun_anggaran_id')
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
                            <option value="{{ $bidang->id }}" @selected(old('bidang_kerja_id') == $bidang->id)>
                                {{ $bidang->nama }}
                            </option>
                        @endforeach
                    </select>
                    @error('bidang_kerja_id')
                        <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Nama Kegiatan --}}
                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1.5">
                        Nama Kegiatan / Pos Anggaran <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="nama_kegiatan" value="{{ old('nama_kegiatan') }}"
                        placeholder="Contoh: Rapat Pengurus Rutin (12 kali/tahun)"
                        class="w-full px-4 py-2.5 text-sm rounded-xl border border-gray-200 focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none @error('nama_kegiatan') border-red-300 @enderror">
                    @error('nama_kegiatan')
                        <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Kategori & Indikator Mutu --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">

                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-1.5">
                            Kategori Pengeluaran <span class="text-red-500">*</span>
                        </label>
                        <select name="kategori_pengeluaran_id"
                            class="w-full px-4 py-2.5 text-sm rounded-xl border border-gray-200 focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none bg-white @error('kategori_pengeluaran_id') border-red-300 @enderror">
                            <option value="">Pilih kategori</option>
                            @foreach ($kategoriList as $kategori)
                                <option value="{{ $kategori->id }}" @selected(old('kategori_pengeluaran_id') == $kategori->id)>
                                    {{ $kategori->nama }}
                                </option>
                            @endforeach
                        </select>
                        @error('kategori_pengeluaran_id')
                            <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-1.5">
                            Indikator Mutu
                            <span class="text-gray-400 font-normal">(opsional)</span>
                        </label>
                        <select name="indikator_mutu_id"
                            class="w-full px-4 py-2.5 text-sm rounded-xl border border-gray-200 focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none bg-white @error('indikator_mutu_id') border-red-300 @enderror">
                            <option value="">Tidak terkait indikator</option>
                            @foreach ($indikatorList as $indikator)
                                <option value="{{ $indikator->id }}" @selected(old('indikator_mutu_id') == $indikator->id)>
                                    {{ $indikator->kode }} — {{ Str::limit($indikator->nama, 50) }}
                                </option>
                            @endforeach
                        </select>
                        @error('indikator_mutu_id')
                            <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                </div>

                {{-- Jumlah Anggaran --}}
                <div x-data="{
                    raw: '{{ old('jumlah_anggaran') }}',
                    display: '{{ old('jumlah_anggaran') ? number_format((float) old('jumlah_anggaran'), 0, ',', '.') : '' }}',
                    format(e) {
                        let v = e.target.value.replace(/\D/g, '');
                        this.raw = v;
                        this.display = v ? new Intl.NumberFormat('id-ID').format(v) : '';
                        e.target.value = this.display;
                    }
                }">
                    <label class="block text-xs font-semibold text-gray-700 mb-1.5">
                        Jumlah Anggaran <span class="text-red-500">*</span>
                    </label>
                    <div class="relative">
                        <span
                            class="absolute left-4 top-1/2 -translate-y-1/2 text-sm text-gray-400 font-medium">Rp</span>
                        <input type="text" inputmode="numeric" x-model="display" @input="format($event)"
                            placeholder="0"
                            class="w-full pl-10 pr-4 py-2.5 text-sm rounded-xl border border-gray-200 focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none @error('jumlah_anggaran') border-red-300 @enderror">
                    </div>
                    <input type="hidden" name="jumlah_anggaran" :value="raw">
                    @error('jumlah_anggaran')
                        <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Keterangan --}}
                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1.5">
                        Keterangan / Rincian
                        <span class="text-gray-400 font-normal">(opsional)</span>
                    </label>
                    <textarea name="keterangan" rows="3" placeholder="Contoh: Konsumsi & ATK rapat bulanan, 12 kali × Rp 500.000"
                        class="w-full px-4 py-2.5 text-sm rounded-xl border border-gray-200 focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none resize-none @error('keterangan') border-red-300 @enderror">{{ old('keterangan') }}</textarea>
                    @error('keterangan')
                        <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Actions --}}
                <div class="flex justify-end gap-3 pt-2 border-t border-gray-100">
                    <a href="{{ route('pengeluaran.index') }}"
                        class="px-5 py-2.5 rounded-xl border border-gray-200 text-gray-600 text-sm font-medium hover:bg-gray-50 transition">
                        Batal
                    </a>
                    <button type="submit"
                        class="inline-flex items-center gap-2 bg-primary text-white px-5 py-2.5 rounded-xl text-sm font-medium hover:bg-primary-dark transition">
                        <span class="material-symbols-outlined text-[18px]">save</span>
                        Simpan Pengeluaran
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
                    <li>Pilih <strong>Bidang Kerja</strong> sesuai unit yang bertanggung jawab atas pengeluaran ini.
                    </li>
                    <li>Nama kegiatan harus spesifik, sertakan frekuensi jika ada (contoh: 12 kali/tahun).</li>
                    <li>Kaitkan dengan <strong>Indikator Mutu</strong> agar laporan rekapitulasi lebih akurat.</li>
                    <li>Realisasi akan diperbarui melalui menu <strong>Aktivitas & Realisasi</strong>, bukan di sini.
                    </li>
                </ul>
            </div>

            @if (session('tahun_anggaran'))
                <div class="bg-amber-50 border border-amber-100 rounded-3xl p-5 space-y-2">
                    <div class="flex items-center gap-2 text-amber-700">
                        <span class="material-symbols-outlined text-[18px]">warning</span>
                        <h3 class="text-sm font-semibold">Perhatian</h3>
                    </div>
                    <p class="text-xs text-gray-600">{{ session('tahun_anggaran') }}</p>
                </div>
            @endif

        </div>

    </div>

</x-app-layout>
