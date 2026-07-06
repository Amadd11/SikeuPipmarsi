<x-app-layout>
    <x-slot:title>Manajemen Bidang Kerja</x-slot>

    {{-- Flash Messages --}}
    @if (session('success'))
        <div class="mb-4 px-4 py-3 rounded-xl bg-green-50 border border-green-100 text-green-700 text-xs flex items-center gap-2">
            <span class="material-symbols-outlined text-[16px]">check_circle</span>
            {{ session('success') }}
        </div>
    @endif

    @if ($errors->has('general'))
        <div class="mb-4 px-4 py-3 rounded-xl bg-red-50 border border-red-100 text-red-600 text-xs flex items-center gap-2">
            <span class="material-symbols-outlined text-[16px]">error</span>
            {{ $errors->first('general') }}
        </div>
    @endif

    {{-- Page Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 mb-5">
        <p class="text-xs text-gray-500">
            Kelola daftar bidang kerja yang digunakan dalam sistem
        </p>
        <a href="{{ route('bidang-kerja.create') }}"
            class="inline-flex items-center justify-center gap-2 bg-gold text-gray-900 px-4 py-2 rounded-full text-xs font-semibold shadow hover:bg-gold-dark hover:shadow-md transition-all duration-200 active:scale-95 w-full sm:w-auto">
            <span class="material-symbols-outlined text-[16px]">add_circle</span>
            Tambah Bidang Kerja
        </a>
    </div>

    {{-- Search --}}
    <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-4 mb-5">
        <form method="GET" action="{{ route('bidang-kerja.index') }}" class="flex flex-col sm:flex-row items-stretch sm:items-end gap-3">
            <div class="flex-1">
                <label class="block text-[10px] font-semibold text-gray-500 uppercase tracking-wider mb-1.5">Cari Bidang Kerja</label>
                <input type="text" name="search" value="{{ $search ?? '' }}"
                    placeholder="Nama atau kode..."
                    class="w-full px-3 py-2 text-xs rounded-xl border border-gray-200 focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none">
            </div>
            <div class="flex gap-2">
                <button type="submit"
                    class="flex-1 sm:flex-none inline-flex items-center justify-center gap-1.5 bg-gold text-gray-900 px-4 py-2 rounded-full text-xs font-semibold shadow-sm hover:bg-gold-dark hover:shadow transition-all duration-200 active:scale-95">
                    <span class="material-symbols-outlined text-[16px]" style="font-variation-settings: 'FILL' 1">search</span>
                    Cari
                </button>
                @if ($search)
                    <a href="{{ route('bidang-kerja.index') }}"
                        class="flex-1 sm:flex-none inline-flex items-center justify-center gap-1.5 bg-white text-gray-500 px-4 py-2 rounded-full text-xs font-medium border border-gray-200 hover:bg-gray-50 hover:border-gray-300 transition-all duration-200 active:scale-95">
                        <span class="material-symbols-outlined text-[15px]">close</span>
                        Reset
                    </a>
                @endif
            </div>
        </form>
    </div>

    {{-- Data Table --}}
    <div class="bg-white rounded-2xl border border-gray-200 overflow-hidden shadow-sm">

        {{-- Card Header --}}
        <div class="p-4 sm:p-5 border-b border-gray-100">
            <div class="flex flex-col xs:flex-row xs:items-center xs:justify-between gap-2">
                <div>
                    <h2 class="font-semibold text-gray-900 text-sm">Daftar Bidang Kerja</h2>
                    <p class="text-xs text-gray-500 mt-0.5">
                        Menampilkan {{ $bidangKerjaList->total() }} bidang kerja terdaftar.
                    </p>
                </div>
                <span class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-indigo-50 border border-indigo-100 text-indigo-600 text-[10px] font-semibold rounded-lg self-start xs:self-auto shrink-0">
                    <span class="material-symbols-outlined text-[13px]">shield</span>
                    Super Admin Only
                </span>
            </div>
        </div>

        {{-- Table (scrollable on mobile) --}}
        <div class="overflow-x-auto -mx-px">
            <table class="w-full text-xs min-w-[640px]">
                <thead>
                    <tr class="bg-gray-50 text-[10px] uppercase tracking-widest text-gray-500 font-semibold border-b border-gray-100">
                        <th class="px-4 py-2.5 text-center w-10">#</th>
                        <th class="px-4 py-2.5 text-left">Kode</th>
                        <th class="px-4 py-2.5 text-left">Nama Bidang Kerja</th>
                        <th class="px-4 py-2.5 text-left hidden md:table-cell">Deskripsi</th>
                        <th class="px-4 py-2.5 text-center hidden sm:table-cell">Warna</th>
                        <th class="px-4 py-2.5 text-center hidden sm:table-cell">Urutan</th>
                        <th class="px-4 py-2.5 text-center">Aksi</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-gray-100 text-gray-700">

                    @forelse ($bidangKerjaList as $item)
                        <tr class="hover:bg-gray-50/70 transition group">

                            {{-- No --}}
                            <td class="px-4 py-3 text-center text-gray-400 font-medium align-middle">
                                {{ $loop->iteration + ($bidangKerjaList->currentPage() - 1) * $bidangKerjaList->perPage() }}
                            </td>

                            {{-- Kode --}}
                            <td class="px-4 py-3 align-middle">
                                <span class="inline-block px-2 py-0.5 rounded-md font-mono font-bold text-[11px]"
                                    style="background-color: {{ $item->warna_hex ? $item->warna_hex . '22' : '#6366f122' }}; color: {{ $item->warna_hex ?? '#6366f1' }};">
                                    {{ $item->kode }}
                                </span>
                            </td>

                            {{-- Nama --}}
                            <td class="px-4 py-3 align-middle">
                                <div class="flex items-center gap-2.5">
                                    @if ($item->warna_hex)
                                        <div class="w-2.5 h-2.5 rounded-full shrink-0"
                                            style="background-color: {{ $item->warna_hex }};"></div>
                                    @endif
                                    <span class="font-medium text-gray-900">{{ $item->nama }}</span>
                                </div>
                            </td>

                            {{-- Deskripsi (hidden on small screens) --}}
                            <td class="px-4 py-3 align-middle text-gray-500 max-w-xs hidden md:table-cell">
                                <span class="line-clamp-1">{{ $item->deskripsi ?? '—' }}</span>
                            </td>

                            {{-- Warna (hidden on xs screens) --}}
                            <td class="px-4 py-3 text-center align-middle hidden sm:table-cell">
                                @if ($item->warna_hex)
                                    <div class="flex items-center justify-center gap-1.5">
                                        <div class="w-5 h-5 rounded-md border border-gray-200 shadow-sm"
                                            style="background-color: {{ $item->warna_hex }};"></div>
                                        <span class="font-mono text-[10px] text-gray-500">{{ $item->warna_hex }}</span>
                                    </div>
                                @else
                                    <span class="text-gray-300">—</span>
                                @endif
                            </td>

                            {{-- Urutan (hidden on xs screens) --}}
                            <td class="px-4 py-3 text-center align-middle hidden sm:table-cell">
                                <span class="inline-flex items-center justify-center w-6 h-6 bg-gray-100 text-gray-600 rounded-md font-semibold text-[11px]">
                                    {{ $item->urutan }}
                                </span>
                            </td>

                            {{-- Aksi --}}
                            <td class="px-4 py-3 align-middle">
                                <div class="flex items-center justify-center gap-1.5">
                                    <a href="{{ route('bidang-kerja.edit', $item->id) }}"
                                        class="w-8 h-8 rounded-full bg-gray-50 border border-gray-200 flex items-center justify-center text-gray-500 hover:bg-indigo-50 hover:border-indigo-300 hover:text-indigo-600 transition-all duration-200"
                                        title="Edit">
                                        <span class="material-symbols-outlined text-[16px]">edit</span>
                                    </a>
                                    <button
                                        @click="$dispatch('open-delete-modal', {
                                            action: '{{ route('bidang-kerja.destroy', $item->id) }}',
                                            message: 'Yakin ingin menghapus bidang kerja {{ addslashes($item->nama) }}? Tindakan ini tidak dapat dibatalkan.'
                                        })"
                                        class="w-8 h-8 rounded-full bg-red-50/50 border border-red-200 flex items-center justify-center text-red-400 hover:bg-red-100 hover:border-red-400 hover:text-red-600 transition-all duration-200"
                                        title="Hapus">
                                        <span class="material-symbols-outlined text-[16px]">delete</span>
                                    </button>
                                </div>
                            </td>

                        </tr>

                    @empty
                        <tr>
                            <td colspan="7" class="py-14 text-center">
                                <div class="flex flex-col items-center gap-2 px-4">
                                    <span class="material-symbols-outlined text-4xl text-gray-300">category</span>
                                    <h3 class="font-medium text-gray-700 text-sm">Belum ada bidang kerja</h3>
                                    <p class="text-xs text-gray-500">Tambahkan bidang kerja pertama untuk sistem ini.</p>
                                    <a href="{{ route('bidang-kerja.create') }}"
                                        class="mt-3 inline-flex items-center gap-2 bg-gold text-gray-900 px-5 py-2 rounded-full text-xs font-semibold shadow hover:bg-gold-dark hover:shadow-md transition-all duration-200 active:scale-95">
                                        <span class="material-symbols-outlined text-[16px]">add_circle</span>
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
        @if ($bidangKerjaList->hasPages())
            <div class="px-4 sm:px-5 py-3 border-t border-gray-100 text-xs bg-gray-50/30">
                {{ $bidangKerjaList->appends(request()->query())->links() }}
            </div>
        @endif

    </div>

    <x-modal-delete />

</x-app-layout>
