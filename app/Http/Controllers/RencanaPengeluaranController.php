<?php

namespace App\Http\Controllers;

use App\Models\RencanaPengeluaran;
use App\Models\TahunAnggaran;
use App\Services\RencanaPengeluaranService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class RencanaPengeluaranController extends Controller
{
    public function __construct(
        private readonly RencanaPengeluaranService $service
    ) {}

    public function index(Request $request): View
    {
        $tahunAnggaranId = $request->integer('tahun') ?: optional(
            TahunAnggaran::query()->where('is_aktif', true)->first()
        )->id;

        $bidangKerjaId = $request->integer('bidang') ?: null;

        $pengeluaran = $this->service->getList($tahunAnggaranId, $bidangKerjaId);

        $summary = $bidangKerjaId
            ? $this->service->getSummaryByBidang($tahunAnggaranId, $bidangKerjaId)
            : $this->service->getSummaryAll($tahunAnggaranId);

        $semuaBidang     = $this->service->getAllBidang();
        $ringkasanBidang = $this->service->getRingkasanAllBidang($tahunAnggaranId);
        $tahunAnggaranList = TahunAnggaran::query()->orderByDesc('tahun')->get();

        return view('pengeluaran.index', [
            'pengeluaran'      => $pengeluaran,
            'totalAnggaran'    => $summary['totalAnggaran'],
            'totalRealisasi'   => $summary['totalRealisasi'],
            'sisaAnggaran'     => $summary['sisaAnggaran'],
            'semuaBidang'      => $semuaBidang,
            'ringkasanBidang'  => $ringkasanBidang,
            'activeBidang'     => $bidangKerjaId,
            'tahunAnggaranList' => $tahunAnggaranList,
            'activeTahun'      => $tahunAnggaranId,
        ]);
    }

    public function create(): View
    {
        $options = $this->service->getFormOptions();

        return view('pengeluaran.create', $options);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'tahun_anggaran_id'       => ['required', 'exists:tahun_anggaran,id'],
            'bidang_kerja_id'         => ['required', 'exists:bidang_kerja,id'],
            'kategori_pengeluaran_id' => ['required', 'exists:kategori_pengeluaran,id'],
            'indikator_mutu_id'       => ['nullable', 'exists:indikator_mutu,id'],
            'nama_kegiatan'           => ['required', 'string', 'max:200'],
            'jumlah_anggaran'         => ['required', 'numeric', 'min:0'],
            'keterangan'              => ['nullable', 'string'],
        ]);

        try {
            $this->service->store($validated);
        } catch (\Exception $e) {
            return back()
                ->withInput()
                ->withErrors(['general' => $e->getMessage()]);
        }

        return redirect()
            ->route('pengeluaran.index', [
                'tahun'  => $validated['tahun_anggaran_id'],
                'bidang' => $validated['bidang_kerja_id'],
            ])
            ->with('success', 'Rencana pengeluaran berhasil ditambahkan.');
    }

    public function edit(RencanaPengeluaran $pengeluaran): View
    {
        $options = $this->service->getFormOptions();

        return view('pengeluaran.edit', [
            'pengeluaran' => $pengeluaran,
            ...$options,
        ]);
    }

    public function update(Request $request, RencanaPengeluaran $pengeluaran): RedirectResponse
    {
        $validated = $request->validate([
            'tahun_anggaran_id'       => ['required', 'exists:tahun_anggaran,id'],
            'bidang_kerja_id'         => ['required', 'exists:bidang_kerja,id'],
            'kategori_pengeluaran_id' => ['required', 'exists:kategori_pengeluaran,id'],
            'indikator_mutu_id'       => ['nullable', 'exists:indikator_mutu,id'],
            'nama_kegiatan'           => ['required', 'string', 'max:200'],
            'jumlah_anggaran'         => ['required', 'numeric', 'min:0'],
            'keterangan'              => ['nullable', 'string'],
        ]);

        try {
            $this->service->update($pengeluaran, $validated);
        } catch (\Exception $e) {
            return back()
                ->withInput()
                ->withErrors(['general' => $e->getMessage()]);
        }

        return redirect()
            ->route('pengeluaran.index', [
                'tahun'  => $validated['tahun_anggaran_id'],
                'bidang' => $validated['bidang_kerja_id'],
            ])
            ->with('success', 'Rencana pengeluaran berhasil diperbarui.');
    }

    public function destroy(RencanaPengeluaran $pengeluaran): RedirectResponse
    {
        $tahunId  = $pengeluaran->tahun_anggaran_id;
        $bidangId = $pengeluaran->bidang_kerja_id;

        try {
            $this->service->destroy($pengeluaran);
        } catch (\Exception $e) {
            return back()->withErrors(['general' => $e->getMessage()]);
        }

        return redirect()
            ->route('pengeluaran.index', [
                'tahun'  => $tahunId,
                'bidang' => $bidangId,
            ])
            ->with('success', 'Rencana pengeluaran berhasil dihapus.');
    }
}
