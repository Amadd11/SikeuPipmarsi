<x-app-layout>
    <x-slot:title>Indikator Mutu</x-slot>

    {{-- Flash Message --}}
    @if (session('success'))
        <div class="mb-4 px-4 py-3 rounded-xl bg-green-50 border border-green-100 text-green-700 text-xs flex items-center gap-2">
            <span class="material-symbols-outlined text-[16px]">check_circle</span>
            {{ session('success') }}
        </div>
    @endif

    {{-- Page Header --}}
    <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4 mb-5">
        <div>
            <p class="text-xs text-gray-500 mt-1">
                Daftar indikator mutu organisasi per bidang kerja beserta status pencapaiannya
            </p>
        </div>

        <a href="{{ route('indikator-mutu.create') }}"
            class="inline-flex items-center justify-center gap-1.5 bg-primary text-white px-3.5 py-2 rounded-xl text-xs font-medium hover:bg-primary-dark transition">
            <span class="material-symbols-outlined text-[16px]">add</span>
            Tambah Indikator
        </a>
    </div>

    {{-- Tabs Filter per Bidang --}}
    <div class="flex flex-wrap gap-1.5 mb-5">

        {{-- Tab: Semua --}}
        <a href="{{ route('indikator-mutu.index') }}"
            class="inline-flex items-center gap-1.5 px-3 py-2 rounded-lg text-xs font-{{ $activeBidang === null ? 'semibold' : 'medium' }}
                {{ $activeBidang === null ? 'bg-primary text-white shadow-sm' : 'bg-white text-gray-600 border border-gray-200 hover:bg-gray-50 hover:text-gray-900' }}
                transition-colors">
            <span class="material-symbols-outlined text-[16px] {{ $activeBidang === null ? '' : 'text-gray-400' }}">
                grid_view
            </span>
            Semua
        </a>

        {{-- Tab per Bidang --}}
        @foreach ($bidangKerjaList as $bidang)
            <a href="{{ route('indikator-mutu.index', ['bidang' => $bidang->id]) }}"
                class="inline-flex items-center gap-1.5 px-3 py-2 rounded-lg text-xs font-{{ $activeBidang === $bidang->id ? 'semibold' : 'medium' }}
                    {{ $activeBidang === $bidang->id ? 'bg-primary text-white shadow-sm' : 'bg-white text-gray-600 border border-gray-200 hover:bg-gray-50 hover:text-gray-900' }}
                    transition-colors">
                <span class="material-symbols-outlined text-[16px] {{ $activeBidang === $bidang->id ? '' : 'text-gray-400' }}">
                    {{ $bidang->icon ?? 'domain' }}
                </span>
                {{ $bidang->nama }}
            </a>
        @endforeach
    </div>

    {{-- Main Card: Daftar Indikator --}}
    <div class="bg-white rounded-2xl border border-gray-200 overflow-hidden shadow-sm mb-5">

        {{-- Card Header --}}
        <div class="p-5 border-b border-gray-100">
            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
                <div>
                    <h2 class="font-semibold text-gray-900 text-sm">Daftar Indikator Mutu</h2>
                    <p class="text-xs text-gray-500 mt-0.5">
                        Menampilkan {{ $indikators->total() }} indikator mutu.
                    </p>
                </div>

                {{-- Status Legend --}}
                <div class="flex flex-wrap gap-2">
                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg text-[10px] font-medium bg-gray-100 text-gray-600">
                        <span class="w-1.5 h-1.5 rounded-full bg-gray-400 inline-block"></span>Belum
                    </span>
                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg text-[10px] font-medium bg-amber-50 text-amber-700">
                        <span class="w-1.5 h-1.5 rounded-full bg-amber-400 inline-block"></span>Proses
                    </span>
                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg text-[10px] font-medium bg-emerald-50 text-emerald-700">
                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 inline-block"></span>Tercapai
                    </span>
                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg text-[10px] font-medium bg-red-50 text-red-700">
                        <span class="w-1.5 h-1.5 rounded-full bg-red-500 inline-block"></span>Tidak Tercapai
                    </span>
                </div>
            </div>
        </div>

        {{-- Table --}}
        <div class="overflow-x-auto">
            <table class="w-full text-xs">
                <thead>
                    <tr class="bg-gray-50 text-[10px] uppercase tracking-widest text-gray-500 font-semibold border-b border-gray-100">
                        <th class="px-4 py-2.5 text-center w-10">#</th>
                        <th class="px-4 py-2.5 text-left w-20">Kode</th>
                        <th class="px-4 py-2.5 text-left">Nama Indikator</th>
                        <th class="px-4 py-2.5 text-left">Bidang Kerja</th>
                        <th class="px-4 py-2.5 text-left">Target</th>
                        <th class="px-4 py-2.5 text-left">Periode</th>
                        <th class="px-4 py-2.5 text-center">Status</th>
                        <th class="px-4 py-2.5 text-center">Aksi</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-gray-100 text-gray-700">

                    @forelse ($indikators as $item)
                        <tr class="hover:bg-gray-50/70 transition group">

                            {{-- No --}}
                            <td class="px-4 py-3 text-center text-gray-400 font-medium align-top">
                                {{ $loop->iteration + ($indikators->currentPage() - 1) * $indikators->perPage() }}
                            </td>

                            {{-- Kode --}}
                            <td class="px-4 py-3 align-top">
                                <span class="inline-flex items-center px-2 py-0.5 rounded-md bg-indigo-50 text-indigo-700 text-[10px] font-bold border border-indigo-100/50 tracking-wide">
                                    {{ $item->kode }}
                                </span>
                            </td>

                            {{-- Nama --}}
                            <td class="px-4 py-3 align-top max-w-xs">
                                <span class="font-medium text-gray-900 leading-tight block">
                                    {{ $item->nama }}
                                </span>
                                @if ($item->catatan)
                                    <span class="text-[10px] text-gray-400 mt-0.5 block">
                                        {{ Str::limit($item->catatan, 60) }}
                                    </span>
                                @endif
                            </td>

                            {{-- Bidang Kerja --}}
                            <td class="px-4 py-3 align-top">
                                <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-md bg-gray-100 text-gray-700 text-[10px] font-medium border border-gray-200/50">
                                    <span class="material-symbols-outlined text-[12px] text-gray-400">{{ $item->bidangKerja->icon ?? 'domain' }}</span>
                                    {{ $item->bidangKerja->kode ?? '-' }}
                                </span>
                            </td>

                            {{-- Target --}}
                            <td class="px-4 py-3 align-top text-gray-700 font-medium whitespace-nowrap">
                                {{ $item->target }}
                            </td>

                            {{-- Periode --}}
                            <td class="px-4 py-3 align-top text-gray-500 whitespace-nowrap">
                                {{ $item->periode ?? '-' }}
                            </td>

                            {{-- Status --}}
                            <td class="px-4 py-3 text-center align-top">
                                @php
                                    $statusMap = [
                                        'tercapai'       => ['bg-emerald-100 text-emerald-700', 'Tercapai'],
                                        'proses'         => ['bg-amber-100 text-amber-700', 'Proses'],
                                        'tidak tercapai' => ['bg-red-100 text-red-700', 'Tidak Tercapai'],
                                        'belum'          => ['bg-gray-100 text-gray-600', 'Belum'],
                                    ];
                                    [$badge, $label] = $statusMap[$item->status] ?? ['bg-gray-100 text-gray-600', ucfirst($item->status)];
                                @endphp
                                <span class="inline-flex px-2 py-0.5 rounded-md {{ $badge }} text-[10px] font-semibold tracking-wide whitespace-nowrap">
                                    {{ $label }}
                                </span>
                            </td>

                            {{-- Aksi --}}
                            <td class="px-4 py-3 align-top">
                                <div class="flex items-center justify-center gap-1.5">
                                    <a href="{{ route('indikator-mutu.edit', $item->id) }}"
                                        class="w-7 h-7 rounded-md border border-gray-200 flex items-center justify-center text-gray-500 hover:border-primary hover:text-primary transition">
                                        <span class="material-symbols-outlined text-[16px]">edit</span>
                                    </a>

                                    <button
                                        @click="$dispatch('open-delete-modal', {
                                            action: '{{ route('indikator-mutu.destroy', $item->id) }}',
                                            message: 'Yakin ingin menghapus indikator {{ addslashes($item->kode) }} — {{ addslashes(Str::limit($item->nama, 40)) }}?'
                                        })"
                                        class="w-7 h-7 rounded-md border border-red-200 flex items-center justify-center text-red-500 hover:bg-red-50 transition">
                                        <span class="material-symbols-outlined text-[16px]">delete</span>
                                    </button>
                                </div>
                            </td>

                        </tr>

                    @empty
                        <tr>
                            <td colspan="8" class="py-14 text-center">
                                <div class="flex flex-col items-center gap-2">
                                    <span class="material-symbols-outlined text-4xl text-gray-300">verified</span>
                                    <h3 class="font-medium text-gray-700 text-sm">Belum ada indikator mutu</h3>
                                    <p class="text-xs text-gray-500">Tambahkan indikator mutu pertama untuk bidang ini.</p>
                                    <a href="{{ route('indikator-mutu.create') }}"
                                        class="mt-2 inline-flex items-center gap-1.5 bg-primary text-white px-3.5 py-2 rounded-xl text-xs font-medium hover:bg-primary-dark transition">
                                        <span class="material-symbols-outlined text-[16px]">add</span>
                                        Tambah Sekarang
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforelse

                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @if ($indikators->hasPages())
            <div class="px-5 py-3 border-t border-gray-100 text-xs bg-gray-50/30">
                {{ $indikators->appends(request()->query())->links() }}
            </div>
        @endif

    </div>

    <x-modal-delete />

</x-app-layout>
