<x-app-layout>
    <x-slot:title>Aktivitas & Realisasi</x-slot>

    {{-- Header --}}
    <div class="border-b border-gray-100 pb-6">
        <h2 class="text-lg font-semibold text-gray-900">Edit Transaksi</h2>
        <p class="text-sm text-gray-500 mt-1">
            Perbarui rincian log transaksi yang mengalami kekeliruan pencatatan
        </p>
    </div>

    {{-- Form Container --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mt-6">

        {{-- Form --}}
        <div class="lg:col-span-2">
            <form action="{{ route('transaksi.update', $transaksi->id) }}" method="POST" enctype="multipart/form-data"
                class="bg-white rounded-3xl border border-gray-100 shadow-sm p-6 space-y-4 text-xs"
                x-data="transaksiForm({
                    jenis: '{{ old('jenis', $transaksi->jenis) }}',
                    bidangKerjaId: '{{ old('bidang_kerja_id', $transaksi->bidang_kerja_id) }}',
                    tahunAnggaranId: '{{ old('tahun_anggaran_id', $transaksi->tahun_anggaran_id) }}',
                    pendapatanList: {{ Js::from($rencanaPendapatanList) }},
                    pengeluaranList: {{ Js::from($rencanaPengeluaranList) }},
                    typeModel: '{!! addslashes(old('transaksable_type', $transaksi->transaksable_type)) !!}',
                    rawJumlah: '{{ old('jumlah', intval($transaksi->jumlah)) }}',
                    oldTransaksableId: '{{ old('transaksable_id', $transaksi->transaksable_id) }}'
                })">
                @csrf
                @method('PUT')

            <input type="hidden" name="transaksable_type" :value="typeModel">

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                {{-- Kode Transaksi (Input Manual) --}}
                <div>
                    <label class="block font-semibold text-gray-700 mb-1.5">Kode Transaksi / No. Kuitansi <span class="text-red-500">*</span></label>
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
                    <select name="tahun_anggaran_id" x-model="tahunAnggaranId"
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
                        <option value="pemasukan">Masuk (Pemasukan / Pendapatan)</option>
                        <option value="pengeluaran">Keluar (Pengeluaran / Beban)</option>
                    </select>
                </div>

                {{-- Bidang Kerja --}}
                <div x-show="jenis === 'pengeluaran'">
                    <label class="block font-semibold text-gray-700 mb-1.5">Departemen / Bidang Kerja</label>
                    <select name="bidang_kerja_id" x-model="bidangKerjaId"
                        class="w-full px-3 py-2 border border-gray-200 rounded-xl focus:outline-none focus:border-primary focus:ring-2 focus:ring-primary/20 bg-white text-gray-900">
                        <option value="">-- Pilih Bidang Kerja (Semua) --</option>
                        @foreach ($bidangKerjaList as $bidang)
                            <option value="{{ $bidang->id }}"
                                {{ old('bidang_kerja_id', $transaksi->bidang_kerja_id) == $bidang->id ? 'selected' : '' }}>
                                {{ $bidang->nama }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Kolom kosong --}}
                <div x-show="jenis !== 'pengeluaran'" class="hidden md:block"></div>

                {{-- Referensi Anggaran Terkait --}}
                <div class="md:col-span-2">
                    <label class="block font-semibold text-gray-700 mb-1.5">Alokasi / Referensi Anggaran Terkait</label>
                    <div>
                        <select name="transaksable_id" x-ref="tomSelectEl" placeholder="-- Pilih Alokasi Referensi --"
                            class="w-full px-3 py-2 border border-gray-200 rounded-xl focus:outline-none focus:border-primary focus:ring-2 focus:ring-primary/20 bg-white text-gray-900">
                        </select>
                    </div>
                </div>

                {{-- Jumlah Nominal --}}
                <div>
                    <label class="block font-semibold text-gray-700 mb-1.5">Jumlah Nominal (Rp)</label>
                    <input type="text" x-model="formattedJumlah" @input="updateJumlah($event.target.value)"
                        class="w-full px-3 py-2 border border-gray-200 rounded-xl focus:outline-none focus:border-primary focus:ring-2 focus:ring-primary/20 bg-white text-gray-900 @error('jumlah') border-red-500 @enderror">
                    <input type="hidden" name="jumlah" x-model="rawJumlah">
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

                {{-- Upload File Bukti --}}
                <div>
                    <label class="block font-semibold text-gray-700 mb-1.5">Ganti File Bukti / Nota (Biarkan kosong jika
                        tetap)</label>
                    <input type="file" name="file_bukti"
                        class="w-full px-3 py-1.5 border border-gray-200 rounded-xl focus:outline-none bg-white text-gray-500 file:mr-4 file:py-1 file:px-3 file:rounded-md file:border-0 file:text-xs file:font-semibold file:bg-gray-100 file:text-gray-700 hover:file:bg-gray-200">

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
                    class="inline-flex items-center gap-2 bg-gold text-gray-900 px-6 py-2.5 rounded-full text-sm font-semibold shadow hover:bg-gold-dark hover:shadow-md transition-all duration-200 active:scale-95">
                    Perbarui Transaksi
                </button>
            </div>

            </form>
        </div>
        
        {{-- Side Info --}}
        <div class="lg:col-span-1 space-y-4">
            
            {{-- Info Data --}}
            <div class="bg-white border border-gray-100 rounded-3xl p-5 space-y-3 shadow-sm">
                <div class="flex items-center gap-2 text-gray-600">
                    <span class="material-symbols-outlined text-[18px]">info</span>
                    <h3 class="text-sm font-semibold">Info Data</h3>
                </div>
                <div class="text-xs space-y-2">
                    <div class="flex justify-between">
                        <span class="text-gray-500">Dibuat pada</span>
                        <span class="font-medium text-gray-700">{{ $transaksi->created_at->format('d M Y') }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-500">Terakhir diubah</span>
                        <span class="font-medium text-gray-700">{{ $transaksi->updated_at->format('d M Y') }}</span>
                    </div>
                </div>
            </div>

            {{-- Tips --}}
            <div class="bg-primary/5 border border-primary/10 rounded-3xl p-5 space-y-3">
                <div class="flex items-center gap-2 text-primary">
                    <span class="material-symbols-outlined text-[18px]">info</span>
                    <h3 class="text-sm font-semibold">Petunjuk Edit</h3>
                </div>
                <ul class="text-xs text-gray-600 space-y-2 list-disc pl-4">
                    <li>Jika Anda mengubah <strong>Jenis Arus Kas</strong>, Anda wajib memilih ulang <strong>Alokasi Referensi</strong> karena referensi datanya akan berbeda.</li>
                    <li>Mengubah <strong>Jumlah Nominal</strong> akan secara otomatis mengupdate perhitungan serapan / realisasi di Dashboard.</li>
                    <li>Pastikan Anda melampirkan ulang file bukti (opsional) jika ada perubahan bukti transaksi.</li>
                </ul>
            </div>
            
        </div>

    </div>
    
    @push('scripts')
        <script src="{{ asset('js/transaksi-form.js') }}"></script>
    @endpush
</x-app-layout>
