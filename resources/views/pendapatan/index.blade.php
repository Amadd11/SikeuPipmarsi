<x-app-layout>
    <x-slot:title>Rencana Pendapatan</x-slot>

    {{-- Header --}}
    <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4 mb-5">
        <div>
            <p class="text-xs text-gray-500 mt-1">
                Pengelolaan seluruh sumber pendapatan organisasi PIPMARSI
            </p>
        </div>

        <a href="{{ route('pendapatan.create') }}"
            class="inline-flex items-center gap-1.5 bg-primary text-white px-3.5 py-2 rounded-xl text-xs font-medium hover:bg-primary-dark transition">
            <span class="material-symbols-outlined text-[16px]">add</span>
            Tambah Pendapatan
        </a>
    </div>

    {{-- Card --}}
    <div class="bg-white rounded-2xl border border-gray-200 overflow-hidden shadow-sm">

        {{-- Summary --}}
        <div class="p-5 border-b border-gray-100">
            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
                <div>
                    <h2 class="font-semibold text-gray-900 text-sm">
                        Daftar Rencana Pendapatan
                    </h2>
                    <p class="text-xs text-gray-500 mt-0.5">
                        Ringkasan target dan realisasi pendapatan organisasi
                    </p>
                </div>

                <div class="flex flex-wrap gap-2.5">
                    <div class="bg-gray-50 rounded-xl px-3.5 py-2.5 min-w-40">
                        <p class="text-[10px] uppercase tracking-wider text-gray-500">Total Rencana</p>
                        <p class="font-bold text-gray-900 mt-0.5 text-sm">
                            Rp {{ number_format($totalRencana, 0, ',', '.') }}
                        </p>
                    </div>

                    <div class="bg-green-50 rounded-xl px-3.5 py-2.5 min-w-40">
                        <p class="text-[10px] uppercase tracking-wider text-green-600">Total Realisasi</p>
                        <p class="font-bold text-green-700 mt-0.5 text-sm">
                            Rp {{ number_format($totalRealisasi, 0, ',', '.') }}
                        </p>
                    </div>

                    <div class="bg-emerald-50 rounded-xl px-3.5 py-2.5 min-w-40">
                        <p class="text-[10px] uppercase tracking-wider text-black">Sisa Pendapatan</p>
                        <p class="font-bold text-black mt-0.5 text-sm">
                            Rp {{ number_format($sisaAnggaran, 0, ',', '.') }}
                        </p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Table --}}
        <div class="overflow-x-auto">
            <table class="w-full text-xs">
                <thead>
                    <tr
                        class="bg-gray-50 text-[10px] uppercase tracking-widest text-gray-500 font-semibold border-b border-gray-100">
                        <th class="px-4 py-2.5 text-center w-10">#</th>
                        <th class="px-4 py-2.5 text-left">Pendapatan</th>
                        <th class="px-4 py-2.5 text-left">Kategori</th>
                        <th class="px-4 py-2.5 text-right">Rencana</th>
                        <th class="px-4 py-2.5 text-right">Realisasi</th>
                        <th class="px-4 py-2.5 text-right">Sisa</th>
                        <th class="px-4 py-2.5 text-center w-32">Progress</th>
                        <th class="px-4 py-2.5 text-center">Status</th>
                        <th class="px-4 py-2.5 text-center">Aksi</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-gray-100">

                    @forelse ($pendapatan as $row)
                        @php
                            $persen = $row['rencana'] > 0 ? round(($row['realisasi'] / $row['rencana']) * 100) : 0;
                            $sisa = $row['rencana'] - $row['realisasi'];
                            $status = $persen >= 100 ? 'Tercapai' : ($persen > 0 ? 'Sebagian' : 'Belum');
                        @endphp

                        <tr class="hover:bg-gray-50/70 transition">
                            <td class="px-4 py-2.5 text-center text-gray-400 font-medium">
                                {{ $loop->iteration }}
                            </td>

                            <td class="px-4 py-2.5">
                                <div class="flex flex-col">
                                    <span
                                        class="font-semibold text-gray-900 leading-tight">{{ $row->nama_sumber }}</span>
                                    <span class="text-[10px] text-gray-500 mt-0.5">{{ $row->keterangan }}</span>
                                </div>
                            </td>

                            <td class="px-4 py-2.5">
                                <span
                                    class="inline-flex items-center px-2 py-0.5 rounded-md bg-emerald-50 text-emerald-700 text-[10px] font-medium border border-emerald-100/50">
                                    {{ $row->kategoriPendapatan->nama }}
                                </span>
                            </td>

                            <td class="px-4 py-2.5 text-right font-semibold text-gray-900 whitespace-nowrap">
                                Rp {{ number_format($row->jumlah_rencana, 0, ',', '.') }}
                            </td>

                            <td class="px-4 py-2.5 text-right font-semibold text-green-600 whitespace-nowrap">
                                Rp {{ number_format($row->jumlah_realisasi, 0, ',', '.') }}
                            </td>

                            <td class="px-4 py-2.5 text-right font-semibold text-gray-800 whitespace-nowrap">
                                Rp {{ number_format($sisa, 0, ',', '.') }}
                            </td>

                            <td class="px-4 py-2.5">
                                <div class="flex items-center gap-2">
                                    <div class="flex-1 h-1.5 bg-gray-100 rounded-full overflow-hidden">
                                        <div class="h-full bg-primary" style="width: {{ min($persen, 100) }}%"></div>
                                    </div>
                                    <span
                                        class="text-[10px] font-medium text-gray-500 w-6 text-right">{{ $persen }}%</span>
                                </div>
                            </td>

                            <td class="px-4 py-2.5 text-center">
                                @if ($status === 'Tercapai')
                                    <span
                                        class="inline-flex px-2 py-0.5 rounded-md bg-green-100 text-green-700 text-[10px] font-semibold tracking-wide">
                                        {{ $status }}
                                    </span>
                                @elseif ($status === 'Sebagian')
                                    <span
                                        class="inline-flex px-2 py-0.5 rounded-md bg-amber-100 text-amber-700 text-[10px] font-semibold tracking-wide">
                                        {{ $status }}
                                    </span>
                                @else
                                    <span
                                        class="inline-flex px-2 py-0.5 rounded-md bg-gray-100 text-gray-600 text-[10px] font-semibold tracking-wide">
                                        {{ $status }}
                                    </span>
                                @endif
                            </td>

                            {{-- Aksi --}}
                            <td class="px-4 py-2.5">
                                <div class="flex items-center justify-center gap-1.5">
                                    <a href="{{ route('pendapatan.edit', $row->id) }}"
                                        class="w-7 h-7 rounded-md border border-gray-200 flex items-center justify-center text-gray-500 hover:border-primary hover:text-primary transition">
                                        <span class="material-symbols-outlined text-[16px]">edit</span>
                                    </a>

                                    <button
                                        @click="$dispatch('open-delete-modal', {
                                            action: '{{ route('pendapatan.destroy', $row->id) }}',
                                            message: 'Yakin ingin menghapus {{ addslashes($row->nama_sumber) }}?'
                                        })"
                                        class="w-7 h-7 rounded-md border border-red-200 flex items-center justify-center text-red-500 hover:bg-red-50 transition">
                                        <span class="material-symbols-outlined text-[16px]">delete</span>
                                    </button>
                                </div>
                            </td>

                        </tr>

                    @empty

                        <tr>
                            <td colspan="9" class="py-10 text-center">
                                <div class="flex flex-col items-center gap-2">
                                    <span class="material-symbols-outlined text-4xl text-gray-300">payments</span>
                                    <h3 class="font-medium text-gray-700 text-sm">Belum ada data pendapatan</h3>
                                    <p class="text-xs text-gray-500">Tambahkan data pendapatan pertama Anda.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse

                </tbody>
            </table>
        </div>
    </div>

    <x-modal-delete />

</x-app-layout>
