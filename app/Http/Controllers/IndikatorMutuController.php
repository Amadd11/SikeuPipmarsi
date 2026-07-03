<?php

namespace App\Http\Controllers;

use App\Http\Requests\IndikatorMutuStoreRequest;
use App\Http\Requests\IndikatorMutuUpdateRequest;
use App\Models\IndikatorMutu;
use App\Services\IndikatorMutuService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class IndikatorMutuController extends Controller
{
    public function __construct(
        private readonly IndikatorMutuService $service
    ) {}

    public function index(Request $request): View
    {
        $bidangKerjaId = $request->integer('bidang') ?: null;

        $indikators      = $this->service->getList($bidangKerjaId);
        $ringkasanBidang = $this->service->getRingkasanByBidang();
        $options         = $this->service->getFormOptions();

        return view('indikator-mutu.index', [
            'indikators'      => $indikators,
            'ringkasanBidang' => $ringkasanBidang,
            'bidangKerjaList' => $options['bidangKerjaList'],
            'statusOptions'   => $options['statusOptions'],
            'activeBidang'    => $bidangKerjaId,
        ]);
    }

    public function create(): View
    {
        $options = $this->service->getFormOptions();

        return view('indikator-mutu.create', $options);
    }

    public function store(IndikatorMutuStoreRequest $request): RedirectResponse
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
            ->route('indikator-mutu.index')
            ->with('success', 'Indikator mutu berhasil ditambahkan.');
    }

    public function edit(IndikatorMutu $indikatorMutu): View
    {
        $options = $this->service->getFormOptions();

        return view('indikator-mutu.edit', [
            'indikator' => $indikatorMutu,
            ...$options,
        ]);
    }

    public function update(IndikatorMutuUpdateRequest $request, IndikatorMutu $indikatorMutu): RedirectResponse
    {
        $validated = $request->validated();

        try {
            $this->service->update($indikatorMutu, $validated);
        } catch (\Exception $e) {
            return back()
                ->withInput()
                ->withErrors(['general' => $e->getMessage()]);
        }

        return redirect()
            ->route('indikator-mutu.index')
            ->with('success', 'Indikator mutu berhasil diperbarui.');
    }

    public function destroy(IndikatorMutu $indikatorMutu): RedirectResponse
    {
        try {
            $this->service->destroy($indikatorMutu);
        } catch (\Exception $e) {
            return back()->withErrors(['general' => $e->getMessage()]);
        }

        return redirect()
            ->route('indikator-mutu.index')
            ->with('success', 'Indikator mutu berhasil dihapus.');
    }
}
