<x-app-layout>
    <x-slot:title>Dashboard Keuangan</x-slot>

    {{-- Page Header --}}
    <div class="mb-4 sm:mb-6">
        <p class="text-xs sm:text-sm text-gray-500 mt-1">
            Ringkasan posisi anggaran dan capaian mutu PIPMARSI tahun berjalan
        </p>
    </div>

    {{-- Row 1: Stat Cards --}}
    {{-- Diubah menjadi: 1 kolom (mobile), 2 kolom (tablet), 4 kolom (desktop) --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-5 mb-6">

        <x-stat-card label="Pendapatan Rencana" :value="'Rp ' . number_format($totalPendapatanRencana, 0, ',', '.')" sub="Seluruh sumber" icon="payments" variant="teal" />

        <x-stat-card label="Realisasi Pendapatan" :value="'Rp ' . number_format($realisasiPendapatan, 0, ',', '.')" sub="0% dari rencana" icon="trending_up"
            variant="green" />

        <x-stat-card label="Total Anggaran Belanja" :value="'Rp ' . number_format($totalAnggaranBelanja, 0, ',', '.')" sub="Per bidang kerja"
            icon="account_balance_wallet" variant="navy" />

        <x-stat-card label="Realisasi Pengeluaran" :value="'Rp ' . number_format($realisasiPengeluaran, 0, ',', '.')" sub="0% dari anggaran" icon="receipt_long"
            variant="red" />
    </div>

    {{-- Row 2: Saldo, Capaian, Peringatan --}}
    {{-- Diubah menjadi: 1 kolom (mobile), 2 kolom (tablet), 3 kolom (desktop besar) --}}
    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-4 sm:gap-6 mb-6">

        {{-- Saldo & Serapan --}}
        <div class="bg-white rounded-2xl p-4 sm:p-6 shadow-sm border border-white/10">
            <h3 class="font-bold text-[#0a2419] mb-4 sm:mb-5 flex items-center gap-2 text-sm sm:text-base">
                <span class="text-yellow-500">🏦</span> Saldo & Serapan
            </h3>

            <div class="space-y-4">
                <div class="flex justify-between items-center py-2 sm:py-3 border-b border-gray-100">
                    <span class="text-xs sm:text-sm text-gray-600">Saldo Kas</span>
                    <span class="text-xs sm:text-sm font-bold text-[#0a2419] text-right ml-2">
                        Rp {{ number_format($stats['saldo_kas'] ?? 0, 0, ',', '.') }}
                    </span>
                </div>

                <div class="flex justify-between items-center py-2">
                    <span class="text-xs sm:text-sm text-gray-600">Serapan Anggaran</span>
                    <span class="text-xs sm:text-sm font-bold text-teal-600">0%</span>
                </div>

                <div class="w-full h-2 bg-gray-100 rounded-full overflow-hidden">
                    <div class="h-full bg-teal-600 w-[0%] transition-all duration-500"></div>
                </div>
            </div>
        </div>

        {{-- Capaian Mutu --}}
        <div class="bg-white rounded-2xl p-4 sm:p-6 shadow-sm border border-white/10">
            <h3 class="font-bold text-[#0a2419] mb-4 sm:mb-5 flex items-center gap-2 text-sm sm:text-base">
                <span class="text-yellow-500">🎯</span> Capaian Indikator Mutu
            </h3>

            {{-- Dibuat fleksibel: menumpuk ke bawah di mobile kecil, menyamping di layar lebih besar --}}
            <div class="flex flex-row items-center gap-4 sm:gap-5">
                <div
                    class="w-16 h-16 sm:w-20 sm:h-20 shrink-0 rounded-full border-4 border-gray-100 flex items-center justify-center">
                    <span class="text-base sm:text-lg font-bold text-[#0a2419]">0%</span>
                </div>

                <div>
                    <p class="text-base sm:text-lg font-bold text-[#0a2419]">0 Tercapai</p>
                    <p class="text-[10px] sm:text-xs text-gray-400">dari 31 indikator</p>
                    <p class="text-[10px] sm:text-xs text-red-500 mt-1 font-medium line-clamp-2">
                        Perlu evaluasi menyeluruh
                    </p>
                </div>
            </div>
        </div>

        {{-- Peringatan --}}
        {{-- Mengambil lebar penuh (col-span-2) jika di layar tablet (md) agar tidak menyisakan ruang kosong yang aneh --}}
        <div class="bg-white rounded-2xl p-4 sm:p-6 shadow-sm border border-white/10 md:col-span-2 xl:col-span-1">
            <h3 class="font-bold text-[#0a2419] mb-4 sm:mb-5 flex items-center gap-2 text-sm sm:text-base">
                <span class="text-yellow-500">⚠️</span> Peringatan
            </h3>

            <div class="flex items-start sm:items-center gap-2 text-xs sm:text-sm text-gray-500">
                <span class="text-green-500 mt-0.5 sm:mt-0">●</span>
                <span>Tidak ada peringatan.</span>
            </div>
        </div>

    </div>

    {{-- Row 3: Aktivitas & Serapan Bidang --}}
    {{-- Diubah menjadi: 1 kolom (mobile), 2 kolom (tablet ke atas) --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 sm:gap-6">

        {{-- Aktivitas --}}
        <div class="bg-white rounded-2xl p-4 sm:p-6 shadow-sm border border-white/10">
            <h3 class="font-bold text-[#0a2419] mb-4 sm:mb-5 flex items-center gap-2 text-sm sm:text-base">
                <span class="text-yellow-500">📋</span> Aktivitas Terbaru
            </h3>

            @if (isset($aktivitasTerbaru) && $aktivitasTerbaru->count())
                <ul class="space-y-3">
                    @foreach ($aktivitasTerbaru as $aktivitas)
                        <li class="flex items-start gap-3 text-xs sm:text-sm">
                            <span class="w-2 h-2 rounded-full bg-teal-600 shrink-0 mt-1.5"></span>
                            <span class="text-gray-600">{{ $aktivitas->keterangan }}</span>
                        </li>
                    @endforeach
                </ul>
            @else
                <div class="text-xs sm:text-sm text-gray-400 flex items-center gap-2">
                    <span class="w-2 h-2 rounded-full bg-gray-300 shrink-0"></span>
                    Belum ada aktivitas.
                </div>
            @endif
        </div>

        {{-- Serapan Bidang --}}
        <div class="bg-white rounded-2xl p-4 sm:p-6 shadow-sm border border-white/10">
            <h3 class="font-bold text-[#0a2419] mb-4 sm:mb-5 flex items-center gap-2 text-sm sm:text-base">
                <span class="text-yellow-500">📊</span> Serapan Per Bidang
            </h3>

            <div class="space-y-4">
                @foreach ([['label' => 'Pengembangan Organisasi', 'icon' => '🏛️'], ['label' => 'Pendidikan', 'icon' => '📚'], ['label' => 'Penelitian & Pengabmas', 'icon' => '🔬'], ['label' => 'Publikasi', 'icon' => '📰'], ['label' => 'Kerjasama Antar Lembaga', 'icon' => '🤝']] as $bidang)
                    <div class="flex items-start sm:items-center justify-between text-xs sm:text-sm gap-2">
                        <span class="text-gray-600 flex items-start sm:items-center gap-2 leading-tight">
                            <span class="shrink-0">{{ $bidang['icon'] }}</span>
                            <span>{{ $bidang['label'] }}</span>
                        </span>

                        <span class="font-bold text-yellow-600 shrink-0">
                            0%
                        </span>
                    </div>

                    <div class="w-full h-1.5 bg-gray-100 rounded-full overflow-hidden mt-1 sm:mt-0">
                        <div class="h-full bg-teal-600 w-0 transition-all duration-500"></div>
                    </div>
                @endforeach
            </div>
        </div>

    </div>

</x-app-layout>
