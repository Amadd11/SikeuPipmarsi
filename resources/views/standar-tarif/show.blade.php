<x-app-layout>
    <x-slot:title>Detail Standar Tarif</x-slot>

    {{-- Header --}}
    <div class="border-b border-gray-100 pb-6">
        <a href="{{ route('standar-tarif.index') }}"
            class="inline-flex items-center gap-1 text-xs text-gray-400 hover:text-primary transition mb-3">
            <span class="material-symbols-outlined text-[16px]">arrow_back</span>
            Kembali ke Standar Tarif
        </a>

        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
            <div>
                <h2 class="text-lg font-semibold text-gray-900">{{ $standarTarif->nama }}</h2>
                @if ($standarTarif->kode)
                    <span class="inline-flex items-center px-2 py-0.5 rounded-md bg-indigo-50 text-indigo-700 text-xs font-bold border border-indigo-100/50 mt-1">
                        {{ $standarTarif->kode }}
                    </span>
                @endif
            </div>

            <div class="flex items-center gap-2">
                <a href="{{ route('standar-tarif.edit', $standarTarif->id) }}"
                    class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl border border-gray-200 text-gray-600 text-xs font-medium hover:bg-gray-50 hover:border-primary hover:text-primary transition">
                    <span class="material-symbols-outlined text-[16px]">edit</span>
                    Edit
                </a>
                <button
                    @click="$dispatch('open-delete-modal', {
                        action: '{{ route('standar-tarif.destroy', $standarTarif->id) }}',
                        message: 'Yakin ingin menghapus standar tarif \"{{ addslashes($standarTarif->nama) }}\"? File PDF juga akan ikut terhapus.'
                    })"
                    class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl border border-red-200 text-red-500 text-xs font-medium hover:bg-red-50 transition">
                    <span class="material-symbols-outlined text-[16px]">delete</span>
                    Hapus
                </button>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mt-6">

        {{-- PDF Viewer / Preview --}}
        <div class="lg:col-span-2">
            <div class="bg-white rounded-3xl border border-gray-100 shadow-sm overflow-hidden">

                {{-- PDF Header --}}
                <div class="px-5 py-4 border-b border-gray-100 flex items-center justify-between">
                    <div class="flex items-center gap-2.5">
                        <div class="w-8 h-8 rounded-lg bg-red-100 flex items-center justify-center">
                            <span class="material-symbols-outlined text-red-500 text-[18px]">picture_as_pdf</span>
                        </div>
                        <div>
                            <p class="text-xs font-semibold text-gray-800">{{ basename($standarTarif->file) }}</p>
                            <p class="text-[10px] text-gray-400">Dokumen PDF</p>
                        </div>
                    </div>
                    <a href="{{ Storage::url($standarTarif->file) }}" target="_blank"
                        class="inline-flex items-center gap-1.5 px-3.5 py-2 bg-primary text-white text-xs font-medium rounded-xl hover:bg-primary-dark transition">
                        <span class="material-symbols-outlined text-[15px]">open_in_new</span>
                        Buka di Tab Baru
                    </a>
                </div>

                {{-- PDF Embed --}}
                <div class="w-full" style="height: 600px;">
                    <iframe
                        src="{{ Storage::url($standarTarif->file) }}"
                        class="w-full h-full border-0"
                        title="{{ $standarTarif->nama }}">
                        <div class="flex flex-col items-center justify-center h-full py-20 text-center">
                            <span class="material-symbols-outlined text-5xl text-gray-300 mb-3">picture_as_pdf</span>
                            <p class="text-sm text-gray-500 mb-3">Browser Anda tidak mendukung preview PDF.</p>
                            <a href="{{ Storage::url($standarTarif->file) }}" target="_blank"
                                class="inline-flex items-center gap-1.5 bg-primary text-white px-4 py-2 rounded-xl text-xs font-medium hover:bg-primary-dark transition">
                                <span class="material-symbols-outlined text-[16px]">download</span>
                                Unduh PDF
                            </a>
                        </div>
                    </iframe>
                </div>

                {{-- Download Footer --}}
                <div class="px-5 py-3 border-t border-gray-100 bg-gray-50/50 flex items-center justify-between">
                    <p class="text-xs text-gray-400">Jika PDF tidak tampil, klik tombol di bawah.</p>
                    <a href="{{ Storage::url($standarTarif->file) }}" download
                        class="inline-flex items-center gap-1.5 px-3.5 py-2 bg-gray-100 text-gray-700 text-xs font-medium rounded-xl hover:bg-gray-200 transition">
                        <span class="material-symbols-outlined text-[15px]">download</span>
                        Unduh
                    </a>
                </div>

            </div>
        </div>

        {{-- Sidebar Info --}}
        <div class="lg:col-span-1 space-y-4">

            {{-- Detail --}}
            <div class="bg-white border border-gray-100 rounded-3xl p-5 space-y-4 shadow-sm">
                <h3 class="text-sm font-semibold text-gray-800">Informasi Dokumen</h3>

                <div class="space-y-3 text-xs">
                    @if ($standarTarif->kode)
                        <div>
                            <p class="text-gray-400 font-medium uppercase tracking-wider text-[10px] mb-0.5">Kode</p>
                            <p class="font-semibold text-indigo-700 bg-indigo-50 px-2 py-0.5 rounded-md inline-block">{{ $standarTarif->kode }}</p>
                        </div>
                    @endif

                    <div>
                        <p class="text-gray-400 font-medium uppercase tracking-wider text-[10px] mb-0.5">Nama</p>
                        <p class="text-gray-800 font-medium">{{ $standarTarif->nama }}</p>
                    </div>

                    @if ($standarTarif->deskripsi)
                        <div>
                            <p class="text-gray-400 font-medium uppercase tracking-wider text-[10px] mb-0.5">Deskripsi</p>
                            <p class="text-gray-600 leading-relaxed">{{ $standarTarif->deskripsi }}</p>
                        </div>
                    @endif

                    <div class="pt-2 border-t border-gray-100 space-y-2">
                        <div class="flex justify-between">
                            <span class="text-gray-400">Diunggah</span>
                            <span class="font-medium text-gray-700">{{ $standarTarif->created_at->format('d M Y') }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-400">Diperbarui</span>
                            <span class="font-medium text-gray-700">{{ $standarTarif->updated_at->format('d M Y') }}</span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Quick Actions --}}
            <div class="bg-white border border-gray-100 rounded-3xl p-5 space-y-2 shadow-sm">
                <h3 class="text-sm font-semibold text-gray-800 mb-3">Aksi Cepat</h3>

                <a href="{{ Storage::url($standarTarif->file) }}" target="_blank"
                    class="flex items-center gap-3 px-3 py-2.5 rounded-xl border border-gray-100 hover:border-primary/30 hover:bg-primary/5 transition group">
                    <span class="material-symbols-outlined text-[18px] text-gray-400 group-hover:text-primary transition">open_in_new</span>
                    <span class="text-xs font-medium text-gray-700 group-hover:text-primary transition">Buka PDF di Tab Baru</span>
                </a>

                <a href="{{ Storage::url($standarTarif->file) }}" download
                    class="flex items-center gap-3 px-3 py-2.5 rounded-xl border border-gray-100 hover:border-emerald-300 hover:bg-emerald-50 transition group">
                    <span class="material-symbols-outlined text-[18px] text-gray-400 group-hover:text-emerald-600 transition">download</span>
                    <span class="text-xs font-medium text-gray-700 group-hover:text-emerald-700 transition">Unduh PDF</span>
                </a>

                <a href="{{ route('standar-tarif.edit', $standarTarif->id) }}"
                    class="flex items-center gap-3 px-3 py-2.5 rounded-xl border border-gray-100 hover:border-amber-300 hover:bg-amber-50 transition group">
                    <span class="material-symbols-outlined text-[18px] text-gray-400 group-hover:text-amber-600 transition">edit</span>
                    <span class="text-xs font-medium text-gray-700 group-hover:text-amber-700 transition">Edit Dokumen</span>
                </a>
            </div>

        </div>

    </div>

    <x-modal-delete />

</x-app-layout>
