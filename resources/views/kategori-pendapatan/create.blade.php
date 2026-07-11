<x-app-layout>
    <x-slot:title>Tambah Kategori Pendapatan</x-slot>

    <div class="border-b border-gray-100 pb-5 mb-5">
        <div class="flex items-center gap-3">
            <a href="{{ route('kategori-pendapatan.index') }}"
                class="w-8 h-8 rounded-full flex items-center justify-center bg-gray-50 text-gray-500 hover:bg-gray-100 hover:text-gray-900 transition-colors">
                <span class="material-symbols-outlined text-[18px]">arrow_back</span>
            </a>
            <div>
                <h2 class="text-lg font-semibold text-gray-900">Tambah Kategori Pendapatan</h2>
                <p class="text-sm text-gray-500 mt-0.5">
                    Tambahkan data kategori pendapatan baru
                </p>
            </div>
        </div>
    </div>

    @if ($errors->any())
        <div class="mb-5 px-4 py-3 rounded-xl bg-red-50 border border-red-100 text-red-600 text-xs">
            <p class="font-medium mb-1">Periksa kembali isian Anda:</p>
            <ul class="list-disc pl-4 space-y-0.5">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="bg-white rounded-3xl border border-gray-100 shadow-sm overflow-hidden max-w-2xl">
        <form method="POST" action="{{ route('kategori-pendapatan.store') }}" class="p-6">
            @csrf

            <div class="mb-6">
                <x-input-label for="nama" value="Nama Kategori" class="mb-1.5" />
                <x-text-input id="nama" name="nama" type="text" class="block w-full"
                    :value="old('nama')" required autofocus autocomplete="off" placeholder="Contoh: Hibah, APBN, dll..." />
                <x-input-error class="mt-2" :messages="$errors->get('nama')" />
            </div>

            <div class="flex items-center justify-end gap-3 pt-4 border-t border-gray-50">
                <a href="{{ route('kategori-pendapatan.index') }}"
                    class="px-5 py-2.5 rounded-full text-sm font-medium text-gray-600 hover:bg-gray-50 transition-colors">
                    Batal
                </a>
                <button type="submit"
                    class="inline-flex items-center gap-2 bg-gold text-gray-900 px-6 py-2.5 rounded-full text-sm font-semibold shadow hover:bg-gold-dark hover:shadow-md transition-all active:scale-95">
                    <span class="material-symbols-outlined text-[18px]">save</span>
                    Simpan
                </button>
            </div>
        </form>
    </div>
</x-app-layout>
