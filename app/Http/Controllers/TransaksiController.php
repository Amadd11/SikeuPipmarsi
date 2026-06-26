<?php

namespace App\Http\Controllers;

use App\Http\Requests\TransaksiStoreRequest;
use App\Http\Requests\TransaksiUpdateRequest;
use App\Models\BidangKerja;
use App\Models\RencanaPendapatan;
use App\Models\RencanaPengeluaran;
use App\Models\TahunAnggaran;
use App\Models\Transaksi;
use App\Services\TransaksiService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class TransaksiController extends Controller
{
    public function __construct(
        private readonly TransaksiService $service,
    ) {}

    public function index(Request $request): View
    {
        $tahunAnggaranList = TahunAnggaran::query()->orderByDesc('tahun')->get();

        $tahunAnggaranId = $request->integer('tahun') ?: optional(
            TahunAnggaran::query()->where('is_aktif', true)->first()
        )->id;

        $filters = $request->only(['jenis', 'bidang_kerja_id', 'tanggal_dari', 'tanggal_sampai']);

        // Belum ada Tahun Anggaran aktif/terpilih — tampilkan halaman kosong
        // daripada lempar TypeError ke service.
        if (!$tahunAnggaranId) {
            return view('transaksi.index', [
                'transaksi'         => $this->service->emptyList(),
                'tahunAnggaranList' => $tahunAnggaranList,
                'activeTahun'       => null,
                'filters'           => $filters,
            ])->with('warning', 'Belum ada Tahun Anggaran aktif. Silakan pilih atau buat Tahun Anggaran terlebih dahulu.');
        }

        $transaksi = $this->service->getList($tahunAnggaranId, $filters);

        return view('transaksi.index', [
            'transaksi'         => $transaksi,
            'tahunAnggaranList' => $tahunAnggaranList,
            'activeTahun'       => $tahunAnggaranId,
            'filters'           => $filters,
        ]);
    }

    public function create(Request $request): View
    {
        $jenis          = $request->query('jenis', 'pemasukan');
        $transaksableId = $request->integer('transaksable_id') ?: null;

        $tahunAnggaranList = TahunAnggaran::query()->orderByDesc('tahun')->get();
        $bidangKerjaList   = BidangKerja::query()->orderBy('nama')->get();

        // Kirim KEDUA list sekaligus — supaya tidak perlu reload saat ganti jenis
        $rencanaPendapatanList  = $this->getRencanaPendapatanList();
        $rencanaPengeluaranList = $this->getRencanaPengeluaranList();

        return view('transaksi.create', compact(
            'jenis',
            'transaksableId',
            'tahunAnggaranList',
            'bidangKerjaList',
            'rencanaPendapatanList',
            'rencanaPengeluaranList',
        ));
    }

    public function store(TransaksiStoreRequest $request): RedirectResponse
    {
        $validated = $request->validated();
        $uploadedPath = null;

        if ($request->hasFile('file_bukti')) {
            $uploadedPath = $request->file('file_bukti')->store('bukti-transaksi', 'public');
            $validated['file_bukti'] = $uploadedPath;
        }

        try {
            $this->service->store($validated);
        } catch (\Exception $e) {
            // Gagal simpan transaksi -> bersihkan file yang sudah terupload
            // supaya tidak jadi sampah orphan di storage.
            if ($uploadedPath) {
                Storage::disk('public')->delete($uploadedPath);
            }

            return back()->withInput()->withErrors(['general' => $e->getMessage()]);
        }

        return redirect()
            ->route('transaksi.index')
            ->with('success', 'Transaksi berhasil disimpan.');
    }

    public function edit(Transaksi $transaksi): View
    {
        $tahunAnggaranList = TahunAnggaran::query()->orderByDesc('tahun')->get();

        $bidangKerjaList   = BidangKerja::query()->orderBy('nama')->get();

        $rencanaPendapatanList  = $this->getRencanaPendapatanList();
        $rencanaPengeluaranList = $this->getRencanaPengeluaranList();

        return view('transaksi.edit', compact(
            'transaksi',
            'tahunAnggaranList',
            'bidangKerjaList',
            'rencanaPendapatanList',
            'rencanaPengeluaranList',
        ));
    }

    public function update(TransaksiUpdateRequest $request, Transaksi $transaksi): RedirectResponse
    {
        $validated = $request->validated();
        $uploadedPath = null;
        $oldFilePath = $transaksi->file_bukti;

        if ($request->hasFile('file_bukti')) {
            $uploadedPath = $request->file('file_bukti')->store('bukti-transaksi', 'public');
            $validated['file_bukti'] = $uploadedPath;
        }

        try {
            $this->service->update($transaksi, $validated);
        } catch (\Exception $e) {
            // Gagal update -> bersihkan file BARU yang sudah terupload,
            // file lama tetap dibiarkan karena belum jadi diganti.
            if ($uploadedPath) {
                Storage::disk('public')->delete($uploadedPath);
            }

            return back()->withInput()->withErrors(['general' => $e->getMessage()]);
        }

        // Update berhasil & ada file baru -> hapus file lama supaya tidak menumpuk.
        if ($uploadedPath && $oldFilePath) {
            Storage::disk('public')->delete($oldFilePath);
        }

        return redirect()
            ->route('transaksi.index')
            ->with('success', 'Transaksi berhasil diperbarui.');
    }

    public function destroy(Transaksi $transaksi): RedirectResponse
    {
        try {
            $this->service->destroy($transaksi);
        } catch (\Exception $e) {
            return back()->withErrors(['general' => $e->getMessage()]);
        }

        return redirect()
            ->route('transaksi.index')
            ->with('success', 'Transaksi berhasil dihapus.');
    }

    // -------------------------------------------------------------------------
    // PRIVATE HELPERS
    // -------------------------------------------------------------------------

    private function getRencanaPendapatanList(): \Illuminate\Support\Collection
    {
        return RencanaPendapatan::query()
            ->with('kategoriPendapatan')
            ->orderBy('nama_sumber')
            ->get();
    }

    private function getRencanaPengeluaranList(): \Illuminate\Support\Collection
    {
        return RencanaPengeluaran::query()
            ->with('bidangKerja')
            ->orderBy('nama_kegiatan')
            ->get();
    }
}
