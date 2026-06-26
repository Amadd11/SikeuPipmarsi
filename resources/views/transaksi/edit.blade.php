<x-app-layout>
    <x-slot:title>Edit Transaksi — {{ $transaksi->kode_transaksi }}</x-slot>

    {{-- Header --}}
    <div class="mb-5">
        <a href="{{ route('transaksi.index') }}"
            class="inline-flex items-center gap-1 text-xs text-gray-500 hover:text-primary transition mb-2">
            <span class="material-symbols-outlined text-[14px]">arrow_back</span> Kembali ke Log Transaksi
        </a>
        <h1 class="text-xl font-bold text-gray-900 tracking-tight">Koreksi Data Transaksi</h1>
        <p class="text-xs text-gray-500">Perbarui rincian log transaksi yang mengalami kekeliruan pencatatan</p>
    </div>

    {{-- Form Card --}}
    <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-6 max-w-3xl" x-data="{
        jenis: '{{ old('jenis', $transaksi->jenis) }}',
        typeModel: '{{ addslashes(old('transaksable_type', $transaksi->transaksable_type)) }}',
        init() {
            this.$watch('jenis', value => {
                this.typeModel = value === 'pemasukan' ? 'App\\Models\\RencanaPendapatan' : 'App\\Models\\RencanaPengeluaran';
            });
        }
    }">

        <form action="{{ route('transaksi.update', $transaksi->id) }}" method="POST" enctype="multipart/form-data"
            class="space-y-4 text-xs">
            @csrf
            @method('PUT')

            <input type="hidden" name="transaksable_type" :value="typeModel">

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                {{-- Kode Transaksi (Input Manual) --}}
                <div>
                    <label class="block font-semibold text-gray-700 mb-1.5">Kode Transaksi / No. Kuitansi</label>
                    <input type="text" name="kode_transaksi"
                        value="{{ old('kode_transaksi', $transaksi->kode_transaksi) }}"
                        class="w-full px-3 py-2 border border-gray-200 rounded-xl focus:outline-none focus:border-primary focus:ring-2 focus:ring-primary/20 bg-gray-50/30 text-gray-900 @error('kode_transaksi') border-red-500 @enderror">
                    @error('kode_transaksi')
                        <p class="text-red-500 text-[10px] mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Tahun Anggaran --}}
                <div>
                    <label class="block font-semibold text-gray-700 mb-1.5">Tahun Anggaran</label>
                    <select name="tahun_anggaran_id"
                        class="w-full px-3 py-2 border border-gray-200 rounded-xl focus:outline-none focus:border-primary focus:ring-2 focus:ring-primary/20 bg-white text-gray-900">
                        @foreach ($tahunAnggaranList as $tahun)
                            <option value="{{ $tahun->id }}"
                                {{ old('tahun_anggaran_id', $transaksi->tahun_anggaran_id) == $tahun->id ? 'selected' : '' }}>
                                TA {{ $tahun->tahun }} {{ $tahun->is_aktif ? '(Aktif)' : '' }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Tanggal --}}
                <div>
                    <label class="block font-semibold text-gray-700 mb-1.5">Tanggal Transaksi</label>
                    <input type="date" name="tanggal"
                        value="{{ old('tanggal', $transaksi->tanggal->format('Y-m-d')) }}"
                        class="w-full px-3 py-2 border border-gray-200 rounded-xl focus:outline-none focus:border-primary focus:ring-2 focus:ring-primary/20 bg-white text-gray-900">
                </div>

                {{-- Jenis Transaksi --}}
                <div>
                    <label class="block font-semibold text-gray-700 mb-1.5">Jenis Arus Kas</label>
                    <select name="jenis" x-model="jenis"
                        class="w-full px-3 py-2 border border-gray-200 rounded-xl focus:outline-none focus:border-primary focus:ring-2 focus:ring-primary/20 bg-white text-gray-900">
                        <!-- PERBAIKAN: Value diubah menjadi pemasukan & pengeluaran -->
                        <option value="pemasukan">Masuk (Pemasukan / Pendapatan)</option>
                        <option value="pengeluaran">Keluar (Pengeluaran / Beban)</option>
                    </select>
                </div>

                {{-- Referensi Anggaran Terkait (Polymorphic Dropdown) --}}
                <div class="md:col-span-2">
                    <label class="block font-semibold text-gray-700 mb-1.5">Alokasi / Referensi Anggaran Terkait</label>

                    {{-- Dropdown jika Jurnal Masuk --}}
                    <!-- PERBAIKAN: Logic dievaluasi terhadap 'pemasukan' -->
                    <div x-show="jenis === 'pemasukan'" style="display: none;" x-transition>
                        <select name="transaksable_id" :disabled="jenis !== 'pemasukan'"
                            class="w-full px-3 py-2 border border-gray-200 rounded-xl focus:outline-none focus:border-primary focus:ring-2 focus:ring-primary/20 bg-white text-gray-900 disabled:bg-gray-100 disabled:opacity-50">
                            @foreach ($rencanaPendapatanList as $pendapatan)
                                <option value="{{ $pendapatan->id }}"
                                    {{ old('transaksable_id', $transaksi->transaksable_id) == $pendapatan->id && old('transaksable_type', $transaksi->transaksable_type) === 'App\\Models\\RencanaPendapatan' ? 'selected' : '' }}>
                                    {{ $pendapatan->nama_sumber }} (Target: Rp
                                    {{ number_format($pendapatan->jumlah_rencana, 0, ',', '.') }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Dropdown jika Jurnal Keluar --}}
                    <!-- PERBAIKAN: Logic dievaluasi terhadap 'pengeluaran' -->
                    <div x-show="jenis === 'pengeluaran'" style="display: none;" x-transition>
                        <select name="transaksable_id" :disabled="jenis !== 'pengeluaran'"
                            class="w-full px-3 py-2 border border-gray-200 rounded-xl focus:outline-none focus:border-primary focus:ring-2 focus:ring-primary/20 bg-white text-gray-900 disabled:bg-gray-100 disabled:opacity-50">
                            @foreach ($rencanaPengeluaranList as $pengeluaran)
                                <option value="{{ $pengeluaran->id }}"
                                    {{ old('transaksable_id', $transaksi->transaksable_id) == $pengeluaran->id && old('transaksable_type', $transaksi->transaksable_type) === 'App\\Models\\RencanaPengeluaran' ? 'selected' : '' }}>
                                    [{{ $pengeluaran->bidangKerja->nama ?? 'Umum' }}]
                                    {{ $pengeluaran->nama_kegiatan }} (Pagu: Rp
                                    {{ number_format($pengeluaran->jumlah_anggaran, 0, ',', '.') }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                {{-- Bidang Kerja --}}
                <div>
                    <label class="block font-semibold text-gray-700 mb-1.5">Departemen / Bidang Kerja (Opsional)</label>
                    <select name="bidang_kerja_id"
                        class="w-full px-3 py-2 border border-gray-200 rounded-xl focus:outline-none focus:border-primary focus:ring-2 focus:ring-primary/20 bg-white text-gray-900">
                        <option value="">-- Pilih Bidang Kerja --</option>
                        @foreach ($bidangKerjaList as $bidang)
                            <option value="{{ $bidang->id }}"
                                {{ old('bidang_kerja_id', $transaksi->bidang_kerja_id) == $bidang->id ? 'selected' : '' }}>
                                {{ $bidang->nama }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Jumlah Nominal --}}
                <div>
                    <label class="block font-semibold text-gray-700 mb-1.5">Jumlah Nominal (Rp)</label>
                    <input type="number" name="jumlah" value="{{ old('jumlah', intval($transaksi->jumlah)) }}"
                        class="w-full px-3 py-2 border border-gray-200 rounded-xl focus:outline-none focus:border-primary focus:ring-2 focus:ring-primary/20 bg-white text-gray-900 @error('jumlah') border-red-500 @enderror">
                    @error('jumlah')
                        <p class="text-red-500 text-[10px] mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Nomor Bukti Fisik --}}
                <div>
                    <label class="block font-semibold text-gray-700 mb-1.5">Nomor Nota / Kuitansi Fisik</label>
                    <input type="text" name="nomor_bukti" value="{{ old('nomor_bukti', $transaksi->nomor_bukti) }}"
                        class="w-full px-3 py-2 border border-gray-200 rounded-xl focus:outline-none focus:border-primary focus:ring-2 focus:ring-primary/20 bg-white text-gray-900">
                </div>

                {{-- Upload File Bukti Terganti --}}
                <div>
                    <label class="block font-semibold text-gray-700 mb-1.5">Ganti File Bukti / Nota (Biarkan kosong jika
                        tetap)</label>
                    <input type="file" name="file_bukti"
                        class="w-full px-3 py-1.5 border border-gray-200 rounded-xl focus:outline-none bg-white text-gray-500 file:mr-4 file:py-1 file:px-3 file:rounded-md file:border-0 file:text-xs file:font-semibold file:bg-gray-100 file:text-gray-700 hover:file:bg-gray-200">

                    {{-- Tampilkan indikator jika file lama ada di storage --}}
                    @if ($transaksi->file_bukti)
                        <div class="mt-2 flex items-center gap-1 text-[11px] text-gray-500">
                            <span class="material-symbols-outlined text-[14px] text-blue-500">attachment</span>
                            <span>File saat ini tersedia: </span>
                            <a href="{{ Storage::url($transaksi->file_bukti) }}" target="_blank"
                                class="text-primary hover:underline font-medium">Lihat Dokumen</a>
                        </div>
                    @endif
                </div>

                {{-- Uraian --}}
                <div class="md:col-span-2">
                    <label class="block font-semibold text-gray-700 mb-1.5">Uraian / Deskripsi Transaksi</label>
                    <textarea name="uraian" rows="3"
                        class="w-full px-3 py-2 border border-gray-200 rounded-xl focus:outline-none focus:border-primary focus:ring-2 focus:ring-primary/20 bg-white text-gray-900 @error('uraian') border-red-500 @enderror">{{ old('uraian', $transaksi->uraian) }}</textarea>
                    @error('uraian')
                        <p class="text-red-500 text-[10px] mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            {{-- Form Actions --}}
            <div class="flex items-center justify-end gap-2 pt-2 border-t border-gray-100">
                <a href="{{ route('transaksi.index') }}"
                    class="px-4 py-2 rounded-xl border border-gray-200 font-medium text-gray-600 hover:bg-gray-50 transition">
                    Batal
                </a>
                <button type="submit"
                    class="px-4 py-2 rounded-xl bg-primary hover:bg-primary-dark font-semibold text-white transition shadow-xs">
                    Perbarui Transaksi
                </button>
            </div>

        </form>
    </div>
</x-app-layout>
