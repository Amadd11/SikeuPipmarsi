<x-app-layout>
    <x-slot:title>Dashboard</x-slot>

    {{-- ── Greeting & Tahun ──────────────────────────────────────────────── --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 mb-6">
        <div>
            <p class="text-xs text-gray-400 mt-0.5">
                @if ($tahunAktif)
                    Tahun Anggaran Aktif:
                    <span class="font-semibold text-gray-700">{{ $tahunAktif->label ?? $tahunAktif->tahun }}</span>
                @else
                    <span class="text-amber-500 font-medium">Belum ada tahun anggaran aktif.</span>
                @endif
            </p>
        </div>
        <span class="inline-flex items-center gap-1.5 text-[11px] text-gray-400 bg-white border border-gray-200 px-3 py-1.5 rounded-full shadow-sm">
            <span class="material-symbols-outlined text-[14px] text-primary">calendar_today</span>
            {{ now()->translatedFormat('d F Y') }}
        </span>
    </div>

    {{-- ── Row 1: Stat Cards Keuangan ──────────────────────────────────────── --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-5">

        <x-stat-card
            label="Rencana Pendapatan"
            :value="'Rp ' . number_format($totalPendapatanRencana, 0, ',', '.')"
            :sub="'Realisasi ' . $pctPendapatan . '% dari target'"
            icon="payments"
            variant="teal" />

        <x-stat-card
            label="Realisasi Pendapatan"
            :value="'Rp ' . number_format($realisasiPendapatan, 0, ',', '.')"
            :sub="number_format($realisasiPendapatan / 1000000, 1) . ' juta terkumpul'"
            icon="trending_up"
            variant="green" />

        <x-stat-card
            label="Anggaran Belanja"
            :value="'Rp ' . number_format($totalAnggaranBelanja, 0, ',', '.')"
            :sub="'Serapan ' . $serapan . '% dari anggaran'"
            icon="account_balance_wallet"
            variant="navy" />

        <x-stat-card
            label="Realisasi Pengeluaran"
            :value="'Rp ' . number_format($realisasiPengeluaran, 0, ',', '.')"
            :sub="number_format($realisasiPengeluaran / 1000000, 1) . ' juta terpakai'"
            icon="receipt_long"
            variant="red" />
    </div>

    {{-- ── Row 2: Saldo + Mutu + Audit/Tarif ──────────────────────────────── --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-5">

        {{-- Saldo & Serapan --}}
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
            <div class="flex items-center gap-2 mb-4">
                <div class="w-8 h-8 rounded-xl bg-teal-50 flex items-center justify-center">
                    <span class="material-symbols-outlined text-teal-600 text-[18px]">account_balance</span>
                </div>
                <h3 class="font-semibold text-gray-800 text-sm">Saldo & Serapan</h3>
            </div>

            <div class="space-y-4">
                {{-- Saldo --}}
                <div>
                    <p class="text-[10px] text-gray-400 uppercase tracking-wider mb-1">Saldo Kas</p>
                    <p class="text-xl font-bold {{ $saldoKas >= 0 ? 'text-teal-700' : 'text-red-600' }}">
                        Rp {{ number_format(abs($saldoKas), 0, ',', '.') }}
                        @if ($saldoKas < 0) <span class="text-xs font-medium">(defisit)</span> @endif
                    </p>
                </div>

                {{-- Serapan Progress --}}
                <div>
                    <div class="flex justify-between text-xs mb-1.5">
                        <span class="text-gray-500">Serapan Anggaran</span>
                        <span class="font-semibold {{ $serapan >= 90 ? 'text-red-600' : 'text-teal-700' }}">
                            {{ $serapan }}%
                        </span>
                    </div>
                    <div class="w-full h-2 bg-gray-100 rounded-full overflow-hidden">
                        <div class="h-full rounded-full transition-all duration-700
                            {{ $serapan >= 90 ? 'bg-red-500' : ($serapan >= 70 ? 'bg-amber-500' : 'bg-teal-600') }}"
                            style="width: {{ min($serapan, 100) }}%"></div>
                    </div>
                </div>

                {{-- Pendapatan Progress --}}
                <div>
                    <div class="flex justify-between text-xs mb-1.5">
                        <span class="text-gray-500">Realisasi Pendapatan</span>
                        <span class="font-semibold text-emerald-700">{{ $pctPendapatan }}%</span>
                    </div>
                    <div class="w-full h-2 bg-gray-100 rounded-full overflow-hidden">
                        <div class="h-full bg-emerald-500 rounded-full transition-all duration-700"
                            style="width: {{ min($pctPendapatan, 100) }}%"></div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Capaian Indikator Mutu --}}
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
            <div class="flex items-center gap-2 mb-4">
                <div class="w-8 h-8 rounded-xl bg-indigo-50 flex items-center justify-center">
                    <span class="material-symbols-outlined text-indigo-600 text-[18px]">verified</span>
                </div>
                <h3 class="font-semibold text-gray-800 text-sm">Indikator Mutu</h3>
            </div>

            {{-- Donut Visual --}}
            <div class="flex items-center gap-4 mb-4">
                <div class="relative w-20 h-20 shrink-0">
                    <svg class="w-20 h-20 -rotate-90" viewBox="0 0 36 36">
                        <circle cx="18" cy="18" r="15.9" fill="none" stroke="#f3f4f6" stroke-width="3.5"/>
                        @if ($totalIndikator > 0)
                        <circle cx="18" cy="18" r="15.9" fill="none"
                            stroke="{{ $persenTercapai >= 70 ? '#10b981' : ($persenTercapai >= 40 ? '#f59e0b' : '#6366f1') }}"
                            stroke-width="3.5"
                            stroke-dasharray="{{ $persenTercapai }}, 100"
                            stroke-linecap="round"/>
                        @endif
                    </svg>
                    <div class="absolute inset-0 flex items-center justify-center">
                        <span class="text-sm font-bold text-gray-800">{{ $persenTercapai }}%</span>
                    </div>
                </div>
                <div class="flex-1 space-y-1.5 text-xs">
                    <div class="flex items-center justify-between">
                        <span class="flex items-center gap-1.5 text-gray-500"><span class="w-2 h-2 rounded-full bg-emerald-500 inline-block"></span>Tercapai</span>
                        <span class="font-bold text-gray-800">{{ $tercapai }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="flex items-center gap-1.5 text-gray-500"><span class="w-2 h-2 rounded-full bg-amber-400 inline-block"></span>Proses</span>
                        <span class="font-bold text-gray-800">{{ $proses }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="flex items-center gap-1.5 text-gray-500"><span class="w-2 h-2 rounded-full bg-red-400 inline-block"></span>Tdk Tercapai</span>
                        <span class="font-bold text-gray-800">{{ $tidakTercapai }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="flex items-center gap-1.5 text-gray-500"><span class="w-2 h-2 rounded-full bg-gray-300 inline-block"></span>Belum</span>
                        <span class="font-bold text-gray-800">{{ $belum }}</span>
                    </div>
                </div>
            </div>

            <div class="flex justify-between items-center text-xs pt-3 border-t border-gray-100">
                <span class="text-gray-400">Total indikator</span>
                <a href="{{ route('indikator-mutu.index') }}"
                    class="inline-flex items-center gap-1 text-primary font-semibold hover:underline">
                    {{ $totalIndikator }} indikator
                    <span class="material-symbols-outlined text-[13px]">arrow_forward</span>
                </a>
            </div>
        </div>

        {{-- Audit & Standar Tarif --}}
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5 flex flex-col gap-4">

            {{-- Audit Monitoring --}}
            <div>
                <div class="flex items-center gap-2 mb-3">
                    <div class="w-8 h-8 rounded-xl bg-violet-50 flex items-center justify-center">
                        <span class="material-symbols-outlined text-violet-600 text-[18px]">manage_search</span>
                    </div>
                    <h3 class="font-semibold text-gray-800 text-sm">Audit Monitoring</h3>
                </div>
                <div class="grid grid-cols-2 gap-3 text-center">
                    <div class="bg-gray-50 rounded-xl p-3">
                        <p class="text-2xl font-bold text-gray-900">{{ $totalAudit }}</p>
                        <p class="text-[10px] text-gray-400 mt-0.5">Total Audit</p>
                    </div>
                    <div class="bg-emerald-50 rounded-xl p-3">
                        <p class="text-2xl font-bold text-emerald-700">{{ $auditSelesai }}</p>
                        <p class="text-[10px] text-emerald-600 mt-0.5">Selesai</p>
                    </div>
                </div>
                @if ($totalAudit > 0)
                <div class="mt-2.5">
                    @php $pctAudit = round(($auditSelesai / $totalAudit) * 100); @endphp
                    <div class="flex justify-between text-[10px] text-gray-400 mb-1">
                        <span>Progress penyelesaian</span>
                        <span class="font-semibold text-violet-600">{{ $pctAudit }}%</span>
                    </div>
                    <div class="w-full h-1.5 bg-gray-100 rounded-full overflow-hidden">
                        <div class="h-full bg-violet-500 rounded-full" style="width: {{ $pctAudit }}%"></div>
                    </div>
                </div>
                @endif
            </div>

            <div class="border-t border-gray-100"></div>

            {{-- Standar Tarif --}}
            <div>
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <div class="w-8 h-8 rounded-xl bg-red-50 flex items-center justify-center">
                            <span class="material-symbols-outlined text-red-500 text-[18px]">description</span>
                        </div>
                        <div>
                            <p class="text-sm font-semibold text-gray-800">Standar Tarif</p>
                            <p class="text-[10px] text-gray-400">Dokumen PDF tersedia</p>
                        </div>
                    </div>
                    <div class="text-right">
                        <p class="text-2xl font-bold text-gray-900">{{ $totalStandarTarif }}</p>
                        <a href="{{ route('standar-tarif.index') }}"
                            class="text-[10px] text-primary font-medium hover:underline">Lihat semua</a>
                    </div>
                </div>
            </div>

        </div>
    </div>

    {{-- ── Row 3: Serapan Bidang + Transaksi Terbaru + Peringatan ─────────── --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">

        {{-- Serapan Per Bidang --}}
        <div class="lg:col-span-1 bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
            <div class="flex items-center gap-2 mb-4">
                <div class="w-8 h-8 rounded-xl bg-amber-50 flex items-center justify-center">
                    <span class="material-symbols-outlined text-amber-600 text-[18px]">bar_chart</span>
                </div>
                <h3 class="font-semibold text-gray-800 text-sm">Serapan Per Bidang</h3>
            </div>

            <div class="space-y-4">
                @forelse ($serapanBidang as $bidang)
                    <div>
                        <div class="flex items-center justify-between text-xs mb-1.5">
                            <span class="flex items-center gap-1.5 text-gray-700 font-medium">
                                <span class="material-symbols-outlined text-[14px] text-gray-400">
                                    {{ $bidang->icon ?? 'domain' }}
                                </span>
                                <span class="leading-tight">{{ Str::limit($bidang->nama, 28) }}</span>
                            </span>
                            <span class="font-bold text-xs shrink-0 ml-2
                                {{ $bidang->pct_serapan >= 90 ? 'text-red-600' : ($bidang->pct_serapan >= 60 ? 'text-amber-600' : 'text-teal-700') }}">
                                {{ $bidang->pct_serapan }}%
                            </span>
                        </div>
                        <div class="w-full h-1.5 bg-gray-100 rounded-full overflow-hidden">
                            <div class="h-full rounded-full transition-all duration-700
                                {{ $bidang->pct_serapan >= 90 ? 'bg-red-500' : ($bidang->pct_serapan >= 60 ? 'bg-amber-500' : 'bg-teal-600') }}"
                                style="width: {{ min($bidang->pct_serapan, 100) }}%"></div>
                        </div>
                    </div>
                @empty
                    <p class="text-xs text-gray-400 text-center py-6">Belum ada data bidang kerja.</p>
                @endforelse
            </div>

            <div class="mt-4 pt-3 border-t border-gray-100">
                <a href="{{ route('pengeluaran.index') }}"
                    class="text-xs text-primary font-medium hover:underline flex items-center gap-1">
                    <span class="material-symbols-outlined text-[13px]">arrow_forward</span>
                    Lihat detail pengeluaran
                </a>
            </div>
        </div>

        {{-- Transaksi Terbaru --}}
        <div class="lg:col-span-1 bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
            <div class="flex items-center justify-between mb-4">
                <div class="flex items-center gap-2">
                    <div class="w-8 h-8 rounded-xl bg-blue-50 flex items-center justify-center">
                        <span class="material-symbols-outlined text-blue-600 text-[18px]">sync_alt</span>
                    </div>
                    <h3 class="font-semibold text-gray-800 text-sm">Transaksi Terbaru</h3>
                </div>
                <a href="{{ route('transaksi.index') }}"
                    class="text-[10px] text-primary font-medium hover:underline flex items-center gap-0.5">
                    Semua
                    <span class="material-symbols-outlined text-[12px]">arrow_forward</span>
                </a>
            </div>

            <div class="space-y-2.5">
                @forelse ($transaksiTerbaru as $tx)
                    <div class="flex items-start gap-3 py-2 border-b border-gray-50 last:border-0">
                        <div class="w-7 h-7 rounded-lg shrink-0 flex items-center justify-center
                            {{ $tx->jenis === 'pemasukan' ? 'bg-emerald-50' : 'bg-red-50' }}">
                            <span class="material-symbols-outlined text-[14px]
                                {{ $tx->jenis === 'pemasukan' ? 'text-emerald-600' : 'text-red-500' }}">
                                {{ $tx->jenis === 'pemasukan' ? 'arrow_downward' : 'arrow_upward' }}
                            </span>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-xs font-medium text-gray-800 truncate leading-tight">{{ $tx->uraian ?? '-' }}</p>
                            <p class="text-[10px] text-gray-400 mt-0.5">
                                {{ $tx->tanggal?->format('d M Y') }}
                                @if ($tx->bidangKerja)
                                    · {{ $tx->bidangKerja->kode ?? $tx->bidangKerja->nama }}
                                @endif
                            </p>
                        </div>
                        <div class="text-right shrink-0">
                            <p class="text-xs font-bold {{ $tx->jenis === 'pemasukan' ? 'text-emerald-700' : 'text-red-600' }}">
                                {{ $tx->jenis === 'pemasukan' ? '+' : '-' }}
                                Rp {{ number_format($tx->jumlah / 1000) }}rb
                            </p>
                        </div>
                    </div>
                @empty
                    <div class="flex flex-col items-center justify-center py-8 text-center">
                        <span class="material-symbols-outlined text-3xl text-gray-200 mb-2">receipt_long</span>
                        <p class="text-xs text-gray-400">Belum ada transaksi.</p>
                    </div>
                @endforelse
            </div>
        </div>

        {{-- Peringatan & Quick Links --}}
        <div class="lg:col-span-1 flex flex-col gap-4">

            {{-- Peringatan --}}
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5 flex-1">
                <div class="flex items-center gap-2 mb-4">
                    <div class="w-8 h-8 rounded-xl bg-amber-50 flex items-center justify-center">
                        <span class="material-symbols-outlined text-amber-600 text-[18px]">notifications_active</span>
                    </div>
                    <h3 class="font-semibold text-gray-800 text-sm">Notifikasi & Peringatan</h3>
                </div>

                <div class="space-y-2.5">
                    @forelse ($peringatan as $p)
                        @php
                            $colors = [
                                'red'   => ['bg' => 'bg-red-50',   'border' => 'border-red-100',   'icon' => 'text-red-500'],
                                'amber' => ['bg' => 'bg-amber-50', 'border' => 'border-amber-100', 'icon' => 'text-amber-600'],
                                'blue'  => ['bg' => 'bg-blue-50',  'border' => 'border-blue-100',  'icon' => 'text-blue-600'],
                            ];
                            $c = $colors[$p['level']] ?? $colors['blue'];
                        @endphp
                        <a href="{{ $p['link'] }}"
                            class="flex items-start gap-2.5 px-3 py-2.5 rounded-xl border {{ $c['bg'] }} {{ $c['border'] }} hover:brightness-95 transition">
                            <span class="material-symbols-outlined text-[16px] {{ $c['icon'] }} shrink-0 mt-0.5">{{ $p['icon'] }}</span>
                            <span class="text-xs text-gray-700 leading-snug">{{ $p['message'] }}</span>
                        </a>
                    @empty
                        <div class="flex items-center gap-2 px-3 py-2.5 rounded-xl bg-emerald-50 border border-emerald-100">
                            <span class="material-symbols-outlined text-[16px] text-emerald-600">check_circle</span>
                            <span class="text-xs text-emerald-700 font-medium">Semua kondisi normal. Tidak ada peringatan.</span>
                        </div>
                    @endforelse
                </div>
            </div>

            {{-- Quick Access --}}
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
                <p class="text-[10px] text-gray-400 uppercase tracking-wider font-semibold mb-3">Akses Cepat</p>
                <div class="grid grid-cols-2 gap-2">
                    <a href="{{ route('transaksi.create') }}"
                        class="flex flex-col items-center gap-1.5 p-3 rounded-xl border border-gray-100 hover:border-primary/30 hover:bg-primary/5 transition group text-center">
                        <span class="material-symbols-outlined text-[20px] text-gray-400 group-hover:text-primary transition">add_circle</span>
                        <span class="text-[10px] text-gray-600 font-medium leading-tight">Tambah Transaksi</span>
                    </a>
                    <a href="{{ route('indikator-mutu.create') }}"
                        class="flex flex-col items-center gap-1.5 p-3 rounded-xl border border-gray-100 hover:border-indigo-300 hover:bg-indigo-50 transition group text-center">
                        <span class="material-symbols-outlined text-[20px] text-gray-400 group-hover:text-indigo-600 transition">verified</span>
                        <span class="text-[10px] text-gray-600 font-medium leading-tight">Tambah Indikator</span>
                    </a>
                    <a href="{{ route('audit-monitoring.create') }}"
                        class="flex flex-col items-center gap-1.5 p-3 rounded-xl border border-gray-100 hover:border-violet-300 hover:bg-violet-50 transition group text-center">
                        <span class="material-symbols-outlined text-[20px] text-gray-400 group-hover:text-violet-600 transition">manage_search</span>
                        <span class="text-[10px] text-gray-600 font-medium leading-tight">Tambah Audit</span>
                    </a>
                    <a href="{{ route('standar-tarif.create') }}"
                        class="flex flex-col items-center gap-1.5 p-3 rounded-xl border border-gray-100 hover:border-red-200 hover:bg-red-50 transition group text-center">
                        <span class="material-symbols-outlined text-[20px] text-gray-400 group-hover:text-red-500 transition">upload_file</span>
                        <span class="text-[10px] text-gray-600 font-medium leading-tight">Upload Tarif</span>
                    </a>
                </div>
            </div>

        </div>
    </div>

</x-app-layout>
