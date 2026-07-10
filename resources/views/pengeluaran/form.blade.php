                {{-- Tahun Anggaran --}}
                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1.5">
                        Tahun Anggaran <span class="text-red-500">*</span>
                    </label>
                    <select name="tahun_anggaran_id"
                        class="w-full px-4 py-2.5 text-sm rounded-xl border border-gray-200 focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none bg-white @error('tahun_anggaran_id') @enderror">
                        <option value="">Pilih tahun anggaran</option>
                        @foreach ($tahunAnggaranList as $tahun)
                            <option value="{{ $tahun->id }}" @selected(old('tahun_anggaran_id', $pengeluaran->tahun_anggaran_id ?? '') == $tahun->id || (!isset($pengeluaran) && $tahun->is_aktif))>
                                TA {{ $tahun->tahun }}{{ $tahun->is_aktif ? ' (Aktif)' : '' }}
                            </option>
                        @endforeach
                    </select>
                    @error('tahun_anggaran_id')
                        <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Bidang Kerja --}}
                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1.5">
                        Bidang Kerja <span class="text-red-500">*</span>
                    </label>
                    <select name="bidang_kerja_id"
                        class="w-full px-4 py-2.5 text-sm rounded-xl border border-gray-200 focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none bg-white @error('bidang_kerja_id') @enderror">
                        <option value="">Pilih bidang kerja</option>
                        @foreach ($bidangKerjaList as $bidang)
                            <option value="{{ $bidang->id }}" @selected(old('bidang_kerja_id', $pengeluaran->bidang_kerja_id ?? '') == $bidang->id)>
                                {{ $bidang->nama }}
                            </option>
                        @endforeach
                    </select>
                    @error('bidang_kerja_id')
                        <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Nama Kegiatan --}}
                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1.5">
                        Nama Kegiatan / Pos Anggaran <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="nama_kegiatan" value="{{ old('nama_kegiatan', $pengeluaran->nama_kegiatan ?? '') }}"
                        placeholder="Contoh: Rapat Pengurus Rutin (12 kali/tahun)"
                        class="w-full px-4 py-2.5 text-sm rounded-xl border border-gray-200 focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none @error('nama_kegiatan') @enderror">
                    @error('nama_kegiatan')
                        <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Kategori & Indikator Mutu --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">

                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-1.5">
                            Kategori Pengeluaran <span class="text-red-500">*</span>
                        </label>
                        <select name="kategori_pengeluaran_id"
                            class="w-full px-4 py-2.5 text-sm rounded-xl border border-gray-200 focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none bg-white @error('kategori_pengeluaran_id') @enderror">
                            <option value="">Pilih kategori</option>
                            @foreach ($kategoriList as $kategori)
                                <option value="{{ $kategori->id }}" @selected(old('kategori_pengeluaran_id', $pengeluaran->kategori_pengeluaran_id ?? '') == $kategori->id)>
                                    {{ $kategori->nama }}
                                </option>
                            @endforeach
                        </select>
                        @error('kategori_pengeluaran_id')
                            <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-1.5">
                            Indikator Mutu
                            <span class="text-gray-400 font-normal">(opsional)</span>
                        </label>
                        <select name="indikator_mutu_id" x-data x-init="new TomSelect($el, { maxOptions: null })"
                            class="w-full px-4 py-2.5 text-sm rounded-xl border border-gray-200 focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none bg-white @error('indikator_mutu_id') @enderror">
                            <option value="">Tidak terkait indikator</option>
                            @foreach ($indikatorList as $indikator)
                                <option value="{{ $indikator->id }}" @selected(old('indikator_mutu_id', $pengeluaran->indikator_mutu_id ?? '') == $indikator->id)>
                                    {{ $indikator->kode }} — {{ Str::limit($indikator->nama, 50) }}
                                </option>
                            @endforeach
                        </select>
                        @error('indikator_mutu_id')
                            <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                </div>

                {{-- Detail RK Belanja --}}
                <div x-data="rincianForm({ details: {{ json_encode($initialDetails) }} })" class="space-y-4">
                    <div class="flex items-center justify-between">
                        <label class="block text-sm font-semibold text-gray-900">
                            Rincian Pengeluaran <span class="text-red-500">*</span>
                        </label>
                        <button type="button" @click="addDetail" class="inline-flex items-center gap-1 text-xs font-medium text-primary hover:text-primary-dark transition">
                            <span class="material-symbols-outlined text-[16px]">add</span> Tambah Baris
                        </button>
                    </div>

                    @error('details')
                        <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                    @enderror

                    <div class="space-y-4">
                        <template x-for="(item, index) in details" :key="item.id">
                            <div class="grid grid-cols-12 gap-3 items-start relative p-4 bg-gray-50 border border-gray-100 rounded-2xl">
                                {{-- Uraian --}}
                                <div class="col-span-12 sm:col-span-4">
                                    <label class="block text-xs font-medium text-gray-700 mb-1">Uraian</label>
                                    <input type="text" x-model="item.uraian" :name="`details[${index}][uraian]`" placeholder="Misal: Sewa Ruangan" required
                                        class="w-full px-3 py-2 text-sm rounded-xl border border-gray-200 focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none">
                                </div>
                                {{-- Satuan --}}
                                <div class="col-span-12 sm:col-span-2">
                                    <label class="block text-xs font-medium text-gray-700 mb-1">Satuan</label>
                                    <input type="text" x-model="item.satuan" :name="`details[${index}][satuan]`" placeholder="Misal: Hari" required
                                        class="w-full px-3 py-2 text-sm rounded-xl border border-gray-200 focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none">
                                </div>
                                {{-- Harga --}}
                                <div class="col-span-12 sm:col-span-3">
                                    <label class="block text-xs font-medium text-gray-700 mb-1">Harga</label>
                                    <div class="relative">
                                        <span class="absolute left-3 top-1/2 -translate-y-1/2 text-xs text-gray-400 font-medium">Rp</span>
                                        <input type="text" inputmode="numeric" x-model="item.hargaDisplay" @input="formatHarga(item, $event)" placeholder="0" required
                                            class="w-full pl-8 pr-3 py-2 text-sm rounded-xl border border-gray-200 focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none">
                                    </div>
                                    <input type="hidden" :name="`details[${index}][harga]`" :value="item.hargaRaw">
                                </div>
                                {{-- Kuantitas --}}
                                <div class="col-span-10 sm:col-span-2">
                                    <label class="block text-xs font-medium text-gray-700 mb-1">Qty</label>
                                    <input type="number" min="1" x-model="item.kuantitas" :name="`details[${index}][kuantitas]`" placeholder="1" required
                                        class="w-full px-3 py-2 text-sm rounded-xl border border-gray-200 focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none">
                                </div>
                                {{-- Delete Button --}}
                                <div class="col-span-2 sm:col-span-1 flex justify-end mt-6">
                                    <button type="button" @click="removeDetail(item.id)" x-show="details.length > 1" class="p-2 text-gray-400 hover:text-red-500 hover:bg-red-50 rounded-xl transition">
                                        <span class="material-symbols-outlined text-[18px]">delete</span>
                                    </button>
                                </div>
                            </div>
                        </template>
                    </div>

                    {{-- Total Anggaran --}}
                    <div class="pt-2 border-t border-gray-100 flex justify-end">
                        <div class="w-full sm:w-1/2">
                            <label class="block text-xs font-semibold text-gray-700 mb-1.5 text-right">
                                Grand Total Anggaran <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <span class="absolute left-4 top-1/2 -translate-y-1/2 text-sm text-gray-400 font-medium">Rp</span>
                                <input type="text" readonly :value="grandTotalDisplay"
                                    class="w-full pl-10 pr-4 py-2.5 text-sm rounded-xl border border-gray-200 bg-gray-100 text-gray-800 font-bold outline-none cursor-not-allowed text-right">
                            </div>
                            <input type="hidden" name="jumlah_anggaran" :value="grandTotal">
                        </div>
                    </div>
                </div>

@push('scripts')
    <script src="{{ asset('js/rincian-form.js') }}"></script>
@endpush
