<x-app-layout>
    <x-slot:title>Rekapitulasi</x-slot>

    {{-- ── Header ────────────────────────────────────────────────────────────── --}}
    <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4 mb-6">
        <div>
            <p class="text-xs text-gray-500 mt-1">
                Ringkasan menyeluruh keuangan dan capaian indikator mutu per bidang
            </p>
        </div>

        {{-- Filter & Action --}}
        <div class="flex flex-col sm:flex-row sm:items-center gap-3">
            {{-- Filter Tahun Anggaran --}}
            <div class="flex items-center gap-2" x-data="{
                onChange(e) {
                    const url = new URL(window.location.href);
                    url.searchParams.set('tahun', e.target.value);
                    window.location.href = url.toString();
                }
            }">
                <label class="text-xs text-gray-500 font-medium whitespace-nowrap">Tahun Anggaran:</label>
                <div class="relative">
                    <select @change="onChange($event)"
                        class="pl-3 pr-8 py-1.5 text-xs font-semibold rounded-lg border border-gray-200 bg-white text-gray-700 focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none appearance-none cursor-pointer">
                        @foreach ($tahunAnggaranList as $tahun)
                            <option value="{{ $tahun->id }}" {{ $activeTahun == $tahun->id ? 'selected' : '' }}>
                                TA {{ $tahun->tahun }}{{ $tahun->is_aktif ? ' (Aktif)' : '' }}
                            </option>
                        @endforeach
                    </select>
                    <span class="material-symbols-outlined absolute right-2 top-1/2 -translate-y-1/2 text-gray-400 text-[15px] pointer-events-none">
                        expand_more
                    </span>
                </div>
            </div>

            {{-- Tombol Cetak --}}
            <x-modal-cetak
                module="rekapitulasi"
                :tahun-anggaran-list="$tahunAnggaranList"
                :active-tahun="$activeTahun" />
        </div>
    </div>

    {{-- ── Section 1: Ringkasan Pendapatan ─────────────────────────────────── --}}
    <div class="mb-6">
        <div class="flex items-center gap-2 mb-3">
            <div class="w-1 h-5 bg-teal-500 rounded-full"></div>
            <h2 class="font-semibold text-gray-800 text-sm">Ringkasan Pendapatan</h2>
            <a href="{{ route('pendapatan.index') }}"
                class="ml-auto text-[11px] text-primary font-medium hover:underline flex items-center gap-0.5">
                Lihat detail
                <span class="material-symbols-outlined text-[13px]">arrow_forward</span>
            </a>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
            {{-- Total Rencana --}}
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-4">
                <div class="flex items-center gap-2 mb-2">
                    <div class="w-7 h-7 rounded-lg bg-gray-100 flex items-center justify-center">
                        <span class="material-symbols-outlined text-gray-500 text-[16px]">payments</span>
                    </div>
                    <p class="text-[10px] uppercase tracking-wider text-gray-400 font-semibold">Total Rencana</p>
                </div>
                <p class="text-lg font-bold text-gray-900">Rp {{ number_format($totalRencana, 0, ',', '.') }}</p>
                @php $pctPend = $totalRencana > 0 ? round(($totalRealisasiPend / $totalRencana) * 100, 1) : 0; @endphp
                <p class="text-[10px] text-gray-400 mt-1">Target pencapaian pendapatan</p>
            </div>

            {{-- Total Realisasi --}}
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-4">
                <div class="flex items-center gap-2 mb-2">
                    <div class="w-7 h-7 rounded-lg bg-green-50 flex items-center justify-center">
                        <span class="material-symbols-outlined text-green-600 text-[16px]">trending_up</span>
                    </div>
                    <p class="text-[10px] uppercase tracking-wider text-green-600 font-semibold">Realisasi</p>
                </div>
                <p class="text-lg font-bold text-green-700">Rp {{ number_format($totalRealisasiPend, 0, ',', '.') }}</p>
                <div class="mt-2">
                    <div class="flex justify-between text-[10px] text-gray-400 mb-1">
                        <span>Capaian</span>
                        <span class="font-semibold text-green-700">{{ $pctPend }}%</span>
                    </div>
                    <div class="w-full h-1.5 bg-gray-100 rounded-full overflow-hidden">
                        <div class="h-full bg-green-500 rounded-full" style="width: {{ min($pctPend, 100) }}%"></div>
                    </div>
                </div>
            </div>

            {{-- Sisa --}}
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-4">
                <div class="flex items-center gap-2 mb-2">
                    <div class="w-7 h-7 rounded-lg bg-teal-50 flex items-center justify-center">
                        <span class="material-symbols-outlined text-teal-600 text-[16px]">account_balance</span>
                    </div>
                    <p class="text-[10px] uppercase tracking-wider text-gray-400 font-semibold">Sisa Pendapatan</p>
                </div>
                <p class="text-lg font-bold {{ $sisaPendapatan >= 0 ? 'text-gray-900' : 'text-red-600' }}">
                    Rp {{ number_format(abs($sisaPendapatan), 0, ',', '.') }}
                    @if ($sisaPendapatan < 0) <span class="text-xs font-normal text-red-500">(minus)</span> @endif
                </p>
                <p class="text-[10px] text-gray-400 mt-1">Selisih rencana vs realisasi</p>
            </div>
        </div>
    </div>

    {{-- ── Section 2: Ringkasan Anggaran Per Bidang (Pengeluaran) ─────────── --}}
    <div class="bg-white rounded-2xl border border-gray-200 overflow-hidden shadow-sm mb-6">

        <div class="px-5 py-4 border-b border-gray-100 flex items-center gap-2">
            <div class="w-1 h-5 bg-primary rounded-full"></div>
            <span class="material-symbols-outlined text-gray-400 text-[18px]">pie_chart</span>
            <h2 class="font-semibold text-gray-900 text-sm">Ringkasan Anggaran Per Bidang</h2>
            <a href="{{ route('pengeluaran.index') }}"
                class="ml-auto text-[11px] text-primary font-medium hover:underline flex items-center gap-0.5">
                Lihat detail
                <span class="material-symbols-outlined text-[13px]">arrow_forward</span>
            </a>
        </div>

        {{-- Summary Bar --}}
        <div class="px-5 py-3 bg-gray-50/60 border-b border-gray-100 grid grid-cols-3 gap-4 text-xs">
            <div>
                <p class="text-[10px] uppercase tracking-wider text-gray-400 mb-0.5">Total Anggaran</p>
                <p class="font-bold text-gray-900">Rp {{ number_format($totalAnggaran, 0, ',', '.') }}</p>
            </div>
            <div>
                <p class="text-[10px] uppercase tracking-wider text-green-600 mb-0.5">Total Realisasi</p>
                <p class="font-bold text-green-700">Rp {{ number_format($totalRealisasiPeng, 0, ',', '.') }}</p>
            </div>
            <div>
                <p class="text-[10px] uppercase tracking-wider text-gray-400 mb-0.5">Sisa Anggaran</p>
                <p class="font-bold text-gray-900">Rp {{ number_format($sisaAnggaran, 0, ',', '.') }}</p>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-xs text-left">
                <thead class="bg-gray-50 text-[10px] uppercase tracking-widest text-gray-500 font-semibold border-b border-gray-100">
                    <tr>
                        <th class="px-5 py-2.5">Bidang</th>
                        <th class="px-5 py-2.5 text-right">Anggaran</th>
                        <th class="px-5 py-2.5 text-right">Realisasi</th>
                        <th class="px-5 py-2.5 text-right">Sisa</th>
                        <th class="px-5 py-2.5 w-40">Serapan</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-gray-100 text-gray-700">

                    @forelse ($ringkasanPengeluaran as $bidang)
                        <tr class="hover:bg-gray-50/50 transition-colors">

                            <td class="px-5 py-3 align-top">
                                <a href="{{ route('pengeluaran.index', ['tahun' => $activeTahun, 'bidang' => $bidang->id]) }}"
                                    class="inline-flex items-center gap-1.5 bg-gray-50 text-gray-700 px-2.5 py-1.5 rounded-md font-medium border border-gray-100 text-[11px] leading-tight hover:bg-primary/5 hover:text-primary hover:border-primary/20 transition-colors">
                                    <span class="material-symbols-outlined text-[14px] text-gray-400">
                                        {{ $bidang->icon ?? 'domain' }}
                                    </span>
                                    {{ $bidang->nama }}
                                </a>
                            </td>

                            <td class="px-5 py-3 font-semibold text-gray-900 text-right align-top whitespace-nowrap">
                                Rp {{ number_format($bidang->total_anggaran, 0, ',', '.') }}
                            </td>

                            <td class="px-5 py-3 font-medium text-right align-top whitespace-nowrap {{ $bidang->total_realisasi > 0 ? 'text-green-600' : 'text-gray-500' }}">
                                Rp {{ number_format($bidang->total_realisasi, 0, ',', '.') }}
                            </td>

                            <td class="px-5 py-3 font-medium text-gray-800 text-right align-top whitespace-nowrap">
                                Rp {{ number_format($bidang->sisa_anggaran, 0, ',', '.') }}
                            </td>

                            <td class="px-5 py-3 align-top">
                                <div class="flex items-center gap-2 mt-0.5">
                                    <div class="w-full h-1.5 bg-gray-100 rounded-full overflow-hidden">
                                        <div class="h-full {{ $bidang->persen >= 90 ? 'bg-red-500' : ($bidang->persen >= 60 ? 'bg-amber-500' : 'bg-primary') }}"
                                            style="width: {{ min($bidang->persen, 100) }}%"></div>
                                    </div>
                                    <span class="text-[10px] font-medium w-8 text-right
                                        {{ $bidang->persen >= 90 ? 'text-red-600' : ($bidang->persen >= 60 ? 'text-amber-600' : 'text-gray-500') }}">
                                        {{ $bidang->persen }}%
                                    </span>
                                </div>
                            </td>

                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-5 py-10 text-center text-gray-400 text-xs">
                                Belum ada data bidang kerja yang tersedia.
                            </td>
                        </tr>
                    @endforelse

                </tbody>
            </table>
        </div>

    </div>

    {{-- ── Section 3: Ringkasan Capaian Indikator Mutu Per Bidang ──────────── --}}
    <div class="bg-white rounded-2xl border border-gray-200 overflow-hidden shadow-sm mb-5">

        <div class="px-5 py-4 border-b border-gray-100 flex items-center gap-2">
            <div class="w-1 h-5 bg-indigo-500 rounded-full"></div>
            <span class="material-symbols-outlined text-gray-400 text-[18px]">verified</span>
            <h2 class="font-semibold text-gray-900 text-sm">Ringkasan Capaian Indikator Mutu Per Bidang</h2>
            <a href="{{ route('indikator-mutu.index') }}"
                class="ml-auto text-[11px] text-primary font-medium hover:underline flex items-center gap-0.5">
                Lihat detail
                <span class="material-symbols-outlined text-[13px]">arrow_forward</span>
            </a>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-xs text-left">
                <thead class="bg-gray-50 text-[10px] uppercase tracking-widest text-gray-500 font-semibold border-b border-gray-100">
                    <tr>
                        <th class="px-5 py-2.5">Bidang</th>
                        <th class="px-5 py-2.5 text-center">Total</th>
                        <th class="px-5 py-2.5 text-center">Tercapai</th>
                        <th class="px-5 py-2.5 text-center">Proses</th>
                        <th class="px-5 py-2.5 text-center">Belum</th>
                        <th class="px-5 py-2.5 w-40">Capaian</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-gray-100 text-gray-700">

                    @forelse ($ringkasanIndikator as $bidang)
                        <tr class="hover:bg-gray-50/50 transition-colors">

                            <td class="px-5 py-3 align-top">
                                <a href="{{ route('indikator-mutu.index', ['bidang' => $bidang->id]) }}"
                                    class="inline-flex items-center gap-1.5 bg-gray-50 text-gray-700 px-2.5 py-1.5 rounded-md font-medium border border-gray-100 text-[11px] leading-tight hover:bg-indigo-50 hover:text-indigo-700 hover:border-indigo-200 transition-colors">
                                    <span class="material-symbols-outlined text-[14px] text-gray-400">
                                        {{ $bidang->icon ?? 'domain' }}
                                    </span>
                                    {{ $bidang->nama }}
                                </a>
                            </td>

                            <td class="px-5 py-3 text-center font-semibold text-gray-900">
                                {{ $bidang->total_indikator }}
                            </td>

                            <td class="px-5 py-3 text-center">
                                <span class="inline-flex items-center justify-center w-8 h-5 rounded text-[10px] font-bold
                                    {{ $bidang->total_tercapai > 0 ? 'bg-emerald-100 text-emerald-700' : 'text-gray-400' }}">
                                    {{ $bidang->total_tercapai }}
                                </span>
                            </td>

                            <td class="px-5 py-3 text-center">
                                <span class="inline-flex items-center justify-center w-8 h-5 rounded text-[10px] font-bold
                                    {{ $bidang->total_proses > 0 ? 'bg-amber-100 text-amber-700' : 'text-gray-400' }}">
                                    {{ $bidang->total_proses }}
                                </span>
                            </td>

                            <td class="px-5 py-3 text-center">
                                <span class="inline-flex items-center justify-center w-8 h-5 rounded text-[10px] font-bold
                                    {{ $bidang->total_belum > 0 ? 'bg-gray-100 text-gray-600' : 'text-gray-400' }}">
                                    {{ $bidang->total_belum }}
                                </span>
                            </td>

                            <td class="px-5 py-3 align-middle">
                                <div class="flex items-center gap-2">
                                    <div class="flex-1 h-1.5 bg-gray-100 rounded-full overflow-hidden">
                                        <div class="h-full bg-emerald-500 transition-all"
                                            style="width: {{ min($bidang->persen_tercapai, 100) }}%"></div>
                                    </div>
                                    <span class="text-[10px] text-gray-500 font-medium w-8 text-right">{{ $bidang->persen_tercapai }}%</span>
                                </div>
                            </td>

                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-5 py-10 text-center text-gray-400 text-xs">
                                Belum ada data bidang kerja yang tersedia.
                            </td>
                        </tr>
                    @endforelse

                </tbody>
            </table>
        </div>

    </div>

</x-app-layout>
