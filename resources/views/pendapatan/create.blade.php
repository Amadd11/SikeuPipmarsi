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

                @include('pendapatan.form')

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
