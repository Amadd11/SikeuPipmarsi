<x-app-layout>
    <x-slot:title>Edit Pengeluaran</x-slot>

    {{-- Header --}}
    <div class="border-b border-gray-100 pb-6">

        <a href="{{ route('pengeluaran.index', ['tahun' => $pengeluaran->tahun_anggaran_id, 'bidang' => $pengeluaran->bidang_kerja_id]) }}"
            class="inline-flex items-center gap-1 text-xs text-gray-400 hover:text-primary transition mb-3">
            <span class="material-symbols-outlined text-[16px]">arrow_back</span>
            Kembali ke Rencana Pengeluaran
        </a>

        <h2 class="text-lg font-semibold text-gray-900">Edit Pos Anggaran</h2>
        <p class="text-sm text-gray-500 mt-1">
            Perbarui informasi pos anggaran
            <span class="font-medium text-gray-700">{{ $pengeluaran->nama_kegiatan }}</span>
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
            <form method="POST" action="{{ route('pengeluaran.update', $pengeluaran->id) }}"
                class="bg-white rounded-3xl border border-gray-100 shadow-sm p-6 space-y-5">
                @csrf
                @method('PUT')

                {{-- Tahun Anggaran --}}
                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1.5">
                        Tahun Anggaran <span class="text-red-500">*</span>
                    </label>
                    <select name="tahun_anggaran_id"
                        class="w-full px-4 py-2.5 text-sm rounded-xl border border-gray-200 focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none bg-white @error('tahun_anggaran_id') border-red-300 @enderror">
                        <option value="">Pilih tahun anggaran</option>
                        @foreach ($tahunAnggaranList as $tahun)
                            <option value="{{ $tahun->id }}" @selected(old('tahun_anggaran_id', $pengeluaran->tahun_anggaran_id) == $tahun->id)>
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
                            <option value="{{ $bidang->id }}" @selected(old('bidang_kerja_id', $pengeluaran->bidang_kerja_id) == $bidang->id)>
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
                    <input type="text" name="nama_kegiatan"
                        value="{{ old('nama_kegiatan', $pengeluaran->nama_kegiatan) }}"
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
                                <option value="{{ $kategori->id }}" @selected(old('kategori_pengeluaran_id', $pengeluaran->kategori_pengeluaran_id) == $kategori->id)>
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
                                <option value="{{ $indikator->id }}" @selected(old('indikator_mutu_id', $pengeluaran->indikator_mutu_id) == $indikator->id)>
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
                @php $anggaranValue = old('jumlah_anggaran', $pengeluaran->jumlah_anggaran); @endphp
                <div x-data="{
                    raw: '{{ $anggaranValue }}',
                    display: '{{ number_format((float) $anggaranValue, 0, ',', '.') }}',
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
                        class="w-full px-4 py-2.5 text-sm rounded-xl border border-gray-200 focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none resize-none @error('keterangan') border-red-300 @enderror">{{ old('keterangan', $pengeluaran->keterangan) }}</textarea>
                    @error('keterangan')
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
                        <a href="{{ route('pengeluaran.index', ['tahun' => $pengeluaran->tahun_anggaran_id, 'bidang' => $pengeluaran->bidang_kerja_id]) }}"
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

            <form id="delete-form" method="POST" action="{{ route('pengeluaran.destroy', $pengeluaran->id) }}"
                class="hidden"
                onsubmit="return confirm('Hapus pos anggaran {{ addslashes($pengeluaran->nama_kegiatan) }}? Tindakan ini tidak dapat dibatalkan.')">
                @csrf
                @method('DELETE')
            </form>

        </div>

        {{-- Side Panel --}}
        <div class="lg:col-span-1 space-y-4">

            {{-- Status Realisasi --}}
            @php
                $sisa = $pengeluaran->jumlah_anggaran - $pengeluaran->jumlah_realisasi;
                $persen =
                    $pengeluaran->jumlah_anggaran > 0
                        ? round(($pengeluaran->jumlah_realisasi / $pengeluaran->jumlah_anggaran) * 100)
                        : 0;
                $tercapai = $persen >= 100;
            @endphp

            <div class="bg-white rounded-3xl border border-gray-100 shadow-sm p-5">
                <h3 class="text-sm font-semibold text-gray-900 mb-4">Status Realisasi</h3>

                <div class="space-y-3">

                    <div class="flex items-center justify-between">
                        <span class="text-xs text-gray-500">Anggaran</span>
                        <span class="text-sm font-semibold text-gray-900">
                            Rp {{ number_format($pengeluaran->jumlah_anggaran, 0, ',', '.') }}
                        </span>
                    </div>

                    <div class="flex items-center justify-between">
                        <span class="text-xs text-gray-500">Realisasi</span>
                        <span
                            class="text-sm font-semibold {{ $pengeluaran->jumlah_realisasi > 0 ? 'text-emerald-600' : 'text-red-500' }}">
                            Rp {{ number_format($pengeluaran->jumlah_realisasi, 0, ',', '.') }}
                        </span>
                    </div>

                    <div class="flex items-center justify-between">
                        <span class="text-xs text-gray-500">Sisa</span>
                        <span class="text-sm font-semibold text-primary">
                            Rp {{ number_format($sisa, 0, ',', '.') }}
                        </span>
                    </div>

                    <div class="pt-2">
                        <div class="flex items-center gap-3">
                            <div class="flex-1 h-2 rounded-full bg-gray-100 overflow-hidden">
                                <div class="h-full bg-emerald-500 transition-all"
                                    style="width: {{ min($persen, 100) }}%">
                                </div>
                            </div>
                            <span class="text-xs font-medium text-gray-500 w-9 text-right">{{ $persen }}%</span>
                        </div>
                    </div>

                    <div class="pt-1">
                        <span
                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[11px] font-medium
                            {{ $tercapai ? 'bg-emerald-50 text-emerald-700' : 'bg-gray-100 text-gray-600' }}">
                            {{ $tercapai ? 'Tercapai' : 'Belum Tercapai' }}
                        </span>
                    </div>

                </div>
            </div>

            {{-- Info Bidang --}}
            <div class="bg-gray-50 border border-gray-100 rounded-3xl p-5 space-y-2">
                <div class="flex items-center gap-2 text-gray-600">
                    <span class="material-symbols-outlined text-[18px]">domain</span>
                    <h3 class="text-sm font-semibold">Bidang Kerja Saat Ini</h3>
                </div>
                <p class="text-xs text-gray-700 font-medium pl-6">
                    {{ $pengeluaran->bidangKerja->nama ?? '-' }}
                </p>
                <p class="text-xs text-gray-400 pl-6">
                    Mengubah bidang kerja akan memindahkan pos anggaran ini ke tab bidang yang baru dipilih.
                </p>
            </div>

            {{-- Warning --}}
            <div class="bg-amber-50 border border-amber-100 rounded-3xl p-5 space-y-2">
                <div class="flex items-center gap-2 text-amber-700">
                    <span class="material-symbols-outlined text-[18px]">warning</span>
                    <h3 class="text-sm font-semibold">Perhatian</h3>
                </div>
                <p class="text-xs text-gray-600">
                    Mengubah jumlah anggaran tidak akan mempengaruhi data realisasi yang sudah tercatat.
                    Realisasi dikelola melalui menu <strong>Aktivitas & Realisasi</strong>.
                </p>
            </div>

        </div>

    </div>

</x-app-layout>
