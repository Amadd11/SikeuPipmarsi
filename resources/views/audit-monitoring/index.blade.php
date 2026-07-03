<x-app-layout>
    <x-slot:title>Audit & Monitoring</x-slot>

    {{-- Flash Messages --}}
    @if (session('success'))
        <div class="mb-4 px-4 py-3 rounded-xl bg-green-50 border border-green-100 text-green-700 text-xs flex items-center gap-2">
            <span class="material-symbols-outlined text-[16px]">check_circle</span>
            {{ session('success') }}
        </div>
    @endif

    @if (session('warning'))
        <div class="mb-4 px-4 py-3 rounded-xl bg-amber-50 border border-amber-100 text-amber-700 text-xs flex items-center gap-2">
            <span class="material-symbols-outlined text-[16px]">warning</span>
            {{ session('warning') }}
        </div>
    @endif

    @if ($errors->has('general'))
        <div class="mb-4 px-4 py-3 rounded-xl bg-red-50 border border-red-100 text-red-600 text-xs flex items-center gap-2">
            <span class="material-symbols-outlined text-[16px]">error</span>
            {{ $errors->first('general') }}
        </div>
    @endif

    {{-- Page Header --}}
    <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4 mb-5">
        <div>
            <p class="text-xs text-gray-500 mt-1">
                Pencatatan hasil audit dan monitoring pelaksanaan indikator mutu per tahun anggaran
            </p>
        </div>

        <a href="{{ route('audit-monitoring.create') }}"
            class="inline-flex items-center justify-center gap-1.5 bg-primary text-white px-3.5 py-2 rounded-xl text-xs font-medium hover:bg-primary-dark transition">
            <span class="material-symbols-outlined text-[16px]">add</span>
            Tambah Audit
        </a>
    </div>

    {{-- Stat Cards --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-5">
        <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-4">
            <div class="flex items-center gap-3">
                <div class="w-9 h-9 rounded-xl bg-indigo-50 flex items-center justify-center">
                    <span class="material-symbols-outlined text-indigo-500 text-[18px]">manage_search</span>
                </div>
                <div>
                    <p class="text-[10px] text-gray-500 uppercase tracking-wider font-semibold">Total Audit</p>
                    <p class="text-xl font-bold text-gray-900 leading-tight">{{ $statistik['total'] }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-4">
            <div class="flex items-center gap-3">
                <div class="w-9 h-9 rounded-xl bg-emerald-50 flex items-center justify-center">
                    <span class="material-symbols-outlined text-emerald-500 text-[18px]">task_alt</span>
                </div>
                <div>
                    <p class="text-[10px] text-gray-500 uppercase tracking-wider font-semibold">Sudah Selesai</p>
                    <p class="text-xl font-bold text-gray-900 leading-tight">{{ $statistik['lengkap'] }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-4">
            <div class="flex items-center gap-3">
                <div class="w-9 h-9 rounded-xl bg-amber-50 flex items-center justify-center">
                    <span class="material-symbols-outlined text-amber-500 text-[18px]">schedule</span>
                </div>
                <div>
                    <p class="text-[10px] text-gray-500 uppercase tracking-wider font-semibold">Belum Selesai</p>
                    <p class="text-xl font-bold text-gray-900 leading-tight">{{ $statistik['belumLengkap'] }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-4">
            <div class="flex items-center gap-3">
                <div class="w-9 h-9 rounded-xl bg-blue-50 flex items-center justify-center">
                    <span class="material-symbols-outlined text-blue-500 text-[18px]">person_pin</span>
                </div>
                <div>
                    <p class="text-[10px] text-gray-500 uppercase tracking-wider font-semibold">Ada PIC</p>
                    <p class="text-xl font-bold text-gray-900 leading-tight">{{ $statistik['berPic'] }}</p>
                </div>
            </div>
        </div>
    </div>

    {{-- Filters --}}
    <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-4 mb-5">
        <form method="GET" action="{{ route('audit-monitoring.index') }}" class="flex flex-wrap items-end gap-3">

            {{-- Filter Tahun --}}
            <div class="flex-1 min-w-[140px]">
                <label class="block text-[10px] font-semibold text-gray-500 uppercase tracking-wider mb-1.5">Tahun Anggaran</label>
                <select name="tahun"
                    class="w-full px-3 py-2 text-xs rounded-xl border border-gray-200 focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none bg-white"
                    onchange="this.form.submit()">
                    @foreach ($tahunAnggaranList as $ta)
                        <option value="{{ $ta->id }}" @selected($activeTahun == $ta->id)>
                            {{ $ta->label ?? $ta->tahun }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- Filter Indikator --}}
            <div class="flex-1 min-w-[180px]">
                <label class="block text-[10px] font-semibold text-gray-500 uppercase tracking-wider mb-1.5">Indikator Mutu</label>
                <select name="indikator_mutu_id"
                    class="w-full px-3 py-2 text-xs rounded-xl border border-gray-200 focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none bg-white">
                    <option value="">Semua Indikator</option>
                    @foreach ($indikatorMutuList as $indikator)
                        <option value="{{ $indikator->id }}" @selected(($filters['indikator_mutu_id'] ?? '') == $indikator->id)>
                            [{{ $indikator->kode }}] {{ Str::limit($indikator->nama, 50) }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- Search --}}
            <div class="flex-1 min-w-[180px]">
                <label class="block text-[10px] font-semibold text-gray-500 uppercase tracking-wider mb-1.5">Cari</label>
                <input type="text" name="search" value="{{ $filters['search'] ?? '' }}"
                    placeholder="PIC, uraian, nama indikator..."
                    class="w-full px-3 py-2 text-xs rounded-xl border border-gray-200 focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none">
            </div>

            <div class="flex gap-2">
                <button type="submit"
                    class="inline-flex items-center gap-1.5 bg-primary text-white px-4 py-2 rounded-xl text-xs font-medium hover:bg-primary-dark transition">
                    <span class="material-symbols-outlined text-[15px]">filter_list</span>
                    Filter
                </button>
                <a href="{{ route('audit-monitoring.index', ['tahun' => $activeTahun]) }}"
                    class="inline-flex items-center gap-1.5 bg-gray-100 text-gray-600 px-4 py-2 rounded-xl text-xs font-medium hover:bg-gray-200 transition">
                    <span class="material-symbols-outlined text-[15px]">refresh</span>
                    Reset
                </a>
            </div>
        </form>
    </div>

    {{-- Data Table --}}
    <div class="bg-white rounded-2xl border border-gray-200 overflow-hidden shadow-sm mb-5">

        {{-- Card Header --}}
        <div class="p-5 border-b border-gray-100">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="font-semibold text-gray-900 text-sm">Daftar Audit & Monitoring</h2>
                    <p class="text-xs text-gray-500 mt-0.5">
                        Menampilkan {{ $auditList->total() }} data audit monitoring.
                    </p>
                </div>
            </div>
        </div>

        {{-- Table --}}
        <div class="overflow-x-auto">
            <table class="w-full text-xs">
                <thead>
                    <tr class="bg-gray-50 text-[10px] uppercase tracking-widest text-gray-500 font-semibold border-b border-gray-100">
                        <th class="px-4 py-2.5 text-center w-10">#</th>
                        <th class="px-4 py-2.5 text-left">Indikator Mutu</th>
                        <th class="px-4 py-2.5 text-left">Tahun</th>
                        <th class="px-4 py-2.5 text-left">PIC</th>
                        <th class="px-4 py-2.5 text-left">Uraian Pelaksanaan</th>
                        <th class="px-4 py-2.5 text-center">Tgl. Selesai</th>
                        <th class="px-4 py-2.5 text-center">Status</th>
                        <th class="px-4 py-2.5 text-center">Aksi</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-gray-100 text-gray-700">

                    @forelse ($auditList as $item)
                        <tr class="hover:bg-gray-50/70 transition group">

                            {{-- No --}}
                            <td class="px-4 py-3 text-center text-gray-400 font-medium align-top">
                                {{ $loop->iteration + ($auditList->currentPage() - 1) * $auditList->perPage() }}
                            </td>

                            {{-- Indikator --}}
                            <td class="px-4 py-3 align-top max-w-[200px]">
                                <span class="inline-flex items-center px-2 py-0.5 rounded-md bg-indigo-50 text-indigo-700 text-[10px] font-bold border border-indigo-100/50 tracking-wide mb-1">
                                    {{ $item->indikatorMutu->kode ?? '-' }}
                                </span>
                                <span class="text-gray-800 font-medium leading-snug block text-[11px]">
                                    {{ Str::limit($item->indikatorMutu->nama ?? '-', 60) }}
                                </span>
                                @if ($item->indikatorMutu?->bidangKerja)
                                    <span class="text-[10px] text-gray-400 mt-0.5 block">
                                        {{ $item->indikatorMutu->bidangKerja->nama }}
                                    </span>
                                @endif
                            </td>

                            {{-- Tahun --}}
                            <td class="px-4 py-3 align-top">
                                <span class="inline-flex items-center px-2 py-0.5 rounded-md bg-gray-100 text-gray-600 text-[10px] font-semibold">
                                    {{ $item->tahunAnggaran->label ?? $item->tahunAnggaran->tahun ?? '-' }}
                                </span>
                            </td>

                            {{-- PIC --}}
                            <td class="px-4 py-3 align-top">
                                @if ($item->pic)
                                    <span class="inline-flex items-center gap-1 text-gray-700">
                                        <span class="material-symbols-outlined text-[13px] text-gray-400">person</span>
                                        {{ $item->pic }}
                                    </span>
                                @else
                                    <span class="text-gray-300 italic">—</span>
                                @endif
                            </td>

                            {{-- Uraian --}}
                            <td class="px-4 py-3 align-top max-w-xs text-gray-600">
                                {{ $item->uraian_pelaksanaan ? Str::limit($item->uraian_pelaksanaan, 80) : '—' }}
                            </td>

                            {{-- Tanggal Selesai --}}
                            <td class="px-4 py-3 text-center align-top whitespace-nowrap">
                                @if ($item->tanggal_penyelesaian)
                                    <span class="text-gray-700 font-medium">
                                        {{ $item->tanggal_penyelesaian->format('d M Y') }}
                                    </span>
                                @else
                                    <span class="text-gray-300 italic">Belum</span>
                                @endif
                            </td>

                            {{-- Status --}}
                            <td class="px-4 py-3 text-center align-top">
                                @if ($item->tanggal_penyelesaian)
                                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-md bg-emerald-100 text-emerald-700 text-[10px] font-semibold">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 inline-block"></span>
                                        Selesai
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-md bg-amber-100 text-amber-700 text-[10px] font-semibold">
                                        <span class="w-1.5 h-1.5 rounded-full bg-amber-400 inline-block"></span>
                                        Proses
                                    </span>
                                @endif
                            </td>

                            {{-- Aksi --}}
                            <td class="px-4 py-3 align-top">
                                <div class="flex items-center justify-center gap-1.5">
                                    <a href="{{ route('audit-monitoring.edit', $item->id) }}"
                                        class="w-7 h-7 rounded-md border border-gray-200 flex items-center justify-center text-gray-500 hover:border-primary hover:text-primary transition">
                                        <span class="material-symbols-outlined text-[16px]">edit</span>
                                    </a>

                                    <button
                                        @click="$dispatch('open-delete-modal', {
                                            action: '{{ route('audit-monitoring.destroy', $item->id) }}',
                                            message: 'Yakin ingin menghapus data audit untuk indikator {{ addslashes($item->indikatorMutu->kode ?? '-') }}?'
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
                                    <span class="material-symbols-outlined text-4xl text-gray-300">manage_search</span>
                                    <h3 class="font-medium text-gray-700 text-sm">Belum ada data audit monitoring</h3>
                                    <p class="text-xs text-gray-500">Tambahkan catatan audit pertama untuk tahun anggaran ini.</p>
                                    <a href="{{ route('audit-monitoring.create') }}"
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
        @if ($auditList->hasPages())
            <div class="px-5 py-3 border-t border-gray-100 text-xs bg-gray-50/30">
                {{ $auditList->appends(request()->query())->links() }}
            </div>
        @endif

    </div>

    <x-modal-delete />

</x-app-layout>
