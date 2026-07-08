<?php

namespace App\Http\Controllers;

use App\Http\Requests\RencanaPendapatanStoreRequest;
use App\Http\Requests\RencanaPendapatanUpdateRequest;
use App\Models\RencanaPendapatan;
use App\Models\TahunAnggaran;
use App\Services\RencanaPendapatanService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class RencanaPendapatanController extends Controller
{

    public function __construct(
        protected RencanaPendapatanService $rencanaPendapatanService
    ) {}

    public function index(Request $request): View
    {
        $tahunAnggaranId = $request->integer('tahun') ?: optional(
            TahunAnggaran::query()->where('is_aktif', true)->first()
        )->id;

        $pendapatan = $this->rencanaPendapatanService->getList($tahunAnggaranId);
        $summary    = $this->rencanaPendapatanService->getSummary($tahunAnggaranId);
        $tahunAnggaranList = TahunAnggaran::query()->orderByDesc('tahun')->get();

        return view('pendapatan.index', [
            'pendapatan'       => $pendapatan,
            'totalRencana'     => $summary['totalRencana'],
            'totalRealisasi'   => $summary['totalRealisasi'],
            'sisaAnggaran'     => $summary['sisaAnggaran'],
            'tahunAnggaranList'=> $tahunAnggaranList,
            'activeTahun'      => $tahunAnggaranId,
        ]);
    }

    public function create(): View
    {
        $options = $this->rencanaPendapatanService->getFormOptions();

        $initialDetails = old('details') 
            ? $this->formatDetails(old('details')) 
            : [['id' => 1, 'uraian' => '', 'satuan' => '', 'hargaRaw' => '', 'hargaDisplay' => '', 'kuantitas' => 1]];

        return view('pendapatan.create', array_merge($options, [
            'initialDetails' => $initialDetails,
        ]));
    }

    public function store(RencanaPendapatanStoreRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        try {
            $this->rencanaPendapatanService->store($validated);
        } catch (\Exception $e) {
            return back()
                ->withInput()
                ->withErrors(['tahun_anggaran' => $e->getMessage()]);
        }

        return redirect()
            ->route('pendapatan.index')
            ->with('success', 'Rencana Pendapatan berhasil ditambahkan.');
    }

    public function edit(RencanaPendapatan $pendapatan): View
    {
        $options = $this->rencanaPendapatanService->getFormOptions();

        $initialDetails = old('details') 
            ? $this->formatDetails(old('details')) 
            : $this->formatDetailsFromModel($pendapatan->details);

        if (empty($initialDetails)) {
            $initialDetails = [['id' => 1, 'uraian' => '', 'satuan' => '', 'hargaRaw' => '', 'hargaDisplay' => '', 'kuantitas' => 1]];
        }

        return view('pendapatan.edit', array_merge($options, [
            'pendapatan'     => $pendapatan,
            'initialDetails' => $initialDetails,
        ]));
    }

    public function update(RencanaPendapatanUpdateRequest $request, RencanaPendapatan $pendapatan): RedirectResponse
    {
        $validated = $request->validated();

        try {
            $this->rencanaPendapatanService->update($pendapatan, $validated);
        } catch (\Exception $e) {
            return back()
                ->withInput()
                ->withErrors(['general' => $e->getMessage()]);
        }

        return redirect()
            ->route('pendapatan.index')
            ->with('success', 'RencanaPendapatan berhasil diperbarui.');
    }

    public function destroy(RencanaPendapatan $pendapatan): RedirectResponse
    {
        try {
            $this->rencanaPendapatanService->destroy($pendapatan);
        } catch (\Exception $e) {
            return back()->withErrors(['general' => $e->getMessage()]);
        }

        return redirect()
            ->route('pendapatan.index')
            ->with('success', 'Rencana Pendapatan berhasil dihapus.');
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
                'hargaRaw' => $d['jumlah'] ?? '',
                'hargaDisplay' => isset($d['jumlah']) ? number_format((float) $d['jumlah'], 0, ',', '.') : '',
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
                'hargaRaw' => $d->jumlah,
                'hargaDisplay' => number_format((float) $d->jumlah, 0, ',', '.'),
                'kuantitas' => $d->kuantitas
            ];
        }
        return $initialDetails;
    }
}
