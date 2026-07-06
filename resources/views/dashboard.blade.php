<x-app-layout>
    <x-slot:title>Dashboard</x-slot>

    {{-- ── Greeting & Tahun ──────────────────────────────────────────────── --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-8">
        <div>
            <div class="flex items-center gap-2 mb-1">
                <h1 class="text-2xl font-bold text-gray-900 tracking-tight">Ringkasan Eksekutif</h1>
                @if ($tahunAktif)
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-gold/20 text-amber-700 uppercase tracking-widest border border-gold/30 shadow-sm">
                        {{ $tahunAktif->label ?? $tahunAktif->tahun }}
                    </span>
                @else
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-red-100 text-red-700 uppercase tracking-widest">
                        Belum Ada TA
                    </span>
                @endif
            </div>
            <p class="text-xs text-gray-500">Pantau kinerja keuangan dan mutu secara real-time.</p>
        </div>
        
        <div class="inline-flex items-center gap-2 text-xs font-semibold text-gray-600 bg-white border border-gray-200 px-4 py-2 rounded-full shadow-sm hover:shadow-md transition-shadow cursor-default">
            <span class="material-symbols-outlined text-[16px] text-primary" style="font-variation-settings: 'FILL' 1">calendar_today</span>
            {{ now()->translatedFormat('d F Y') }}
        </div>
    </div>

    {{-- ── Row 1: Stat Cards Keuangan ──────────────────────────────────────── --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5 mb-6">
        <x-stat-card
            label="Rencana Pendapatan"
            :value="'Rp ' . number_format($totalPendapatanRencana, 0, ',', '.')"
            :sub="'Realisasi ' . $pctPendapatan . '% dari target'"
            icon="monetization_on"
            variant="primary" />

        <x-stat-card
            label="Realisasi Pendapatan"
            :value="'Rp ' . number_format($realisasiPendapatan, 0, ',', '.')"
            :sub="number_format($realisasiPendapatan / 1000000, 1) . ' juta terkumpul'"
            icon="trending_up"
            variant="success" />

        <x-stat-card
            label="Anggaran Belanja"
            :value="'Rp ' . number_format($totalAnggaranBelanja, 0, ',', '.')"
            :sub="'Serapan ' . $serapan . '% dari anggaran'"
            icon="account_balance_wallet"
            variant="warning" />

        <x-stat-card
            label="Realisasi Pengeluaran"
            :value="'Rp ' . number_format($realisasiPengeluaran, 0, ',', '.')"
            :sub="number_format($realisasiPengeluaran / 1000000, 1) . ' juta terpakai'"
            icon="receipt_long"
            variant="danger" />
    </div>

    {{-- ── Row 2: Saldo + Mutu + Audit/Tarif ──────────────────────────────── --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-5 mb-6">

        {{-- Saldo & Serapan (Minimalist) --}}
        <div class="bg-white rounded-3xl border border-gray-100 shadow-[0_2px_15px_-3px_rgba(0,0,0,0.04)] p-6 flex flex-col justify-between lg:col-span-1">
            <div class="flex items-center gap-3 mb-6">
                <div class="w-10 h-10 rounded-2xl bg-teal-50 flex items-center justify-center shrink-0">
                    <span class="material-symbols-outlined text-teal-600 text-[20px]" style="font-variation-settings: 'FILL' 1">account_balance</span>
                </div>
                <h3 class="text-gray-800 text-sm font-bold tracking-wide">Posisi Kas & Serapan</h3>
            </div>

            <div class="mb-6">
                <p class="text-[10px] text-gray-400 uppercase font-semibold tracking-widest mb-1">Saldo Kas Tersedia</p>
                <p class="text-3xl font-bold {{ $saldoKas >= 0 ? 'text-gray-800' : 'text-rose-500' }} tracking-tight">
                    Rp {{ number_format(abs($saldoKas), 0, ',', '.') }}
                    @if ($saldoKas < 0) <span class="text-sm opacity-80 ml-1">(Defisit)</span> @endif
                </p>
            </div>

            <div class="space-y-4">
                {{-- Serapan Progress --}}
                <div class="bg-gray-50 rounded-xl p-3 border border-gray-100/50">
                    <div class="flex justify-between text-xs mb-2">
                        <span class="text-gray-500 ">Serapan Anggaran</span>
                        <span class="text-gray-800">{{ $serapan }}%</span>
                    </div>
                    <div class="w-full h-1.5 bg-gray-200 rounded-full overflow-hidden">
                        <div class="h-full rounded-full transition-all duration-700
                            {{ $serapan >= 90 ? 'bg-rose-500' : ($serapan >= 70 ? 'bg-amber-400' : 'bg-teal-500') }}"
                            style="width: {{ min($serapan, 100) }}%"></div>
                    </div>
                </div>

                {{-- Pendapatan Progress --}}
                <div class="bg-gray-50 rounded-xl p-3 border border-gray-100/50">
                    <div class="flex justify-between text-xs mb-2">
                        <span class="text-gray-500">Realisasi Pendapatan</span>
                        <span class="text-gray-800">{{ $pctPendapatan }}%</span>
                    </div>
                    <div class="w-full h-1.5 bg-gray-200 rounded-full overflow-hidden">
                        <div class="h-full bg-teal-500 rounded-full transition-all duration-700"
                            style="width: {{ min($pctPendapatan, 100) }}%"></div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Capaian Indikator Mutu --}}
        <div class="bg-white rounded-3xl border border-gray-100 shadow-[0_2px_15px_-3px_rgba(0,0,0,0.04)] p-6 flex flex-col justify-between group">
            <div class="flex items-center justify-between mb-6">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-2xl bg-indigo-50 flex items-center justify-center shrink-0">
                        <span class="material-symbols-outlined text-indigo-600 text-[20px]" style="font-variation-settings: 'FILL' 1">verified</span>
                    </div>
                    <h3 class="font-bold text-gray-800 text-sm tracking-wide">Indikator Mutu</h3>
                </div>
                <a href="{{ route('indikator-mutu.index') }}" class="w-8 h-8 rounded-full bg-gray-50 flex items-center justify-center text-gray-400 hover:bg-primary hover:text-white transition-colors duration-200">
                    <span class="material-symbols-outlined text-[16px]">arrow_forward</span>
                </a>
            </div>

            <div class="flex flex-col sm:flex-row items-center gap-6">
                <div class="relative w-28 h-28 shrink-0">
                    <svg class="w-28 h-28 -rotate-90 transform group-hover:scale-105 transition-transform duration-500" viewBox="0 0 36 36">
                        <circle cx="18" cy="18" r="15.9" fill="none" stroke="#f3f4f6" stroke-width="3"/>
                        @if ($totalIndikator > 0)
                        <circle cx="18" cy="18" r="15.9" fill="none"
                            stroke="{{ $persenTercapai >= 70 ? '#10b981' : ($persenTercapai >= 40 ? '#f59e0b' : '#7c3aed') }}"
                            stroke-width="3"
                            stroke-dasharray="{{ $persenTercapai }}, 100"
                            stroke-linecap="round"/>
                        @endif
                    </svg>
                    <div class="absolute inset-0 flex flex-col items-center justify-center">
                        <span class="text-2xl font-black text-gray-800 tracking-tighter">{{ $persenTercapai }}%</span>
                        <span class="text-[9px] text-gray-400 uppercase font-bold tracking-widest mt-0.5">Tercapai</span>
                    </div>
                </div>
                
                <div class="flex-1 w-full space-y-2.5">
                    <div class="flex items-center justify-between p-2 rounded-lg hover:bg-gray-50 transition-colors">
                        <span class="flex items-center gap-2 text-xs font-semibold text-gray-600">
                            <span class="w-2.5 h-2.5 rounded-md bg-emerald-500 shadow-sm"></span>Tercapai
                        </span>
                        <span class="font-bold text-gray-900 text-sm">{{ $tercapai }}</span>
                    </div>
                    <div class="flex items-center justify-between p-2 rounded-lg hover:bg-gray-50 transition-colors">
                        <span class="flex items-center gap-2 text-xs font-semibold text-gray-600">
                            <span class="w-2.5 h-2.5 rounded-md bg-amber-400 shadow-sm"></span>Proses
                        </span>
                        <span class="font-bold text-gray-900 text-sm">{{ $proses }}</span>
                    </div>
                    <div class="flex items-center justify-between p-2 rounded-lg hover:bg-gray-50 transition-colors">
                        <span class="flex items-center gap-2 text-xs font-semibold text-gray-600">
                            <span class="w-2.5 h-2.5 rounded-md bg-rose-500 shadow-sm"></span>Tdk Tercapai
                        </span>
                        <span class="font-bold text-gray-900 text-sm">{{ $tidakTercapai }}</span>
                    </div>
                </div>
            </div>
        </div>

        {{-- Audit & Standar Tarif --}}
        <div class="flex flex-col gap-5">
            {{-- Audit Monitoring --}}
            <div class="bg-white rounded-3xl border border-gray-100 shadow-[0_2px_15px_-3px_rgba(0,0,0,0.04)] p-5 flex-1 relative overflow-hidden group">
                <div class="absolute -right-4 -bottom-4 w-24 h-24 bg-violet-100 rounded-full blur-[30px] opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
                <div class="flex justify-between mb-4 relative z-10">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-2xl bg-violet-50 flex items-center justify-center shrink-0">
                            <span class="material-symbols-outlined text-violet-600 text-[20px]" style="font-variation-settings: 'FILL' 1">manage_search</span>
                        </div>
                        <h3 class="font-bold text-gray-800 text-sm tracking-wide">Audit</h3>
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-3 text-center relative z-10">
                    <div class="bg-gray-50/80 border border-gray-100 rounded-2xl p-3">
                        <p class="text-3xl font-black text-gray-900 tracking-tighter">{{ $totalAudit }}</p>
                        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mt-1">Total</p>
                    </div>
                    <div class="bg-emerald-50 border border-emerald-100/50 rounded-2xl p-3">
                        <p class="text-3xl font-black text-emerald-600 tracking-tighter">{{ $auditSelesai }}</p>
                        <p class="text-[10px] font-bold text-emerald-600/80 uppercase tracking-widest mt-1">Selesai</p>
                    </div>
                </div>
            </div>

            {{-- Standar Tarif --}}
            <div class="bg-white rounded-3xl border border-gray-100 shadow-[0_2px_15px_-3px_rgba(0,0,0,0.04)] p-5 relative overflow-hidden group">
                <div class="absolute -right-4 -bottom-4 w-24 h-24 bg-rose-100 rounded-full blur-[30px] opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
                <div class="flex items-center justify-between relative z-10">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-2xl bg-rose-50 flex items-center justify-center shrink-0">
                            <span class="material-symbols-outlined text-rose-500 text-[20px]" style="font-variation-settings: 'FILL' 1">description</span>
                        </div>
                        <div>
                            <p class="text-sm font-bold text-gray-800 tracking-wide">Standar Tarif</p>
                            <a href="{{ route('standar-tarif.index') }}" class="text-[10px] text-primary font-bold uppercase tracking-widest hover:underline">Kelola Data →</a>
                        </div>
                    </div>
                    <p class="text-3xl font-black text-gray-900 tracking-tighter">{{ $totalStandarTarif }}</p>
                </div>
            </div>
        </div>
    </div>

    {{-- ── Row 3: Serapan Bidang + Transaksi Terbaru + Peringatan ─────────── --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-5">

        {{-- Serapan Per Bidang --}}
        <div class="lg:col-span-1 bg-white rounded-3xl border border-gray-100 shadow-[0_2px_15px_-3px_rgba(0,0,0,0.04)] flex flex-col overflow-hidden">
            <div class="p-5 border-b border-gray-100 flex items-center justify-between bg-gray-50/50">
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 rounded-xl bg-amber-50 flex items-center justify-center">
                        <span class="material-symbols-outlined text-amber-600 text-[18px]" style="font-variation-settings: 'FILL' 1">bar_chart</span>
                    </div>
                    <h3 class="font-bold text-gray-800 text-sm tracking-wide">Serapan Per Bidang</h3>
                </div>
            </div>

            <div class="p-5 flex-1 overflow-y-auto max-h-[340px] space-y-5">
                @forelse ($serapanBidang as $bidang)
                    <div class="group">
                        <div class="flex items-center justify-between text-xs mb-2">
                            <span class="font-semibold text-gray-700 flex items-center gap-2">
                                <span class="w-1.5 h-1.5 rounded-full {{ $bidang->pct_serapan >= 90 ? 'bg-rose-500' : ($bidang->pct_serapan >= 60 ? 'bg-amber-500' : 'bg-primary') }}"></span>
                                {{ Str::limit($bidang->nama, 28) }}
                            </span>
                            <span class="font-bold text-[11px] px-2 py-0.5 rounded-md bg-gray-100
                                {{ $bidang->pct_serapan >= 90 ? 'text-rose-600 bg-rose-50' : ($bidang->pct_serapan >= 60 ? 'text-amber-600 bg-amber-50' : 'text-gray-600') }}">
                                {{ $bidang->pct_serapan }}%
                            </span>
                        </div>
                        <div class="w-full h-1.5 bg-gray-100 rounded-full overflow-hidden">
                            <div class="h-full rounded-full transition-all duration-1000 ease-out group-hover:opacity-80
                                {{ $bidang->pct_serapan >= 90 ? 'bg-rose-500' : ($bidang->pct_serapan >= 60 ? 'bg-amber-500' : 'bg-primary') }}"
                                style="width: {{ min($bidang->pct_serapan, 100) }}%"></div>
                        </div>
                    </div>
                @empty
                    <div class="flex flex-col items-center justify-center py-10 opacity-50">
                        <span class="material-symbols-outlined text-4xl mb-2">inbox</span>
                        <p class="text-xs text-gray-500 font-medium">Belum ada data bidang kerja.</p>
                    </div>
                @endforelse
            </div>
        </div>

        {{-- Transaksi Terbaru --}}
        <div class="lg:col-span-1 bg-white rounded-3xl border border-gray-100 shadow-[0_2px_15px_-3px_rgba(0,0,0,0.04)] flex flex-col overflow-hidden">
            <div class="p-5 border-b border-gray-100 flex items-center justify-between bg-gray-50/50">
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 rounded-xl bg-blue-50 flex items-center justify-center">
                        <span class="material-symbols-outlined text-blue-600 text-[18px]" style="font-variation-settings: 'FILL' 1">sync_alt</span>
                    </div>
                    <h3 class="font-bold text-gray-800 text-sm tracking-wide">Transaksi Terbaru</h3>
                </div>
                <a href="{{ route('transaksi.index') }}" class="w-7 h-7 rounded-full hover:bg-gray-200 flex items-center justify-center text-gray-400 transition-colors">
                    <span class="material-symbols-outlined text-[16px]">open_in_new</span>
                </a>
            </div>

            <div class="p-3 flex-1 overflow-y-auto max-h-[340px]">
                @forelse ($transaksiTerbaru as $tx)
                    <div class="flex items-center gap-3 p-3 rounded-2xl hover:bg-gray-50 transition-colors group">
                        <div class="w-10 h-10 rounded-xl flex items-center justify-center shrink-0 shadow-sm border border-gray-100
                            {{ $tx->jenis === 'pemasukan' ? 'bg-emerald-50 text-emerald-600' : 'bg-rose-50 text-rose-500' }}">
                            <span class="material-symbols-outlined text-[18px]" style="font-variation-settings: 'FILL' 1">
                                {{ $tx->jenis === 'pemasukan' ? 'arrow_downward' : 'arrow_upward' }}
                            </span>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-xs font-bold text-gray-800 truncate">{{ $tx->uraian ?? '-' }}</p>
                            <p class="text-[10px] text-gray-400 font-medium mt-0.5 tracking-wide">
                                {{ $tx->tanggal?->format('d M') }}
                                @if ($tx->bidangKerja)
                                    <span class="mx-1">•</span> {{ $tx->bidangKerja->kode ?? $tx->bidangKerja->nama }}
                                @endif
                            </p>
                        </div>
                        <div class="text-right shrink-0">
                            <p class="text-xs font-black {{ $tx->jenis === 'pemasukan' ? 'text-emerald-600' : 'text-gray-700' }}">
                                {{ $tx->jenis === 'pemasukan' ? '+' : '-' }}
                                Rp{{ number_format($tx->jumlah / 1000) }}k
                            </p>
                        </div>
                    </div>
                @empty
                    <div class="flex flex-col items-center justify-center py-12 opacity-50">
                        <span class="material-symbols-outlined text-4xl mb-2">receipt_long</span>
                        <p class="text-xs font-medium text-gray-500">Belum ada transaksi.</p>
                    </div>
                @endforelse
            </div>
        </div>

        {{-- Peringatan & Quick Links --}}
        <div class="lg:col-span-1 flex flex-col gap-5">

            {{-- Peringatan --}}
            <div class="bg-white rounded-3xl border border-gray-100 shadow-[0_2px_15px_-3px_rgba(0,0,0,0.04)] p-5 flex-1 flex flex-col">
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-9 h-9 rounded-xl bg-red-50 flex items-center justify-center">
                        <span class="material-symbols-outlined text-red-500 text-[18px]" style="font-variation-settings: 'FILL' 1">notifications_active</span>
                    </div>
                    <h3 class="font-bold text-gray-800 text-sm tracking-wide">Notifikasi</h3>
                </div>

                <div class="space-y-3 overflow-y-auto max-h-[160px] pr-1">
                    @forelse ($peringatan as $p)
                        @php
                            $colors = [
                                'red'   => ['bg' => 'bg-rose-50',   'border' => 'border-rose-100',   'icon' => 'text-rose-500'],
                                'amber' => ['bg' => 'bg-amber-50', 'border' => 'border-amber-100', 'icon' => 'text-amber-600'],
                                'blue'  => ['bg' => 'bg-blue-50',  'border' => 'border-blue-100',  'icon' => 'text-blue-600'],
                            ];
                            $c = $colors[$p['level']] ?? $colors['blue'];
                        @endphp
                        <a href="{{ $p['link'] }}"
                            class="flex items-start gap-3 p-3 rounded-2xl border {{ $c['bg'] }} {{ $c['border'] }} hover:shadow-sm transition-all hover:-translate-y-0.5">
                            <span class="material-symbols-outlined text-[18px] {{ $c['icon'] }} shrink-0 mt-0.5" style="font-variation-settings: 'FILL' 1">{{ $p['icon'] }}</span>
                            <span class="text-[11px] font-semibold text-gray-700 leading-relaxed">{{ $p['message'] }}</span>
                        </a>
                    @empty
                        <div class="flex items-center gap-3 p-4 rounded-2xl bg-emerald-50/50 border border-emerald-100/50">
                            <span class="material-symbols-outlined text-[20px] text-emerald-500" style="font-variation-settings: 'FILL' 1">check_circle</span>
                            <span class="text-xs text-emerald-700 font-semibold">Semua kondisi normal.</span>
                        </div>
                    @endforelse
                </div>
            </div>

            {{-- Quick Access --}}
            <div class="bg-white rounded-3xl border border-gray-100 shadow-[0_2px_15px_-3px_rgba(0,0,0,0.04)] p-5">
                <div class="grid grid-cols-2 gap-3">
                    <a href="{{ route('transaksi.create') }}"
                        class="flex items-center gap-2.5 p-3 rounded-2xl bg-gray-50 border border-gray-100 hover:bg-primary hover:border-primary hover:text-white transition-all group">
                        <span class="material-symbols-outlined text-[18px] text-primary group-hover:text-white transition-colors" style="font-variation-settings: 'FILL' 1">add_circle</span>
                        <span class="text-[10px] font-bold uppercase tracking-wider text-gray-600 group-hover:text-white">Transaksi</span>
                    </a>
                    <a href="{{ route('indikator-mutu.create') }}"
                        class="flex items-center gap-2.5 p-3 rounded-2xl bg-gray-50 border border-gray-100 hover:bg-indigo-600 hover:border-indigo-600 hover:text-white transition-all group">
                        <span class="material-symbols-outlined text-[18px] text-indigo-500 group-hover:text-white transition-colors" style="font-variation-settings: 'FILL' 1">verified</span>
                        <span class="text-[10px] font-bold uppercase tracking-wider text-gray-600 group-hover:text-white">Indikator</span>
                    </a>
                    <a href="{{ route('audit-monitoring.create') }}"
                        class="flex items-center gap-2.5 p-3 rounded-2xl bg-gray-50 border border-gray-100 hover:bg-violet-600 hover:border-violet-600 hover:text-white transition-all group">
                        <span class="material-symbols-outlined text-[18px] text-violet-500 group-hover:text-white transition-colors" style="font-variation-settings: 'FILL' 1">manage_search</span>
                        <span class="text-[10px] font-bold uppercase tracking-wider text-gray-600 group-hover:text-white">Audit</span>
                    </a>
                    <a href="{{ route('standar-tarif.create') }}"
                        class="flex items-center gap-2.5 p-3 rounded-2xl bg-gray-50 border border-gray-100 hover:bg-rose-500 hover:border-rose-500 hover:text-white transition-all group">
                        <span class="material-symbols-outlined text-[18px] text-rose-500 group-hover:text-white transition-colors" style="font-variation-settings: 'FILL' 1">upload_file</span>
                        <span class="text-[10px] font-bold uppercase tracking-wider text-gray-600 group-hover:text-white">Tarif</span>
                    </a>
                </div>
            </div>

        </div>
    </div>

</x-app-layout>
