<x-app-layout>
    <x-slot:title>Rencana Pengeluaran</x-slot>

    {{-- Flash Message --}}
    @if (session('success'))
        <div
            class="mb-4 px-4 py-3 rounded-xl bg-green-50 border border-green-100 text-green-700 text-xs flex items-center gap-2">
            <span class="material-symbols-outlined text-[16px]">check_circle</span>
            {{ session('success') }}
        </div>
    @endif

    {{-- Filter Tahun & Page Header (Digabung sejajar di layar besar) --}}
    <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4 mb-5">

        <div>
            <p class="text-xs text-gray-500 mt-1">
                Alokasi anggaran per bidang kerja sesuai aktivitas pencapaian indikator mutu
            </p>
        </div>

        <div class="flex flex-col sm:flex-row sm:items-center gap-3">
            {{-- Filter Tahun Anggaran --}}
            <div class="flex items-center gap-2" x-data="{
                onChange(e) {
                    const url = new URL(window.location.href);
                    url.searchParams.set('tahun', e.target.value);
                    url.searchParams.delete('page');
                    window.location.href = url.toString();
                }
            }">
                <label class="text-xs text-gray-500 font-medium whitespace-nowrap">Tahun:</label>
                <div class="relative">
                    <select @change="onChange($event)"
                        class="pl-3 pr-8 py-1.5 text-xs font-semibold rounded-lg border border-gray-200 bg-white text-gray-700 focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none appearance-none cursor-pointer">
                        @foreach ($tahunAnggaranList as $tahun)
                            <option value="{{ $tahun->id }}" {{ $activeTahun == $tahun->id ? 'selected' : '' }}>
                                TA {{ $tahun->tahun }}{{ $tahun->is_aktif ? ' (Aktif)' : '' }}
                            </option>
                        @endforeach
                    </select>
                    <span
                        class="material-symbols-outlined absolute right-2 top-1/2 -translate-y-1/2 text-gray-400 text-[15px] pointer-events-none">
                        expand_more
                    </span>
                </div>
            </div>

            {{-- Tombol Cetak & Tambah --}}
            <x-modal-cetak
                module="pengeluaran"
                :show-bidang="true"
                :tahun-anggaran-list="$tahunAnggaranList"
                :bidang-kerja-list="$semuaBidang"
                :active-tahun="$activeTahun"
                :active-bidang="$activeBidang" />

            <a href="{{ route('pengeluaran.create') }}"
                class="inline-flex items-center gap-2 bg-gold text-gray-900 px-4 py-2 rounded-full text-xs font-semibold shadow hover:bg-gold-dark hover:shadow-md transition-all duration-200 active:scale-95">
                <span class="material-symbols-outlined text-[16px]">add</span>
                Tambah Pengeluaran
            </a>
        </div>

    </div>

    {{-- Tabs Navigation --}}
    <div class="flex flex-wrap gap-1.5 mb-5">
        {{-- Tab: Semua --}}
        <a href="{{ route('pengeluaran.index', ['tahun' => $activeTahun]) }}"
            class="inline-flex items-center gap-1.5 px-3 py-2 rounded-lg text-xs font-{{ $activeBidang === null ? 'semibold' : 'medium' }}
                {{ $activeBidang === null ? 'bg-gold text-gray-900 shadow-sm' : 'bg-white text-gray-600 border border-gray-200 hover:bg-gray-50 hover:text-gray-900' }}
                transition-colors">
            <span class="material-symbols-outlined text-[16px] {{ $activeBidang === null ? '' : 'text-gray-400' }}">
                grid_view
            </span>
            Semua
        </a>

        {{-- Tab per Bidang --}}
        @foreach ($semuaBidang as $bidang)
            <a href="{{ route('pengeluaran.index', ['tahun' => $activeTahun, 'bidang' => $bidang->id]) }}"
                class="inline-flex items-center gap-1.5 px-3 py-2 rounded-lg text-xs font-{{ $activeBidang === $bidang->id ? 'semibold' : 'medium' }}
                    {{ $activeBidang === $bidang->id ? 'bg-gold text-gray-900 shadow-sm' : 'bg-white text-gray-600 border border-gray-200 hover:bg-gray-50 hover:text-gray-900' }}
                    transition-colors">
                <span
                    class="material-symbols-outlined text-[16px] {{ $activeBidang === $bidang->id ? '' : 'text-gray-400' }}">
                    {{ $bidang->icon ?? 'domain' }}
                </span>
                {{ $bidang->nama }}
            </a>
        @endforeach
    </div>

    {{-- Main Card: Detail Pengeluaran Bidang Aktif --}}
    <div class="bg-white rounded-2xl border border-gray-200 overflow-hidden shadow-sm mb-5">

        {{-- Summary Header (Disesuaikan persis seperti Rencana Pendapatan) --}}
        <div class="p-5 border-b border-gray-100">
            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">

                <div>
                    <h2 class="font-semibold text-gray-900 text-sm">
                        Daftar Rencana Pengeluaran
                    </h2>
                    <p class="text-xs text-gray-500 mt-0.5">
                        Menampilkan {{ $pengeluaran->total() }} pos anggaran kegiatan.
                    </p>
                </div>

                <div class="flex flex-wrap gap-2.5">
                    <div class="bg-gray-50 rounded-xl px-3.5 py-2.5 min-w-40 border border-gray-100/50">
                        <p class="text-[10px] uppercase tracking-wider text-gray-500">Total Anggaran</p>
                        <p class="font-bold text-gray-900 mt-0.5 text-sm">
                            Rp {{ number_format($totalAnggaran, 0, ',', '.') }}
                        </p>
                    </div>

                    <div class="bg-green-50 rounded-xl px-3.5 py-2.5 min-w-40 border border-green-100/50">
                        <p class="text-[10px] uppercase tracking-wider text-green-600">Total Realisasi</p>
                        <p class="font-bold text-green-700 mt-0.5 text-sm">
                            Rp {{ number_format($totalRealisasi, 0, ',', '.') }}
                        </p>
                    </div>

                    <div class="bg-emerald-50 rounded-xl px-3.5 py-2.5 min-w-40 border border-emerald-100/50">
                        <p class="text-[10px] uppercase tracking-wider text-black">Sisa Anggaran</p>
                        <p class="font-bold text-black mt-0.5 text-sm">
                            Rp {{ number_format($sisaAnggaran, 0, ',', '.') }}
                        </p>
                    </div>
                </div>

            </div>
        </div>

        {{-- Table Area --}}
        <div class="overflow-x-auto">
            <table class="w-full text-xs">
                <thead>
                    <tr
                        class="bg-gray-50 text-[10px] uppercase tracking-widest text-gray-500 font-semibold border-b border-gray-100">
                        <th class="px-4 py-2.5 text-center w-10">#</th>
                        <th class="px-4 py-2.5 text-left">Kegiatan / Pos</th>
                        <th class="px-4 py-2.5 text-left">Kategori</th>
                        <th class="px-4 py-2.5 text-left">Indikator Mutu</th>
                        <th class="px-4 py-2.5 text-right">Total Anggaran</th>
                        <th class="px-4 py-2.5 text-right">Realisasi</th>
                        <th class="px-4 py-2.5 text-right">Sisa</th>
                        <th class="px-4 py-2.5 text-center w-32">Progress</th>
                        <th class="px-4 py-2.5 text-center">Status</th>
                        <th class="px-4 py-2.5 text-center">Aksi</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-gray-100 text-gray-700">

                    @forelse ($pengeluaran as $item)

                        <tr class="hover:bg-gray-50/70 transition">

                            {{-- Nomor --}}
                            <td class="px-4 py-2.5 text-center text-gray-400 font-medium align-top">
                                {{ $loop->iteration + ($pengeluaran->currentPage() - 1) * $pengeluaran->perPage() }}
                            </td>

                            {{-- Kegiatan --}}
                            <td class="px-4 py-2.5 align-top">
                                <div class="flex flex-col">
                                    <span class="font-semibold text-gray-900 leading-tight">
                                        {{ $item->nama_kegiatan }}
                                    </span>
                                </div>
                            </td>

                            {{-- Kategori --}}
                            <td class="px-4 py-2.5 align-top">
                                <span
                                    class="inline-flex items-center px-2 py-0.5 rounded-md bg-emerald-50 text-emerald-700 text-[10px] font-medium border border-emerald-100/50 leading-tight">
                                    {{ $item->kategoriPengeluaran->nama ?? '-' }}
                                </span>
                            </td>

                            {{-- Indikator Mutu --}}
                            <td class="px-4 py-2.5 align-top leading-normal">
                                @if ($item->indikatorMutu)
                                    <span class="font-bold text-gray-900 text-[11px] block">
                                        {{ $item->indikatorMutu->kode }}
                                    </span>
                                    <span class="text-[10px] text-gray-500 block mt-0.5">
                                        {{ Str::limit($item->indikatorMutu->nama, 50) }}
                                    </span>
                                @else
                                    <span class="text-gray-400">-</span>
                                @endif
                            </td>

                            {{-- Anggaran --}}
                            <td class="px-4 py-2.5 text-right font-medium text-gray-900 align-top whitespace-nowrap">
                                Rp {{ number_format($item->jumlah_anggaran, 0, ',', '.') }}
                            </td>

                            {{-- Realisasi --}}
                            <td
                                class="px-4 py-2.5 text-right font-medium align-top whitespace-nowrap {{ $item->jumlah_realisasi > 0 ? 'text-green-600' : 'text-gray-600' }}">
                                Rp {{ number_format($item->jumlah_realisasi, 0, ',', '.') }}
                            </td>

                            {{-- Sisa --}}
                            <td class="px-4 py-2.5 text-right font-medium text-gray-800 align-top whitespace-nowrap">
                                Rp {{ number_format($item->sisa_anggaran, 0, ',', '.') }}
                            </td>

                            {{-- Progress --}}
                            <td class="px-4 py-2.5 align-top w-28">
                                <div class="flex items-center gap-2 mt-0.5">
                                    <div class="flex-1 h-1.5 bg-gray-100 rounded-full overflow-hidden">
                                        <div class="h-full bg-primary" style="width: {{ min($item->persentase_realisasi, 100) }}%"></div>
                                    </div>
                                    <span
                                        class="text-[10px] text-gray-500 font-medium w-6 text-right">{{ round($item->persentase_realisasi) }}%</span>
                                </div>
                            </td>

                            {{-- Status --}}
                            <td class="px-4 py-2.5 text-center align-top">
                                @if ($item->status_realisasi === 'selesai')
                                    <span
                                        class="inline-flex px-2 py-0.5 mt-0.5 rounded-md bg-green-100 text-green-700 text-[10px] font-semibold tracking-wide">
                                        Selesai
                                    </span>
                                @elseif ($item->status_realisasi === 'berjalan')
                                    <span
                                        class="inline-flex px-2 py-0.5 mt-0.5 rounded-md bg-amber-100 text-amber-700 text-[10px] font-semibold tracking-wide">
                                        Berjalan
                                    </span>
                                @else
                                    <span
                                        class="inline-flex px-2 py-0.5 mt-0.5 rounded-md bg-gray-100 text-gray-600 text-[10px] font-semibold tracking-wide">
                                        Belum
                                    </span>
                                @endif
                            </td>

                            {{-- Aksi --}}
                            <td class="px-4 py-2.5">
                                <div class="flex items-center justify-center gap-1.5">
                                    <a href="{{ route('pengeluaran.edit', $item->id) }}"
                                        class="w-8 h-8 rounded-full bg-gray-50 border border-gray-200 flex items-center justify-center text-gray-500 hover:bg-indigo-50 hover:border-indigo-300 hover:text-indigo-600 transition-all duration-200">
                                        <span class="material-symbols-outlined text-[16px]">edit</span>
                                    </a>

                                    <button
                                        @click="$dispatch('open-delete-modal', {
                                            action: '{{ route('pengeluaran.destroy', $item->id) }}',
                                            message: 'Yakin ingin menghapus {{ addslashes($item->nama_sumber) }}?'
                                        })"
                                        class="w-8 h-8 rounded-full bg-red-50/50 border border-red-200 flex items-center justify-center text-red-400 hover:bg-red-100 hover:border-red-400 hover:text-red-600 transition-all duration-200">
                                        <span class="material-symbols-outlined text-[16px]">delete</span>
                                    </button>
                                </div>
                            </td>

                        </tr>

                    @empty
                        <tr>
                            <td colspan="10" class="py-10 text-center">
                                <div class="flex flex-col items-center gap-2">
                                    <span class="material-symbols-outlined text-4xl text-gray-300">
                                        account_balance_wallet
                                    </span>
                                    <h3 class="font-medium text-gray-700 text-sm">
                                        Belum ada pos anggaran
                                    </h3>
                                    <p class="text-xs text-gray-500">
                                        Tambahkan data pengeluaran pertama untuk bidang ini.
                                    </p>
                                </div>
                            </td>
                        </tr>
                    @endforelse

                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        <x-pagination :paginator="$pengeluaran" />

    </div>

    <x-modal-delete />

</x-app-layout>
