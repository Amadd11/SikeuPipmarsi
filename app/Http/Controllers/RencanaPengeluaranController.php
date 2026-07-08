<?php

namespace App\Http\Controllers;

use App\Models\RencanaPengeluaran;
use App\Models\TahunAnggaran;
use App\Services\RencanaPengeluaranService;
use App\Http\Requests\RencanaPengeluaranStoreRequest;
use App\Http\Requests\RencanaPengeluaranUpdateRequest;
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

        $initialDetails = old('details') 
            ? $this->formatDetails(old('details')) 
            : [['id' => 1, 'uraian' => '', 'satuan' => '', 'hargaRaw' => '', 'hargaDisplay' => '', 'kuantitas' => 1]];

        return view('pengeluaran.create', array_merge($options, [
            'initialDetails' => $initialDetails,
        ]));
    }

    public function store(RencanaPengeluaranStoreRequest $request): RedirectResponse
    {
        $validated = $request->validated();

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

        $initialDetails = old('details') 
            ? $this->formatDetails(old('details')) 
            : $this->formatDetailsFromModel($pengeluaran->details);

        if (empty($initialDetails)) {
            $initialDetails = [['id' => 1, 'uraian' => '', 'satuan' => '', 'hargaRaw' => '', 'hargaDisplay' => '', 'kuantitas' => 1]];
        }

        return view('pengeluaran.edit', array_merge($options, [
            'pengeluaran'    => $pengeluaran,
            'initialDetails' => $initialDetails,
        ]));
    }

    public function update(RencanaPengeluaranUpdateRequest $request, RencanaPengeluaran $pengeluaran): RedirectResponse
    {
        $validated = $request->validated();

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

    private function formatDetails(?array $oldDetails): array
    {
        if (empty($oldDetails)) return [];
        
        $initialDetails = [];
        foreach ($oldDetails as $idx => $d) {
            $initialDetails[] = [
                'id' => $idx,
                'uraian' => $d['uraian'] ?? '',
                'satuan' => $d['satuan'] ?? '',
                'hargaRaw' => $d['harga'] ?? '',
                'hargaDisplay' => isset($d['harga']) ? number_format((float) $d['harga'], 0, ',', '.') : '',
                'kuantitas' => $d['kuantitas'] ?? 1
            ];
        }
        return $initialDetails;
    }

    private function formatDetailsFromModel($details): array
    {
        $initialDetails = [];
        foreach ($details as $d) {
            $initialDetails[] = [
                'id' => $d->id,
                'uraian' => $d->uraian,
                'satuan' => $d->satuan,
                'hargaRaw' => $d->harga,
                'hargaDisplay' => number_format((float) $d->harga, 0, ',', '.'),
                'kuantitas' => $d->kuantitas
            ];
        }
        return $initialDetails;
    }
}
