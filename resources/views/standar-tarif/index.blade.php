<x-app-layout>
    <x-slot:title>Standar Tarif</x-slot>

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
    <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4 mb-5">
        <div>
            <p class="text-xs text-gray-500 mt-1">
                Kelola dokumen standar tarif dalam format PDF
            </p>
        </div>

        <a href="{{ route('standar-tarif.create') }}"
            class="inline-flex items-center gap-2 bg-gold text-gray-900 px-4 py-2 rounded-full text-xs font-semibold shadow hover:bg-gold-dark hover:shadow-md transition-all duration-200 active:scale-95">
            <span class="material-symbols-outlined text-[16px]">upload_file</span>
            Upload Standar Tarif
        </a>
    </div>

    {{-- Search --}}
    <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-4 mb-5">
        <form method="GET" action="{{ route('standar-tarif.index') }}" class="flex items-end gap-3">
            <div class="flex-1">
                <label class="block text-[10px] font-semibold text-gray-500 uppercase tracking-wider mb-1.5">Cari Dokumen</label>
                <input type="text" name="search" value="{{ $search ?? '' }}"
                    placeholder="Kode, nama, atau deskripsi..."
                    class="w-full px-3 py-2 text-xs rounded-xl border border-gray-200 focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none">
            </div>
            <div class="flex gap-2">
                <button type="submit"
                    class="inline-flex items-center gap-1.5 bg-gold text-gray-900 px-4 py-2 rounded-full text-xs font-semibold shadow-sm hover:bg-gold-dark hover:shadow transition-all duration-200 active:scale-95">
                    <span class="material-symbols-outlined text-[16px]" style="font-variation-settings: 'FILL' 1">search</span>
                    Cari
                </button>
                @if ($search)
                    <a href="{{ route('standar-tarif.index') }}"
                        class="inline-flex items-center gap-1.5 bg-white text-gray-500 px-4 py-2 rounded-full text-xs font-medium border border-gray-200 hover:bg-gray-50 hover:border-gray-300 transition-all duration-200 active:scale-95">
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
        <div class="p-5 border-b border-gray-100">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="font-semibold text-gray-900 text-sm">Daftar Standar Tarif</h2>
                    <p class="text-xs text-gray-500 mt-0.5">
                        Menampilkan {{ $tarifList->total() }} dokumen standar tarif.
                    </p>
                </div>
                <span class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-red-50 border border-red-100 text-red-600 text-[10px] font-semibold rounded-lg">
                    <span class="material-symbols-outlined text-[13px]">picture_as_pdf</span>
                    Format PDF
                </span>
            </div>
        </div>

        {{-- Table --}}
        <div class="overflow-x-auto">
            <table class="w-full text-xs">
                <thead>
                    <tr class="bg-gray-50 text-[10px] uppercase tracking-widest text-gray-500 font-semibold border-b border-gray-100">
                        <th class="px-4 py-2.5 text-center w-10">#</th>
                        <th class="px-4 py-2.5 text-left w-24">Kode</th>
                        <th class="px-4 py-2.5 text-left">Nama Dokumen</th>
                        <th class="px-4 py-2.5 text-left">Deskripsi</th>
                        <th class="px-4 py-2.5 text-center">File PDF</th>
                        <th class="px-4 py-2.5 text-center">Diunggah</th>
                        <th class="px-4 py-2.5 text-center">Aksi</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-gray-100 text-gray-700">

                    @forelse ($tarifList as $item)
                        <tr class="hover:bg-gray-50/70 transition group">

                            {{-- No --}}
                            <td class="px-4 py-3 text-center text-gray-400 font-medium align-middle">
                                {{ $loop->iteration + ($tarifList->currentPage() - 1) * $tarifList->perPage() }}
                            </td>

                            {{-- Kode --}}
                            <td class="px-4 py-3 align-middle">
                                @if ($item->kode)
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-md bg-indigo-50 text-indigo-700 text-[10px] font-bold border border-indigo-100/50 tracking-wide">
                                        {{ $item->kode }}
                                    </span>
                                @else
                                    <span class="text-gray-300">—</span>
                                @endif
                            </td>

                            {{-- Nama --}}
                            <td class="px-4 py-3 align-middle max-w-[200px]">
                                <span class="font-medium text-gray-900 leading-tight block">{{ $item->nama }}</span>
                            </td>

                            {{-- Deskripsi --}}
                            <td class="px-4 py-3 align-middle max-w-xs text-gray-500">
                                {{ $item->deskripsi ? Str::limit($item->deskripsi, 70) : '—' }}
                            </td>

                            {{-- File PDF --}}
                            <td class="px-4 py-3 text-center align-middle">
                                <a href="{{ Storage::url($item->file) }}" target="_blank"
                                    class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-red-50 border border-red-100 text-red-600 text-[10px] font-semibold rounded-lg hover:bg-red-100 transition group">
                                    <span class="material-symbols-outlined text-[14px]">picture_as_pdf</span>
                                    Buka PDF
                                </a>
                            </td>

                            {{-- Diunggah --}}
                            <td class="px-4 py-3 text-center align-middle text-gray-500 whitespace-nowrap">
                                {{ $item->created_at->format('d M Y') }}
                            </td>

                            {{-- Aksi --}}
                            <td class="px-4 py-3 align-middle">
                                <div class="flex items-center justify-center gap-1.5">
                                    <a href="{{ Storage::url($item->file) }}" target="_blank"
                                        class="w-8 h-8 rounded-full bg-gray-50 border border-gray-200 flex items-center justify-center text-gray-500 hover:bg-indigo-50 hover:border-indigo-300 hover:text-indigo-600 transition-all duration-200"
                                        title="Buka / Unduh Dokumen">
                                        <span class="material-symbols-outlined text-[16px]">visibility</span>
                                    </a>
                                    <a href="{{ route('standar-tarif.edit', $item->id) }}"
                                        class="w-8 h-8 rounded-full bg-gray-50 border border-gray-200 flex items-center justify-center text-gray-500 hover:bg-indigo-50 hover:border-indigo-300 hover:text-indigo-600 transition-all duration-200"
                                        title="Edit">
                                        <span class="material-symbols-outlined text-[16px]">edit</span>
                                    </a>
                                    <button
                                        @click="$dispatch('open-delete-modal', {
                                            action: '{{ route('standar-tarif.destroy', $item->id) }}',
                                            message: 'Yakin ingin menghapus standar tarif {{ addslashes($item->nama) }}? File PDF juga akan ikut terhapus.'
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
                                <div class="flex flex-col items-center gap-2">
                                    <span class="material-symbols-outlined text-4xl text-gray-300">description</span>
                                    <h3 class="font-medium text-gray-700 text-sm">Belum ada standar tarif</h3>
                                    <p class="text-xs text-gray-500">Upload dokumen standar tarif pertama dalam format PDF.</p>
                                    <a href="{{ route('standar-tarif.create') }}"
                                        class="mt-3 inline-flex items-center gap-2 bg-gold text-gray-900 px-5 py-2 rounded-full text-xs font-semibold shadow hover:bg-gold-dark hover:shadow-md transition-all duration-200 active:scale-95">
                                        <span class="material-symbols-outlined text-[16px]">upload_file</span>
                                        Upload Sekarang
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforelse

                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @if ($tarifList->hasPages())
            <div class="px-5 py-3 border-t border-gray-100 text-xs bg-gray-50/30">
                {{ $tarifList->appends(request()->query())->links() }}
            </div>
        @endif

    </div>

    <x-modal-delete />

</x-app-layout>
