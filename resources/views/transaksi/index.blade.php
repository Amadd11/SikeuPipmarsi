<x-app-layout>
    <x-slot:title>Aktivitas & Realisasi</x-slot>

    {{-- Flash Message --}}
    @if (session('success'))
        <div
            class="mb-4 px-4 py-3 rounded-xl bg-green-50 border border-green-100 text-green-700 text-xs flex items-center gap-2">
            <span class="material-symbols-outlined text-[16px]">check_circle</span>
            {{ session('success') }}
        </div>
    @endif

    {{-- Header & Action --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-5">
        <div>
            <h1 class="text-xl font-bold text-gray-900 tracking-tight mb-1">Aktivitas & Realisasi</h1>
            <p class="text-xs text-gray-500">
                Catat setiap transaksi keuangan yang terealisasi
            </p>
        </div>

        <div class="flex flex-col sm:flex-row sm:items-center gap-3">

            {{-- Filter Tahun Anggaran --}}
            <div class="flex items-center gap-2" x-data="{
                onChange(e) {
                    const url = new URL(window.location.href);
                    url.searchParams.set('tahun', e.target.value);
                    url.searchParams.delete('page'); // Reset ke halaman 1
                    window.location.href = url.toString();
                }
            }">
                <label class="text-xs text-gray-500 font-medium whitespace-nowrap">Tahun:</label>
                <div class="relative">
                    <select @change="onChange($event)"
                        class="pl-3 pr-8 py-1.5 text-xs font-semibold rounded-lg border border-gray-200 bg-white text-gray-700 focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none appearance-none cursor-pointer">
                        @foreach ($tahunAnggaranList as $tahun)
                            <option value="{{ $tahun->id }}" {{ $activeTahun == $tahun->id ? 'selected' : '' }}>
                                TA {{ $tahun->tahun }}{{ $tahun->is_aktif ? ' (Aktif)' : '' }}
                            </option>
                        @endforeach
                    </select>
                    <span
                        class="material-symbols-outlined absolute right-2 top-1/2 -translate-y-1/2 text-gray-400 text-[15px] pointer-events-none">
                        expand_more
                    </span>
                </div>
            </div>

            {{-- Tombol Cetak & Tambah --}}
            <x-modal-cetak
                module="transaksi"
                :show-bidang="true"
                :show-tanggal="true"
                :show-jenis="true"
                :tahun-anggaran-list="$tahunAnggaranList"
                :bidang-kerja-list="\App\Models\BidangKerja::orderBy('nama')->get()"
                :active-tahun="$activeTahun" />

            <a href="{{ route('transaksi.create') }}"
                class="inline-flex items-center gap-2 bg-gold text-gray-900 px-4 py-2 rounded-full text-xs font-semibold shadow hover:bg-gold-dark hover:shadow-md transition-all duration-200 active:scale-95">
                <span class="material-symbols-outlined text-[16px]">add</span>
                Catat Transaksi
            </a>
        </div>
    </div>

    {{-- Main Card --}}
    <div class="bg-white rounded-2xl border border-gray-200 overflow-hidden shadow-sm mb-5">

        {{-- Card Header --}}
        <div class="px-5 py-4 border-b border-gray-100 flex flex-wrap gap-4 items-center justify-between">
            <h2 class="font-semibold text-gray-900 text-sm">
                Log Transaksi Keuangan
            </h2>

            {{-- Search Bar Fungsional --}}
            <form method="GET" action="{{ route('transaksi.index') }}" class="relative w-full sm:w-64">
                {{-- Pertahankan filter tahun saat melakukan pencarian --}}
                @if (request('tahun'))
                    <input type="hidden" name="tahun" value="{{ request('tahun') }}">
                @endif

                <span
                    class="material-symbols-outlined absolute left-2.5 top-1/2 -translate-y-1/2 text-gray-400 text-[16px]">
                    search
                </span>
                <input type="text" name="search" value="{{ request('search') }}"
                    placeholder="Cari kode atau uraian..."
                    class="w-full pl-8 pr-3 py-1.5 text-xs border border-gray-200 rounded-lg focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary placeholder-gray-400">
            </form>
        </div>

        {{-- Table Area --}}
        <div class="overflow-x-auto w-full">
            <table class="w-full text-xs text-left">
                <thead
                    class="bg-gray-50 text-[10px] uppercase tracking-widest text-gray-500 font-semibold border-b border-gray-100">
                    <tr>
                        <th class="px-4 py-2.5 text-center w-10">#</th>
                        <th class="px-4 py-2.5 whitespace-nowrap">Tanggal</th>
                        <th class="px-4 py-2.5 whitespace-nowrap">Kode</th>
                        <th class="px-4 py-2.5 min-w-[200px]">Uraian</th>
                        <th class="px-4 py-2.5">Bidang</th>
                        <th class="px-4 py-2.5 text-center">Jenis</th>
                        <th class="px-4 py-2.5 text-right">Jumlah (Rp)</th>
                        <th class="px-4 py-2.5 text-center">Bukti</th>
                        <th class="px-4 py-2.5">Oleh</th>
                        <th class="px-4 py-2.5 text-center">Aksi</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-gray-100 text-gray-700">

                    @forelse ($transaksi as $row)
                        @php
                            // PERBAIKAN: Mengecek relasi jenis berdasarkan enum database ('pemasukan' atau 'pengeluaran')
                            $isPendapatan = $row->jenis === 'pemasukan';
                        @endphp

                        <tr class="hover:bg-gray-50/70 transition">

                            {{-- Nomor Dinamis dengan Pagination --}}
                            <td class="px-4 py-2.5 text-center text-gray-400 font-medium align-top">
                                {{ $loop->iteration + ($transaksi->currentPage() - 1) * $transaksi->perPage() }}
                            </td>

                            {{-- Tanggal --}}
                            <td class="px-4 py-2.5 align-top whitespace-nowrap">
                                <span class="text-gray-900 font-medium">
                                    {{ \Carbon\Carbon::parse($row->tanggal)->translatedFormat('d M Y') }}
                                </span>
                            </td>

                            {{-- Kode TRX --}}
                            <td class="px-4 py-2.5 align-top whitespace-nowrap">
                                <span
                                    class="font-bold text-gray-600 text-[11px] bg-gray-100 px-2 py-0.5 rounded border border-gray-200/50">
                                    {{ $row->kode_transaksi }}
                                </span>
                            </td>

                            {{-- Uraian --}}
                            <td class="px-4 py-2.5 align-top">
                                <span class="font-medium text-gray-900 leading-snug block">
                                    {{ $row->uraian }}
                                </span>
                            </td>

                            {{-- Bidang --}}
                            <td class="px-4 py-2.5 align-top">
                                <span class="text-gray-600 leading-snug block">
                                    {{ $row->bidangKerja ? $row->bidangKerja->nama : 'Umum / Organisasi' }}
                                </span>
                            </td>

                            {{-- Jenis --}}
                            <td class="px-4 py-2.5 text-center align-top">
                                @if ($isPendapatan)
                                    <span
                                        class="inline-flex items-center gap-1 px-2 py-0.5 rounded-md bg-green-50 text-green-700 text-[10px] font-semibold tracking-wide border border-green-100/50">
                                        <span class="material-symbols-outlined text-[12px]">south_west</span>
                                        Masuk
                                    </span>
                                @else
                                    <span
                                        class="inline-flex items-center gap-1 px-2 py-0.5 rounded-md bg-red-50 text-red-700 text-[10px] font-semibold tracking-wide border border-red-100/50">
                                        <span class="material-symbols-outlined text-[12px]">north_east</span>
                                        Keluar
                                    </span>
                                @endif
                            </td>

                            {{-- Jumlah Nominal --}}
                            <td class="px-4 py-2.5 text-right align-top whitespace-nowrap">
                                <span class="font-bold {{ $isPendapatan ? 'text-green-600' : 'text-gray-900' }}">
                                    {{ $isPendapatan ? '+' : '-' }} Rp {{ number_format($row->jumlah, 0, ',', '.') }}
                                </span>
                            </td>

                            {{-- Bukti Lampiran --}}
                            <td class="px-4 py-2.5 text-center align-top">
                                @if ($row->file_bukti)
                                    <a href="{{ Storage::url($row->file_bukti) }}" target="_blank"
                                        title="Lihat Lampiran"
                                        class="inline-flex items-center justify-center w-7 h-7 bg-blue-50 text-blue-600 hover:bg-blue-100 rounded-lg transition-colors border border-blue-100/50">
                                        <span class="material-symbols-outlined text-[16px]">attachment</span>
                                    </a>
                                @else
                                    <span class="text-gray-300">-</span>
                                @endif
                            </td>

                            {{-- Oleh --}}
                            <td class="px-4 py-2.5 align-top whitespace-nowrap text-gray-500">
                                {{ $row->user ? $row->user->name : 'Sistem' }}
                            </td>

                            {{-- Aksi --}}
                            <td class="px-4 py-2.5 align-top">
                                <div class="flex items-center justify-center gap-1.5">
                                    <a href="{{ route('transaksi.edit', $row->id) }}" title="Edit Transaksi"
                                        class="w-8 h-8 rounded-full bg-gray-50 border border-gray-200 flex items-center justify-center text-gray-500 hover:bg-indigo-50 hover:border-indigo-300 hover:text-indigo-600 transition-all duration-200">
                                        <span class="material-symbols-outlined text-[16px]">edit</span>
                                    </a>

                                    <button
                                        @click="$dispatch('open-delete-modal', {
                                            action: '{{ route('transaksi.destroy', $row->id) }}',
                                            message: 'Yakin ingin membatalkan dan menghapus transaksi {{ $row->kode_transaksi }}?'
                                        })"
                                        title="Hapus Transaksi"
                                        class="w-8 h-8 rounded-full bg-red-50/50 border border-red-200 flex items-center justify-center text-red-400 hover:bg-red-100 hover:border-red-400 hover:text-red-600 transition-all duration-200">
                                        <span class="material-symbols-outlined text-[16px]">delete</span>
                                    </button>
                                </div>
                            </td>

                        </tr>

                    @empty
                        <tr>
                            <td colspan="10" class="py-12 text-center">
                                <div class="flex flex-col items-center gap-2">
                                    <span class="material-symbols-outlined text-4xl text-gray-300">receipt_long</span>
                                    <h3 class="font-medium text-gray-700 text-sm">Belum ada transaksi</h3>
                                    <p class="text-xs text-gray-500">
                                        @if (request('search'))
                                            Tidak ditemukan transaksi yang cocok dengan pencarian Anda.
                                        @else
                                            Log aktivitas akan muncul setelah Anda mencatat transaksi.
                                        @endif
                                    </p>
                                </div>
                            </td>
                        </tr>
                    @endforelse

                </tbody>
            </table>
        </div>

        {{-- Laravel Dynamic Pagination --}}
        @if ($transaksi->hasPages())
            <div class="px-5 py-3 border-t border-gray-100 text-xs bg-gray-50/30">
                {{ $transaksi->appends(request()->query())->links() }}
            </div>
        @endif

    </div>

    {{-- Komponen Global Delete Modal --}}
    <x-modal-delete />

</x-app-layout>
