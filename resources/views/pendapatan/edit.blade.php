<x-app-layout>
    <x-slot:title>Rencana Pendapatan</x-slot>

    {{-- Header --}}
    <div class="border-b border-gray-100 pb-6">
        <h2 class="text-lg font-semibold text-gray-900">
            Edit Pendapatan
        </h2>

        <p class="text-sm text-gray-500 mt-1">
            Perbarui informasi sumber pendapatan
            <span class="font-medium text-gray-700">{{ $pendapatan->nama_sumber }}</span>
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

            <form method="POST" action="{{ route('pendapatan.update', $pendapatan->id) }}"
                class="bg-white rounded-3xl border border-gray-100 shadow-sm p-6 space-y-5">
                @csrf
                @method('PUT')

                {{-- Nama Sumber Pendapatan --}}
                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1.5">
                        Nama Sumber Pendapatan <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="nama_sumber" value="{{ old('nama_sumber', $pendapatan->nama_sumber) }}"
                        placeholder="Contoh: Iuran Anggota Aktif"
                        class="w-full px-4 py-2.5 text-sm rounded-xl border border-gray-200 focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none @error('nama_sumber') @enderror">
                    @error('nama_sumber')
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
                            <option value="{{ $kategori->id }}" @selected(old('kategori_pendapatan_id', $pendapatan->kategori_pendapatan_id) == $kategori->id)>
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
                        class="w-full px-4 py-2.5 text-sm rounded-xl border border-gray-200 focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none resize-none @error('keterangan') @enderror">{{ old('keterangan', $pendapatan->keterangan) }}</textarea>
                    @error('keterangan')
                        <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Rencana Anggaran --}}
                @php
                    $rencanaValue = old('jumlah_rencana', $pendapatan->jumlah_rencana);
                @endphp
                <div x-data="{
                    raw: '{{ $rencanaValue }}',
                    display: '{{ number_format((float) $rencanaValue, 0, ',', '.') }}',
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
                            class="w-full pl-10 pr-4 py-2.5 text-sm rounded-xl border focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none @error('jumlah_rencana') border-red-300 @enderror">
                    </div>
                    <input type="hidden" name="jumlah_rencana" :value="raw">
                    @error('jumlah_rencana')
                        <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Actions --}}
                <div class="flex justify-between items-center gap-3 pt-2 border-t border-gray-100">

                    <button type="button" onclick="document.getElementById('delete-form').submit()"
                        class="inline-flex items-center gap-2 text-red-500 text-sm font-medium hover:text-red-600 transition">
                        <span class="material-symbols-outlined text-[18px]">delete</span>
                        Hapus Pendapatan
                    </button>

                    <div class="flex gap-3">
                        <a href="{{ route('pendapatan.index') }}"
                            class="px-5 py-2.5 rounded-xl border border-gray-200 text-gray-600 text-sm font-medium hover:bg-gray-50 transition">
                            Batal
                        </a>
                        <button type="submit"
                            class="inline-flex items-center gap-2 bg-primary text-white px-5 py-2.5 rounded-xl text-sm font-medium hover:bg-primary-dark transition">
                            <span class="material-symbols-outlined text-[18px]">save</span>
                            Simpan Perubahan
                        </button>
                    </div>

                </div>

            </form>

            <form id="delete-form" method="POST" action="{{ route('pendapatan.destroy', $pendapatan->id) }}"
                class="hidden"
                onsubmit="return confirm('Hapus pendapatan {{ $pendapatan->nama_sumber }}? Tindakan ini tidak dapat dibatalkan.')">
                @csrf
                @method('DELETE')
            </form>

        </div>

        {{-- Side Info: Status Realisasi --}}
        <div class="lg:col-span-1 space-y-4">

            @php
                $sisa = $pendapatan->jumlah_rencana - $pendapatan->jumlah_realisasi;
                $persen =
                    $pendapatan->jumlah_rencana > 0
                        ? round(($pendapatan->jumlah_realisasi / $pendapatan->jumlah_rencana) * 100)
                        : 0;
                $tercapai = $persen >= 100;
            @endphp

            <div class="bg-white rounded-3xl border border-gray-100 shadow-sm p-5">
                <h3 class="text-sm font-semibold text-gray-900 mb-4">
                    Status Realisasi
                </h3>

                <div class="space-y-3">
                    <div class="flex items-center justify-between">
                        <span class="text-xs text-gray-500">Rencana</span>
                        <span class="text-sm font-semibold text-gray-900">
                            Rp {{ number_format($pendapatan->jumlah_rencana, 0, ',', '.') }}
                        </span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-xs text-gray-500">Realisasi</span>
                        <span class="text-sm font-semibold text-green-600">
                            Rp {{ number_format($pendapatan->jumlah_realisasi, 0, ',', '.') }}
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
                                <div class="h-full bg-primary" style="width: {{ min($persen, 100) }}%"></div>
                            </div>
                            <span class="text-xs font-medium text-gray-500">{{ $persen }}%</span>
                        </div>
                    </div>

                    <div class="pt-1">
                        <span
                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[11px] font-medium {{ $tercapai ? 'bg-emerald-50 text-emerald-700' : 'bg-gray-100 text-gray-600' }}">
                            {{ $tercapai ? 'Tercapai' : 'Belum' }}
                        </span>
                    </div>
                </div>
            </div>

            <div class="bg-amber-50 border border-amber-100 rounded-3xl p-5 space-y-2">
                <div class="flex items-center gap-2 text-amber-700">
                    <span class="material-symbols-outlined text-[18px]">warning</span>
                    <h3 class="text-sm font-semibold">Perhatian</h3>
                </div>
                <p class="text-xs text-gray-600">
                    Mengubah nilai Rencana Anggaran tidak akan mengubah data realisasi yang sudah tercatat pada
                    menu Aktivitas & Realisasi.
                </p>
            </div>

        </div>

    </div>

</x-app-layout>
