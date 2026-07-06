<x-app-layout>
    <x-slot:title>Rencana Pendapatan</x-slot>

    {{-- Header --}}
    <div class="border-b border-gray-100 pb-6">
        <h2 class="text-lg font-semibold text-gray-900">
            Tambah Pendapatan Baru
        </h2>

        <p class="text-sm text-gray-500 mt-1">
            Lengkapi form berikut untuk menambahkan sumber pendapatan baru
        </p>
    </div>

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

            <form method="POST" action="{{ route('pendapatan.store') }}"
                class="bg-white rounded-3xl border border-gray-100 shadow-sm p-6 space-y-5">
                @csrf

                {{-- Nama Sumber Pendapatan --}}
                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1.5">
                        Nama Sumber Pendapatan <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="nama_sumber" value="{{ old('nama_sumber') }}"
                        placeholder="Contoh: Iuran Anggota Aktif"
                        class="w-full px-4 py-2.5 text-sm rounded-xl border border-gray-200 focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none @error('nama') @enderror">
                    @error('nama')
                        <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1.5">
                        Tahun Anggaran <span class="text-red-500">*</span>
                    </label>

                    <select name="tahun_anggaran_id"
                        class="w-full px-4 py-2.5 text-sm rounded-xl border border-gray-200 focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none bg-white @error('tahun_anggaran_id') @enderror">

                        <option value="">Pilih Tahun Anggaran</option>

                        @foreach ($tahunAnggaranList as $tahun)
                            <option value="{{ $tahun->id }}" @selected(old('tahun_anggaran_id') == $tahun->id)>
                                {{ $tahun->label ?? $tahun->tahun }}
                            </option>
                        @endforeach

                    </select>

                    @error('tahun_anggaran_id')
                        <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Kategori --}}
                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1.5">
                        Kategori <span class="text-red-500">*</span>
                    </label>
                    <select name="kategori_pendapatan_id"
                        class="w-full px-4 py-2.5 text-sm rounded-xl border border-gray-200 focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none bg-white @error('kategori_pendapatan_id') @enderror">
                        <option value="">Pilih kategori</option>
                        @foreach ($kategoriList as $kategori)
                            <option value="{{ $kategori->id }}" @selected(old('kategori_pendapatan_id') == $kategori->id)>
                                {{ $kategori->nama }}
                            </option>
                        @endforeach
                    </select>
                    @error('kategori_pendapatan_id')
                        <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Deskripsi --}}
                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1.5">
                        Keterangan / Rincian Perhitungan
                    </label>
                    <textarea name="keterangan" rows="3" placeholder="Contoh: Iuran bulanan anggota aktif @Rp 200.000 × 120 anggota"
                        class="w-full px-4 py-2.5 text-sm rounded-xl border border-gray-200 focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none resize-none @error('keterangan') @enderror">{{ old('keterangan') }}</textarea>
                    @error('keterangan')
                        <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Rencana Anggaran --}}
                <div x-data="{
                    raw: '{{ old('rencana') }}',
                    display: '{{ old('rencana') ? number_format((float) old('rencana'), 0, ',', '.') : '' }}',
                    format(e) {
                        let v = e.target.value.replace(/\D/g, '');
                        this.raw = v;
                        this.display = v ? new Intl.NumberFormat('id-ID').format(v) : '';
                        e.target.value = this.display;
                    }
                }">
                    <label class="block text-xs font-semibold text-gray-700 mb-1.5">
                        Rencana Anggaran <span class="text-red-500">*</span>
                    </label>
                    <div class="relative">
                        <span
                            class="absolute left-4 top-1/2 -translate-y-1/2 text-sm text-gray-400 font-medium">Rp</span>
                        <input type="text" inputmode="numeric" x-model="display" @input="format($event)"
                            placeholder="0"
                            class="w-full pl-10 pr-4 py-2.5 text-sm rounded-xl border border-gray-200 focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none @error('rencana') @enderror">
                    </div>
                    <input type="hidden" name="jumlah_rencana" :value="raw">
                    @error('rencana')
                        <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Actions --}}
                <div class="flex justify-end gap-3 pt-2 border-t border-gray-100">
                    <a href="{{ route('pendapatan.index') }}"
                        class="px-6 py-2.5 rounded-full border border-gray-200 text-gray-600 text-sm font-medium hover:bg-gray-100 hover:border-gray-300 transition-all duration-200 active:scale-95">
                        Batal
                    </a>
                    <button type="submit"
                        class="inline-flex items-center gap-2 bg-gold text-gray-900 px-6 py-2.5 rounded-full text-sm font-semibold shadow hover:bg-gold-dark hover:shadow-md transition-all duration-200 active:scale-95">
                        <span class="material-symbols-outlined text-[18px]">save</span>
                        Simpan Pendapatan
                    </button>
                </div>

            </form>

        </div>

        {{-- Side Info --}}
        <div class="lg:col-span-1">
            <div class="bg-primary/5 border border-primary/10 rounded-3xl p-5 space-y-3">
                <div class="flex items-center gap-2 text-primary">
                    <span class="material-symbols-outlined text-[18px]">info</span>
                    <h3 class="text-sm font-semibold">Tips Pengisian</h3>
                </div>
                <ul class="text-xs text-gray-600 space-y-2 list-disc pl-4">
                    <li>Gunakan nama sumber pendapatan yang spesifik dan mudah dikenali.</li>
                    <li>Tuliskan rincian perhitungan pada kolom keterangan, misalnya tarif dan jumlah unit.</li>
                    <li>Nilai realisasi akan terisi otomatis saat ada transaksi pada menu Aktivitas & Realisasi.</li>
                    <li>Status "Belum" akan berubah menjadi "Tercapai" jika realisasi mencapai 100% dari rencana.</li>
                </ul>
            </div>
        </div>

    </div>

</x-app-layout>
