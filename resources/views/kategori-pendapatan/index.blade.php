<x-app-layout>
    <x-slot:title>Kategori Pendapatan</x-slot>

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
            Kelola master data kategori pendapatan
        </p>
        <a href="{{ route('kategori-pendapatan.create') }}"
            class="inline-flex items-center justify-center gap-2 bg-gold text-gray-900 px-4 py-2 rounded-full text-xs font-semibold shadow hover:bg-gold-dark hover:shadow-md transition-all duration-200 active:scale-95 w-full sm:w-auto">
            <span class="material-symbols-outlined text-[16px]">add_circle</span>
            Tambah Kategori
        </a>
    </div>

    {{-- Search --}}
    <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-4 mb-5">
        <form method="GET" action="{{ route('kategori-pendapatan.index') }}" class="flex flex-col sm:flex-row items-stretch sm:items-end gap-3">
            <div class="flex-1">
                <label class="block text-[10px] font-semibold text-gray-500 uppercase tracking-wider mb-1.5">Cari Kategori</label>
                <input type="text" name="search" value="{{ $search ?? '' }}"
                    placeholder="Ketikkan nama kategori..."
                    class="w-full px-3 py-2 text-xs rounded-xl border border-gray-200 focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none">
            </div>
            <div class="flex gap-2">
                <button type="submit"
                    class="flex-1 sm:flex-none inline-flex items-center justify-center gap-1.5 bg-gold text-gray-900 px-4 py-2 rounded-full text-xs font-semibold shadow-sm hover:bg-gold-dark hover:shadow transition-all duration-200 active:scale-95">
                    <span class="material-symbols-outlined text-[16px]" style="font-variation-settings: 'FILL' 1">search</span>
                    Cari
                </button>
                @if ($search)
                    <a href="{{ route('kategori-pendapatan.index') }}"
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
                    <h2 class="font-semibold text-gray-900 text-sm">Daftar Kategori Pendapatan</h2>
                    <p class="text-xs text-gray-500 mt-0.5">
                        Menampilkan {{ $kategoriList->total() }} kategori terdaftar.
                    </p>
                </div>
                <span class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-indigo-50 border border-indigo-100 text-indigo-600 text-[10px] font-semibold rounded-lg self-start xs:self-auto shrink-0">
                    <span class="material-symbols-outlined text-[13px]">shield</span>
                    Super Admin Only
                </span>
            </div>
        </div>

        {{-- Table --}}
        <div class="overflow-x-auto -mx-px">
            <table class="w-full text-xs min-w-125">
                <thead>
                    <tr class="bg-gray-50 text-[10px] uppercase tracking-widest text-gray-500 font-semibold border-b border-gray-100">
                        <th class="px-4 py-2.5 text-center w-10">#</th>
                        <th class="px-4 py-2.5 text-left">Nama Kategori</th>
                        <th class="px-4 py-2.5 text-center w-28">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 text-gray-700">
                    @forelse ($kategoriList as $item)
                        <tr class="hover:bg-gray-50/70 transition group">
                            <td class="px-4 py-3 text-center text-gray-400 font-medium align-middle">
                                {{ $loop->iteration + ($kategoriList->currentPage() - 1) * $kategoriList->perPage() }}
                            </td>
                            <td class="px-4 py-3 align-middle font-medium text-gray-900">
                                {{ $item->nama }}
                            </td>
                            <td class="px-4 py-3 align-middle text-center">
                                <div class="flex items-center justify-center gap-1.5">
                                    <a href="{{ route('kategori-pendapatan.edit', $item->id) }}"
                                        class="w-7 h-7 rounded-full bg-white border border-gray-200 flex items-center justify-center text-gray-500 hover:bg-indigo-50 hover:border-indigo-300 hover:text-indigo-600 transition-colors shadow-sm"
                                        title="Edit">
                                        <span class="material-symbols-outlined text-[15px]">edit</span>
                                    </a>
                                    <button type="button"
                                        @click="$dispatch('open-delete-modal', {
                                            action: '{{ route('kategori-pendapatan.destroy', $item->id) }}',
                                            message: 'Yakin ingin menghapus kategori {{ addslashes($item->nama) }}?'
                                        })"
                                        class="w-7 h-7 rounded-full bg-white border border-gray-200 flex items-center justify-center text-gray-500 hover:bg-red-50 hover:border-red-300 hover:text-red-600 transition-colors shadow-sm"
                                        title="Hapus">
                                        <span class="material-symbols-outlined text-[15px]">delete</span>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="px-4 py-12 text-center">
                                <div class="flex flex-col items-center justify-center gap-2">
                                    <div class="w-12 h-12 rounded-2xl bg-gray-50 flex items-center justify-center border border-gray-100 mb-1">
                                        <span class="material-symbols-outlined text-gray-400 text-2xl">category</span>
                                    </div>
                                    <h3 class="text-sm font-semibold text-gray-800">Belum ada kategori</h3>
                                    <p class="text-xs text-gray-500 max-w-62.5">
                                        Silakan tambah kategori pendapatan pertama Anda.
                                    </p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Laravel Dynamic Pagination --}}
        <x-pagination :paginator="$kategoriList" />
    </div>

    <x-modal-delete />
</x-app-layout>
