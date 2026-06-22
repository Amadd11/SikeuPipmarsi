<?php

namespace App\Http\Controllers;

use App\Http\Requests\RencanaPendapatanStoreRequest;
use App\Http\Requests\RencanaPendapatanUpdateRequest;
use App\Models\RencanaPendapatan;
use App\Services\RencanaPendapatanService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class RencanaPendapatanController extends Controller
{

    public function __construct(
        protected RencanaPendapatanService $rencanaPendapatanService
    ) {}

    public function index(): View
    {
        $pendapatan = $this->rencanaPendapatanService->getListByTahunAktif();
        $summary    = $this->rencanaPendapatanService->getSummaryByTahunAktif();

        return view('pendapatan.index', [
            'pendapatan'     => $pendapatan,
            'totalRencana'   => $summary['totalRencana'],
            'totalRealisasi' => $summary['totalRealisasi'],
            'sisaAnggaran'   => $summary['sisaAnggaran'],
        ]);
    }

    public function create(): View
    {
        $options = $this->rencanaPendapatanService->getFormOptions();

        return view('pendapatan.create', $options);
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

        return view('pendapatan.edit', [
            'pendapatan'   => $pendapatan,
            'kategoriList' => $options['kategoriList'],
        ]);
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
}
