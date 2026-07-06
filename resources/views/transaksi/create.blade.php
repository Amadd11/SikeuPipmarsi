<x-app-layout>
    <x-slot:title>Catat Transaksi Baru</x-slot>

    {{-- Header --}}
    <div class="mb-5">
        <h1 class="text-xl font-bold text-gray-900 tracking-tight">Catat Transaksi Keuangan</h1>
        <p class="text-xs text-gray-500">Formulir untuk mencatat realisasi arus kas masuk atau keluar</p>
    </div>

    {{-- Form Card --}}
    <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-6 max-w-3xl" x-data="{
        jenis: '{{ old('jenis', $jenis) }}',
        get typeModel() {
            return this.jenis === 'pemasukan' ?
                'App\\Models\\RencanaPendapatan' :
                'App\\Models\\RencanaPengeluaran';
        }
    }">

        <form action="{{ route('transaksi.store') }}" method="POST" enctype="multipart/form-data"
            class="space-y-4 text-xs">
            @csrf

            {{-- Hidden input untuk transaksable_type otomatis berdasarkan pilihan jenis --}}
            <input type="hidden" name="transaksable_type" :value="typeModel">

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                {{-- Kode Transaksi (Input Manual) --}}
                <div>
                    <label class="block font-semibold text-gray-700 mb-1.5">Kode Transaksi / No. Kuitansi</label>
                    <input type="text" name="kode_transaksi" value="{{ old('kode_transaksi') }}"
                        placeholder="Contoh: KWT-001 atau TRX-2026-05"
                        class="w-full px-3 py-2 border border-gray-200 rounded-xl focus:outline-none focus:border-primary focus:ring-2 focus:ring-primary/20 bg-gray-50/30 text-gray-900 @error('kode_transaksi') border-red-500 @enderror">
                    @error('kode_transaksi')
                        <p class="text-red-500 text-[10px] mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Tahun Anggaran --}}
                <div>
                    <label class="block font-semibold text-gray-700 mb-1.5">Tahun Anggaran</label>
                    <select name="tahun_anggaran_id"
                        class="w-full px-3 py-2 border border-gray-200 rounded-xl focus:outline-none focus:border-primary focus:ring-2 focus:ring-primary/20 bg-white text-gray-900 @error('tahun_anggaran_id') border-red-500 @enderror">
                        @foreach ($tahunAnggaranList as $tahun)
                            <option value="{{ $tahun->id }}"
                                {{ old('tahun_anggaran_id', $tahun->is_aktif ? $tahun->id : null) == $tahun->id ? 'selected' : '' }}>
                                TA {{ $tahun->tahun }} {{ $tahun->is_aktif ? '(Aktif)' : '' }}
                            </option>
                        @endforeach
                    </select>
                    @error('tahun_anggaran_id')
                        <p class="text-red-500 text-[10px] mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Tanggal --}}
                <div>
                    <label class="block font-semibold text-gray-700 mb-1.5">Tanggal Transaksi</label>
                    <input type="date" name="tanggal" value="{{ old('tanggal', date('Y-m-d')) }}"
                        class="w-full px-3 py-2 border border-gray-200 rounded-xl focus:outline-none focus:border-primary focus:ring-2 focus:ring-primary/20 bg-white text-gray-900 @error('tanggal') border-red-500 @enderror">
                    @error('tanggal')
                        <p class="text-red-500 text-[10px] mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Jenis Transaksi --}}
                <div>
                    <label class="block font-semibold text-gray-700 mb-1.5">Jenis Arus Kas</label>
                    <select name="jenis" x-model="jenis"
                        class="w-full px-3 py-2 border border-gray-200 rounded-xl focus:outline-none focus:border-primary focus:ring-2 focus:ring-primary/20 bg-white text-gray-900 @error('jenis') border-red-500 @enderror">
                        <option value="pemasukan">Rencana Pendapatan</option>
                        <option value="pengeluaran">Rencana Pengeluaran</option>
                    </select>
                    @error('jenis')
                        <p class="text-red-500 text-[10px] mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Referensi Anggaran Terkait — dua select, di-toggle Alpine, BUKAN reload --}}
                <div class="md:col-span-2">
                    <label class="block font-semibold text-gray-700 mb-1.5"
                        x-text="jenis === 'pemasukan' ? 'Alokasi Referensi (Sumber Pendapatan)' : 'Alokasi Referensi (Pos Pengeluaran)'">
                    </label>

                    {{-- Select untuk Pemasukan --}}
                    <select name="transaksable_id" x-show="jenis === 'pemasukan'" :disabled="jenis !== 'pemasukan'"
                        class="w-full px-3 py-2 border border-gray-200 rounded-xl">
                        <option value="">Pilih Sumber Pendapatan</option>
                        @foreach ($rencanaPendapatanList as $rencana)
                            <option value="{{ $rencana->id }}"
                                {{ old('transaksable_id', $jenis === 'pemasukan' ? $transaksableId : null) == $rencana->id ? 'selected' : '' }}>
                                {{ $rencana->nama_sumber }}
                            </option>
                        @endforeach
                    </select>

                    {{-- Select untuk Pengeluaran --}}
                    <select name="transaksable_id" x-show="jenis === 'pengeluaran'" :disabled="jenis !== 'pengeluaran'"
                        class="w-full px-3 py-2 border border-gray-200 rounded-xl">
                        <option value="">Pilih Pos Pengeluaran</option>
                        @foreach ($rencanaPengeluaranList as $rencana)
                            <option value="{{ $rencana->id }}"
                                {{ old('transaksable_id', $jenis === 'pengeluaran' ? $transaksableId : null) == $rencana->id ? 'selected' : '' }}>
                                [{{ $rencana->bidangKerja?->nama }}] {{ $rencana->nama_kegiatan }}
                            </option>
                        @endforeach
                    </select>

                    @error('transaksable_id')
                        <p class="text-red-500 text-[10px] mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Bidang Kerja (Opsional) --}}
                <div>
                    <label class="block font-semibold text-gray-700 mb-1.5">Bidang Terkait (Opsional)</label>
                    <select name="bidang_kerja_id"
                        class="w-full px-3 py-2 border border-gray-200 rounded-xl focus:outline-none focus:border-primary focus:ring-2 focus:ring-primary/20 bg-white text-gray-900">
                        <option value="">-- Pilih Bidang Kerja --</option>
                        @foreach ($bidangKerjaList as $bidang)
                            <option value="{{ $bidang->id }}"
                                {{ old('bidang_kerja_id') == $bidang->id ? 'selected' : '' }}>
                                {{ $bidang->nama }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Jumlah Nominal --}}
                <div>
                    <label class="block font-semibold text-gray-700 mb-1.5">Jumlah Nominal (Rp)</label>
                    <input type="number" name="jumlah" value="{{ old('jumlah') }}" min="0"
                        placeholder="Contoh: 5000000"
                        class="w-full px-3 py-2 border border-gray-200 rounded-xl focus:outline-none focus:border-primary focus:ring-2 focus:ring-primary/20 bg-white text-gray-900 @error('jumlah') border-red-500 @enderror">
                    @error('jumlah')
                        <p class="text-red-500 text-[10px] mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Nomor Bukti Fisik --}}
                <div>
                    <label class="block font-semibold text-gray-700 mb-1.5">Nomor Nota / Kuitansi Fisik
                        (Opsional)</label>
                    <input type="text" name="nomor_bukti" value="{{ old('nomor_bukti') }}"
                        placeholder="Contoh: NOTA/04/VI"
                        class="w-full px-3 py-2 border border-gray-200 rounded-xl focus:outline-none focus:border-primary focus:ring-2 focus:ring-primary/20 bg-white text-gray-900">
                </div>

                {{-- Upload File Bukti --}}
                <div>
                    <label class="block font-semibold text-gray-700 mb-1.5">Upload File Bukti / Scan Nota
                        (PDF/Gambar)</label>
                    <input type="file" name="file_bukti"
                        class="w-full px-3 py-1.5 border border-gray-200 rounded-xl focus:outline-none bg-white text-gray-500 file:mr-4 file:py-1 file:px-3 file:rounded-md file:border-0 file:text-xs file:font-semibold file:bg-gray-100 file:text-gray-700 hover:file:bg-gray-200">
                    @error('file_bukti')
                        <p class="text-red-500 text-[10px] mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Uraian --}}
                <div class="md:col-span-2">
                    <label class="block font-semibold text-gray-700 mb-1.5">Uraian / Deskripsi Transaksi</label>
                    <textarea name="uraian" rows="3" placeholder="Tulis rincian deskripsi transaksi secara detail di sini..."
                        class="w-full px-3 py-2 border border-gray-200 rounded-xl focus:outline-none focus:border-primary focus:ring-2 focus:ring-primary/20 bg-white text-gray-900 @error('uraian') border-red-500 @enderror">{{ old('uraian') }}</textarea>
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
                    class="inline-flex items-center gap-2 bg-gold text-gray-900 px-6 py-2.5 rounded-full text-sm font-semibold shadow hover:bg-gold-dark hover:shadow-md transition-all duration-200 active:scale-95">
                    Simpan Transaksi
                </button>
            </div>

        </form>
    </div>
</x-app-layout>
