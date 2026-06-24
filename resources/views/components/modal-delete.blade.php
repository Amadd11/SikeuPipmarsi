<div x-data="{
    open: false,
    action: '',
    title: 'Konfirmasi Hapus',
    subtitle: 'Data yang dihapus tidak dapat dikembalikan.',
    message: 'Apakah Anda yakin?',

    openModal(detail) {
        this.open = true;
        this.action = detail.action ?? '';

        this.title = detail.title ??
            'Konfirmasi Hapus';

        this.subtitle = detail.subtitle ??
            'Data yang dihapus tidak dapat dikembalikan.';

        this.message = detail.message ??
            'Apakah Anda yakin?';
    },

    closeModal() {
        this.open = false;

        setTimeout(() => {
            this.action = '';
            this.title = 'Konfirmasi Hapus';
            this.subtitle = 'Data yang dihapus tidak dapat dikembalikan.';
            this.message = 'Apakah Anda yakin?';
        }, 200);
    }
}" @open-delete-modal.window="openModal($event.detail)" @keydown.escape.window="closeModal()"
    x-show="open" x-cloak x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0"
    x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-150"
    x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
    class="fixed inset-0 z-9999 flex items-center justify-center bg-black/50 backdrop-blur-sm p-4">

    {{-- Backdrop --}}
    <div class="absolute inset-0" @click="closeModal()"></div>

    {{-- Modal --}}
    <div x-show="open" x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
        x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100 scale-100"
        x-transition:leave-end="opacity-0 scale-95" class="relative bg-white rounded-2xl shadow-xl w-full max-w-md p-6">

        <div class="flex items-center gap-3 mb-4">
            <div class="w-12 h-12 rounded-full bg-red-100 flex items-center justify-center shrink-0">
                <span class="material-symbols-outlined text-red-600">
                    warning
                </span>
            </div>

            <div>
                <h3 class="font-semibold text-gray-900" x-text="title"></h3>

                <p class="text-sm text-gray-500" x-text="subtitle"></p>
            </div>
        </div>

        <p class="text-sm text-gray-600 mb-6" x-text="message"></p>

        <div class="flex justify-end gap-3">
            <button type="button" @click="closeModal()"
                class="px-4 py-2 text-sm font-medium rounded-xl border border-gray-300 text-gray-700 hover:bg-gray-50 transition">
                Batal
            </button>

            <form :action="action" method="POST" @submit="closeModal()">
                @csrf
                @method('DELETE')

                <button type="submit"
                    class="px-4 py-2 text-sm font-medium rounded-xl bg-red-600 hover:bg-red-700 text-white transition shadow-sm">
                    Hapus
                </button>
            </form>
        </div>
    </div>
</div>
