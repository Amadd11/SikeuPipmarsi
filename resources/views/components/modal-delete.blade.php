<div x-show="open" x-cloak class="fixed inset-0 z-50 flex items-center justify-center bg-black/50">
    <div @click.away="open = false" class="bg-white rounded-2xl shadow-xl w-full max-w-md p-6">
        <div class="flex items-center gap-3 mb-4">
            <div class="w-12 h-12 rounded-full bg-red-100 flex items-center justify-center">
                <span class="material-symbols-outlined text-red-600">
                    warning
                </span>
            </div>

            <div>
                <h3 class="font-semibold text-gray-900">
                    {{ $title ?? 'Konfirmasi Hapus' }}
                </h3>

                <p class="text-sm text-gray-500">
                    {{ $subtitle ?? 'Data yang dihapus tidak dapat dikembalikan.' }}
                </p>
            </div>
        </div>

        <p class="text-sm text-gray-600 mb-6">
            {{ $message ?? 'Apakah Anda yakin?' }}
        </p>

        <div class="flex justify-end gap-3">
            <button @click="open = false" type="button" class="px-4 py-2 rounded-lg border border-gray-300">
                Batal
            </button>

            <form action="{{ $action }}" method="POST">
                @csrf
                @method('DELETE')

                <button type="submit" class="px-4 py-2 rounded-lg bg-red-600 text-white">
                    Hapus
                </button>
            </form>
        </div>
    </div>
</div>
