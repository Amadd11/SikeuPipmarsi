<x-app-layout>
    <x-slot:title>Rencana Pengeluaran</x-slot>

    {{-- Header --}}
    <div class="border-b border-gray-100 pb-6">
        <h2 class="text-lg font-semibold text-gray-900">Tambah Rencana Pengeluaran</h2>
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

                @include('pengeluaran.form')


                {{-- Actions --}}
                <div class="flex justify-end gap-3 pt-2 border-t border-gray-100">
                    <a href="{{ route('pengeluaran.index') }}"
                        class="px-6 py-2.5 rounded-full border border-gray-200 text-gray-600 text-sm font-medium hover:bg-gray-100 hover:border-gray-300 transition-all duration-200 active:scale-95">
                        Batal
                    </a>
                    <button type="submit"
                        class="inline-flex items-center gap-2 bg-gold text-gray-900 px-6 py-2.5 rounded-full text-sm font-semibold shadow hover:bg-gold-dark hover:shadow-md transition-all duration-200 active:scale-95">
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
