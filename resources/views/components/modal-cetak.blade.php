{{--
    Modal Cetak Laporan (Reusable)
    
    Usage:
    <x-modal-cetak 
        module="pendapatan" 
        :show-bidang="false" 
        :show-tanggal="false" 
        :tahun-anggaran-list="$tahunAnggaranList ?? collect()"
        :bidang-kerja-list="$bidangKerjaList ?? collect()" />
--}}

@props([
    'module',
    'showBidang'         => false,
    'showTanggal'        => false,
    'showJenis'          => false,
    'tahunAnggaranList'  => collect(),
    'bidangKerjaList'    => collect(),
    'activeTahun'        => null,
    'activeBidang'       => null,
])

<div x-data="{ open: false, format: 'pdf' }" x-cloak>

    {{-- Trigger Button --}}
    <button @click="open = true" type="button"
        class="inline-flex items-center justify-center gap-2 bg-white text-gray-700 px-4 py-2 rounded-full text-xs font-semibold border border-gray-200 shadow-sm hover:bg-gray-50 hover:border-gray-300 hover:shadow transition-all duration-200 active:scale-95">
        <span class="material-symbols-outlined text-[16px]" style="font-variation-settings: 'FILL' 1">print</span>
        Cetak Laporan
    </button>

    {{-- Modal Overlay --}}
    <div x-show="open" x-transition.opacity @click.self="open = false"
        class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 p-4" style="display: none;">

        {{-- Modal Content --}}
        <div x-show="open" x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
            x-transition:leave="transition ease-in duration-150"
            x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95"
            class="bg-white rounded-3xl shadow-2xl w-full max-w-md overflow-hidden">

            {{-- Header --}}
            <div class="px-6 py-4 border-b border-gray-100 bg-gray-50/50 flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 rounded-xl bg-primary/10 flex items-center justify-center">
                        <span class="material-symbols-outlined text-primary text-[18px]" style="font-variation-settings: 'FILL' 1">print</span>
                    </div>
                    <div>
                        <h3 class="font-bold text-gray-800 text-sm">Cetak Laporan</h3>
                        <p class="text-[10px] text-gray-400 uppercase tracking-widest font-semibold">{{ str_replace('-', ' ', $module) }}</p>
                    </div>
                </div>
                <button @click="open = false" class="w-8 h-8 rounded-full flex items-center justify-center hover:bg-gray-200 text-gray-400 transition-colors">
                    <span class="material-symbols-outlined text-[18px]">close</span>
                </button>
            </div>

            {{-- Body --}}
            <form method="GET" action="{{ route('laporan.' . $module) }}" target="_blank" class="p-6 space-y-5">

                {{-- Format Selection --}}
                <div>
                    <label class="block text-[10px] font-bold text-gray-500 uppercase tracking-widest mb-2">Format Ekspor</label>
                    <div class="grid grid-cols-2 gap-2">
                        <label class="cursor-pointer">
                            <input type="radio" name="format" value="pdf" x-model="format" class="hidden peer">
                            <div class="flex flex-col items-center gap-1.5 p-3 rounded-2xl border-2 border-gray-100 text-gray-500 peer-checked:border-red-400 peer-checked:bg-red-50 peer-checked:text-red-600 hover:bg-gray-50 transition-all">
                                <span class="material-symbols-outlined text-[24px]" style="font-variation-settings: 'FILL' 1">picture_as_pdf</span>
                                <span class="text-[10px] font-bold uppercase tracking-wider">PDF</span>
                            </div>
                        </label>
                        <label class="cursor-pointer">
                            <input type="radio" name="format" value="excel" x-model="format" class="hidden peer">
                            <div class="flex flex-col items-center gap-1.5 p-3 rounded-2xl border-2 border-gray-100 text-gray-500 peer-checked:border-emerald-400 peer-checked:bg-emerald-50 peer-checked:text-emerald-600 hover:bg-gray-50 transition-all">
                                <span class="material-symbols-outlined text-[24px]" style="font-variation-settings: 'FILL' 1">table_chart</span>
                                <span class="text-[10px] font-bold uppercase tracking-wider">Excel</span>
                            </div>
                        </label>
                    </div>
                </div>

                {{-- Tahun Anggaran --}}
                @if ($tahunAnggaranList->count() > 0)
                <div>
                    <label class="block text-[10px] font-bold text-gray-500 uppercase tracking-widest mb-1.5">Tahun Anggaran</label>
                    <select name="tahun_anggaran_id"
                        class="w-full px-3 py-2 text-xs rounded-xl border border-gray-200 focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none bg-white">
                        @foreach ($tahunAnggaranList as $tahun)
                            <option value="{{ $tahun->id }}" @selected($tahun->id == $activeTahun || ($activeTahun === null && $tahun->is_aktif))>
                                {{ $tahun->label ?? $tahun->tahun }}
                                @if ($tahun->is_aktif) (Aktif) @endif
                            </option>
                        @endforeach
                    </select>
                </div>
                @endif

                {{-- Bidang Kerja (optional) --}}
                @if ($showBidang && $bidangKerjaList->count() > 0)
                <div>
                    <label class="block text-[10px] font-bold text-gray-500 uppercase tracking-widest mb-1.5">Bidang Kerja</label>
                    <select name="bidang_kerja_id"
                        class="w-full px-3 py-2 text-xs rounded-xl border border-gray-200 focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none bg-white">
                        <option value="">— Semua Bidang —</option>
                        @foreach ($bidangKerjaList as $bidang)
                            <option value="{{ $bidang->id }}" @selected($bidang->id == $activeBidang)>
                                {{ $bidang->nama }}
                            </option>
                        @endforeach
                    </select>
                </div>
                @endif

                {{-- Jenis Transaksi (optional) --}}
                @if ($showJenis)
                <div>
                    <label class="block text-[10px] font-bold text-gray-500 uppercase tracking-widest mb-1.5">Jenis Transaksi</label>
                    <select name="jenis"
                        class="w-full px-3 py-2 text-xs rounded-xl border border-gray-200 focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none bg-white">
                        <option value="">— Semua —</option>
                        <option value="pemasukan">Pemasukan</option>
                        <option value="pengeluaran">Pengeluaran</option>
                    </select>
                </div>
                @endif

                {{-- Tanggal Range (optional) --}}
                @if ($showTanggal)
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-[10px] font-bold text-gray-500 uppercase tracking-widest mb-1.5">Dari Tanggal</label>
                        <input type="date" name="tanggal_dari"
                            class="w-full px-3 py-2 text-xs rounded-xl border border-gray-200 focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none bg-white">
                    </div>
                    <div>
                        <label class="block text-[10px] font-bold text-gray-500 uppercase tracking-widest mb-1.5">Sampai Tanggal</label>
                        <input type="date" name="tanggal_sampai"
                            class="w-full px-3 py-2 text-xs rounded-xl border border-gray-200 focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none bg-white">
                    </div>
                </div>
                @endif

                {{-- Actions --}}
                <div class="flex flex-col-reverse sm:flex-row sm:justify-end gap-2 pt-3 border-t border-gray-100">
                    <button type="button" @click="open = false"
                        class="inline-flex items-center justify-center px-5 py-2 rounded-full border border-gray-200 text-gray-600 text-xs font-medium hover:bg-gray-100 transition-all active:scale-95">
                        Batal
                    </button>
                    <button type="submit"
                        class="inline-flex items-center justify-center gap-2 bg-gold text-gray-900 px-5 py-2 rounded-full text-xs font-semibold shadow hover:bg-gold-dark hover:shadow-md transition-all duration-200 active:scale-95">
                        <span class="material-symbols-outlined text-[16px]" style="font-variation-settings: 'FILL' 1">download</span>
                        Unduh Laporan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
