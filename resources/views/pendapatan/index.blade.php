<x-app-layout>
    <x-slot:title>Rencana Pendapatan</x-slot>

    {{-- Header --}}
    <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4 mb-6">

        <div>
            <h1 class="text-2xl font-semibold text-gray-900">
                Rencana Pendapatan
            </h1>

            <p class="text-sm text-gray-500 mt-1">
                Pengelolaan seluruh sumber pendapatan organisasi PIPMARSI
            </p>
        </div>

        <a href="{{ route('pendapatan.create') }}"
            class="inline-flex items-center gap-2 bg-primary text-white px-4 py-2.5 rounded-xl text-sm font-medium hover:bg-primary-dark transition">
            <span class="material-symbols-outlined text-[18px]">add</span>
            Tambah Pendapatan
        </a>

    </div>

    {{-- Card --}}
    <div class="bg-white rounded-2xl border border-gray-200 overflow-hidden">

        {{-- Summary --}}
        <div class="p-6 border-b border-gray-100">

            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">

                <div>
                    <h2 class="font-semibold text-gray-900">
                        Daftar Rencana Pendapatan
                    </h2>

                    <p class="text-sm text-gray-500 mt-1">
                        Ringkasan target dan realisasi pendapatan organisasi
                    </p>
                </div>

                <div class="flex flex-wrap gap-3">

                    <div class="bg-gray-50 rounded-xl px-4 py-3 min-w-45">
                        <p class="text-[10px] uppercase tracking-wider text-gray-500">
                            Total Rencana
                        </p>

                        <p class="font-bold text-gray-900 mt-1">
                            Rp {{ number_format($totalRencana, 0, ',', '.') }}
                        </p>
                    </div>

                    <div class="bg-green-50 rounded-xl px-4 py-3 min-w-45">
                        <p class="text-[10px] uppercase tracking-wider text-green-600">
                            Total Realisasi
                        </p>

                        <p class="font-bold text-green-700 mt-1">
                            Rp {{ number_format($totalRealisasi, 0, ',', '.') }}
                        </p>
                    </div>

                    <div class="bg-emerald-50 rounded-xl px-4 py-3 min-w-45">
                        <p class="text-[10px] uppercase tracking-wider text-black">
                            Sisa Pendapatan
                        </p>

                        <p class="font-bold text-black mt-1">
                            Rp {{ number_format($sisaAnggaran, 0, ',', '.') }}
                        </p>
                    </div>

                </div>

            </div>

        </div>

        {{-- Table --}}
        <div class="overflow-x-auto">

            <table class="w-full">

                <thead>
                    <tr
                        class="bg-gray-50 text-[10px] uppercase tracking-widest text-gray-500 font-semibold border-b border-gray-100">

                        <th class="px-5 py-3 text-left">Pendapatan</th>
                        <th class="px-5 py-3 text-left">Kategori</th>
                        <th class="px-5 py-3 text-right">Rencana</th>
                        <th class="px-5 py-3 text-right">Realisasi</th>
                        <th class="px-5 py-3 text-right">Sisa</th>
                        <th class="px-5 py-3 text-center">Progress</th>
                        <th class="px-5 py-3 text-center">Status</th>
                        <th class="px-5 py-3 text-center">Aksi</th>

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

                            {{-- Pendapatan --}}
                            <td class="px-5 py-4">

                                <div class="flex flex-col">

                                    <span class="text-sm font-semibold text-gray-900">
                                        {{ $row->nama_sumber }}
                                    </span>

                                    <span class="text-xs text-gray-500 mt-1">
                                        {{ $row->keterangan }}
                                    </span>

                                </div>

                            </td>

                            {{-- Kategori --}}
                            <td class="px-5 py-4">

                                <span
                                    class="inline-flex items-center px-2.5 py-1 rounded-full bg-emerald-50 text-emerald-700 text-[11px] font-medium">
                                    {{ $row->kategoriPendapatan->nama }}
                                </span>

                            </td>

                            {{-- Rencana --}}
                            <td class="px-5 py-4 text-right">

                                <span class="text-sm font-semibold text-gray-900">
                                    Rp {{ number_format($row->jumlah_rencana, 0, ',', '.') }}
                                </span>

                            </td>

                            {{-- Realisasi --}}
                            <td class="px-5 py-4 text-right">

                                <span class="text-sm font-semibold text-green-600">
                                    Rp {{ number_format($row->jumlah_realisasi, 0, ',', '.') }}
                                </span>

                            </td>

                            {{-- Sisa --}}
                            <td class="px-5 py-4 text-right">

                                <span class="text-sm font-semibold text-gray-800">
                                    Rp {{ number_format($sisa, 0, ',', '.') }}
                                </span>

                            </td>

                            {{-- Progress --}}
                            <td class="px-5 py-4 w-48">

                                <div class="flex items-center gap-3">

                                    <div class="flex-1 h-2 bg-gray-100 rounded-full overflow-hidden">

                                        <div class="h-full bg-primary" style="width: {{ $persen }}%">
                                        </div>

                                    </div>

                                    <span class="text-[11px] font-medium text-gray-500">
                                        {{ $persen }}%
                                    </span>

                                </div>

                            </td>

                            {{-- Status --}}
                            <td class="px-5 py-4 text-center">

                                @if ($status === 'Tercapai')
                                    <span
                                        class="inline-flex px-2.5 py-1 rounded-full bg-green-100 text-green-700 text-[11px] font-medium">
                                        {{ $status }}
                                    </span>
                                @elseif ($status === 'Sebagian')
                                    <span
                                        class="inline-flex px-2.5 py-1 rounded-full bg-amber-100 text-amber-700 text-[11px] font-medium">
                                        {{ $status }}
                                    </span>
                                @else
                                    <span
                                        class="inline-flex px-2.5 py-1 rounded-full bg-gray-100 text-gray-600 text-[11px] font-medium">
                                        {{ $status }}
                                    </span>
                                @endif

                            </td>

                            {{-- Aksi --}}
                            <td class="px-5 py-4">
                                <div x-data="{ open: false }" class="flex justify-center gap-2">

                                    <a href="{{ route('pendapatan.edit', $row->id) }}"
                                        class="w-8 h-8 rounded-lg border border-gray-200 flex items-center justify-center text-gray-500 hover:border-primary hover:text-primary transition">
                                        <span class="material-symbols-outlined text-[18px]">
                                            edit
                                        </span>
                                    </a>

                                    <button @click="open = true"
                                        class="w-8 h-8 rounded-lg border border-red-200 flex items-center justify-center text-red-500 hover:bg-red-50 transition">
                                        <span class="material-symbols-outlined text-[18px]">
                                            delete
                                        </span>
                                    </button>

                                    <x-modal-delete :action="route('pendapatan.destroy', $row->id)" title="Hapus Pendapatan"
                                        message="Yakin ingin menghapus {{ $row->nama_sumber }}?" />
                                </div>
                            </td>

                        </tr>

                    @empty

                        <tr>
                            <td colspan="8" class="py-12 text-center">

                                <div class="flex flex-col items-center gap-2">

                                    <span class="material-symbols-outlined text-5xl text-gray-300">
                                        payments
                                    </span>

                                    <h3 class="font-medium text-gray-700">
                                        Belum ada data pendapatan
                                    </h3>

                                    <p class="text-sm text-gray-500">
                                        Tambahkan data pendapatan pertama Anda.
                                    </p>

                                </div>

                            </td>
                        </tr>
                    @endforelse

                </tbody>

            </table>

        </div>

    </div>
</x-app-layout>
