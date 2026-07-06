<x-app-layout>
    <x-slot:title>Upload Standar Tarif</x-slot>

    {{-- Header --}}
    <div class="border-b border-gray-100 pb-6">
        <a href="{{ route('standar-tarif.index') }}"
            class="inline-flex items-center gap-1 text-xs text-gray-400 hover:text-primary transition mb-3">
            <span class="material-symbols-outlined text-[16px]">arrow_back</span>
            Kembali ke Standar Tarif
        </a>

        <h2 class="text-lg font-semibold text-gray-900">Upload Standar Tarif Baru</h2>
        <p class="text-sm text-gray-500 mt-1">
            Upload dokumen standar tarif dalam format PDF (maks. 10 MB)
        </p>
    </div>

    {{-- Error Summary --}}
    @if ($errors->any())
        <div class="mt-6 px-4 py-3 rounded-xl bg-red-50 border border-red-100 text-red-600 text-xs">
            <p class="font-medium mb-1">Periksa kembali isian Anda:</p>
            <ul class="list-disc pl-4 space-y-0.5">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mt-6">

        {{-- Form --}}
        <div class="lg:col-span-2">
            <form method="POST" action="{{ route('standar-tarif.store') }}"
                enctype="multipart/form-data"
                class="bg-white rounded-3xl border border-gray-100 shadow-sm p-6 space-y-5"
                x-data="pdfUploader()">
                @csrf

                {{-- Kode & Nama --}}
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-5">

                    {{-- Kode --}}
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-1.5">
                            Kode
                            <span class="text-gray-400 font-normal">(opsional)</span>
                        </label>
                        <input type="text" name="kode" value="{{ old('kode') }}"
                            placeholder="Contoh: ST-001"
                            maxlength="20"
                            class="w-full px-4 py-2.5 text-sm rounded-xl border border-gray-200 focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none @error('kode') border-red-300 @enderror">
                        @error('kode')
                            <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Nama --}}
                    <div class="sm:col-span-2">
                        <label class="block text-xs font-semibold text-gray-700 mb-1.5">
                            Nama Dokumen <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="nama" value="{{ old('nama') }}"
                            placeholder="Contoh: Standar Tarif Honorarium 2024"
                            maxlength="200"
                            class="w-full px-4 py-2.5 text-sm rounded-xl border border-gray-200 focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none @error('nama') border-red-300 @enderror">
                        @error('nama')
                            <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                </div>

                {{-- Deskripsi --}}
                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1.5">
                        Deskripsi
                        <span class="text-gray-400 font-normal">(opsional)</span>
                    </label>
                    <textarea name="deskripsi" rows="3"
                        placeholder="Keterangan singkat mengenai dokumen ini..."
                        class="w-full px-4 py-2.5 text-sm rounded-xl border border-gray-200 focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none resize-none @error('deskripsi') border-red-300 @enderror">{{ old('deskripsi') }}</textarea>
                    @error('deskripsi')
                        <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Upload PDF --}}
                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1.5">
                        File PDF <span class="text-red-500">*</span>
                    </label>

                    {{-- Drop Zone --}}
                    <div
                        class="relative border-2 border-dashed rounded-2xl transition-all duration-200 cursor-pointer"
                        :class="isDragging ? 'border-primary bg-primary/5' : (fileName ? 'border-emerald-300 bg-emerald-50' : 'border-gray-200 bg-gray-50 hover:border-primary/50 hover:bg-primary/5')"
                        @dragover.prevent="isDragging = true"
                        @dragleave.prevent="isDragging = false"
                        @drop.prevent="handleDrop($event)"
                        @click="$refs.fileInput.click()">

                        <input type="file" name="file" accept=".pdf,application/pdf"
                            x-ref="fileInput"
                            class="hidden"
                            @change="handleFileChange($event)">

                        {{-- Idle state --}}
                        <div x-show="!fileName" class="flex flex-col items-center justify-center py-10 px-6 text-center">
                            <div class="w-14 h-14 rounded-2xl bg-red-50 border border-red-100 flex items-center justify-center mb-3">
                                <span class="material-symbols-outlined text-red-400 text-[28px]">picture_as_pdf</span>
                            </div>
                            <p class="text-sm font-medium text-gray-700">Klik atau seret file PDF ke sini</p>
                            <p class="text-xs text-gray-400 mt-1">Format: PDF · Maksimal 10 MB</p>
                        </div>

                        {{-- File selected state --}}
                        <div x-show="fileName" class="flex items-center gap-4 py-5 px-6" style="display:none">
                            <div class="w-12 h-12 rounded-xl bg-red-100 flex items-center justify-center shrink-0">
                                <span class="material-symbols-outlined text-red-600 text-[24px]">picture_as_pdf</span>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-semibold text-gray-800 truncate" x-text="fileName"></p>
                                <p class="text-xs text-gray-400 mt-0.5" x-text="fileSize"></p>
                            </div>
                            <button type="button"
                                @click.stop="clearFile()"
                                class="w-8 h-8 rounded-lg border border-gray-200 flex items-center justify-center text-gray-400 hover:text-red-500 hover:border-red-200 transition">
                                <span class="material-symbols-outlined text-[18px]">close</span>
                            </button>
                        </div>

                    </div>

                    @error('file')
                        <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Actions --}}
                <div class="flex justify-end gap-3 pt-2 border-t border-gray-100">
                    <a href="{{ route('standar-tarif.index') }}"
                        class="px-6 py-2.5 rounded-full border border-gray-200 text-gray-600 text-sm font-medium hover:bg-gray-100 hover:border-gray-300 transition-all duration-200 active:scale-95">
                        Batal
                    </a>
                    <button type="submit"
                        class="inline-flex items-center gap-2 bg-gold text-gray-900 px-6 py-2.5 rounded-full text-sm font-semibold shadow hover:bg-gold-dark hover:shadow-md transition-all duration-200 active:scale-95">
                        <span class="material-symbols-outlined text-[18px]">upload_file</span>
                        Upload Dokumen
                    </button>
                </div>

            </form>
        </div>

        {{-- Side Info --}}
        <div class="lg:col-span-1 space-y-4">

            <div class="bg-primary/5 border border-primary/10 rounded-3xl p-5 space-y-3">
                <div class="flex items-center gap-2 text-primary">
                    <span class="material-symbols-outlined text-[18px]">info</span>
                    <h3 class="text-sm font-semibold">Ketentuan Upload</h3>
                </div>
                <ul class="text-xs text-gray-600 space-y-2 list-disc pl-4">
                    <li>Hanya file berformat <strong>PDF</strong> yang diterima.</li>
                    <li>Ukuran file maksimal <strong>10 MB</strong>.</li>
                    <li><strong>Kode</strong> bersifat opsional, gunakan kode yang konsisten untuk memudahkan pencarian.</li>
                    <li><strong>Nama</strong> dokumen harus jelas dan deskriptif.</li>
                    <li>Anda dapat mengganti file PDF kapan saja melalui menu <strong>Edit</strong>.</li>
                </ul>
            </div>

            <div class="bg-white border border-gray-100 rounded-3xl p-5 space-y-3 shadow-sm">
                <div class="flex items-center gap-2 text-gray-600">
                    <span class="material-symbols-outlined text-[18px]">folder_open</span>
                    <h3 class="text-sm font-semibold">Contoh Dokumen</h3>
                </div>
                <ul class="text-xs space-y-1.5 text-gray-500">
                    <li class="flex items-start gap-2">
                        <span class="material-symbols-outlined text-[13px] text-red-400 mt-0.5 shrink-0">picture_as_pdf</span>
                        Standar Tarif Honorarium
                    </li>
                    <li class="flex items-start gap-2">
                        <span class="material-symbols-outlined text-[13px] text-red-400 mt-0.5 shrink-0">picture_as_pdf</span>
                        Standar Biaya Perjalanan Dinas
                    </li>
                    <li class="flex items-start gap-2">
                        <span class="material-symbols-outlined text-[13px] text-red-400 mt-0.5 shrink-0">picture_as_pdf</span>
                        Standar Harga Satuan Barang
                    </li>
                </ul>
            </div>

        </div>

    </div>

    <script>
        function pdfUploader() {
            return {
                isDragging: false,
                fileName: null,
                fileSize: null,

                handleFileChange(event) {
                    const file = event.target.files[0];
                    if (file) this.setFile(file);
                },

                handleDrop(event) {
                    this.isDragging = false;
                    const file = event.dataTransfer.files[0];
                    if (file && file.type === 'application/pdf') {
                        this.$refs.fileInput.files = event.dataTransfer.files;
                        this.setFile(file);
                    }
                },

                setFile(file) {
                    this.fileName = file.name;
                    const mb = (file.size / 1024 / 1024).toFixed(2);
                    this.fileSize = mb + ' MB';
                },

                clearFile() {
                    this.fileName = null;
                    this.fileSize = null;
                    this.$refs.fileInput.value = '';
                }
            }
        }
    </script>

</x-app-layout>
