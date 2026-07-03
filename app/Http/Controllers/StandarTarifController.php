<?php

namespace App\Http\Controllers;

use App\Http\Requests\StandarTarifStoreRequest;
use App\Http\Requests\StandarTarifUpdateRequest;
use App\Models\StandarTarif;
use App\Services\StandarTarifService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class StandarTarifController extends Controller
{
    public function __construct(
        private readonly StandarTarifService $service
    ) {}

    public function index(Request $request): View
    {
        $search    = $request->string('search')->toString() ?: null;
        $tarifList = $this->service->getList($search);

        return view('standar-tarif.index', compact('tarifList', 'search'));
    }

    public function create(): View
    {
        return view('standar-tarif.create');
    }

    public function store(StandarTarifStoreRequest $request): RedirectResponse
    {
        $validated    = $request->validated();
        $uploadedPath = null;

        // Upload PDF — wajib ada (sudah divalidasi di request)
        $uploadedPath = $request->file('file')->store('standar-tarif', 'public');

        try {
            $this->service->store($validated, $uploadedPath);
        } catch (\Exception $e) {
            // Rollback: hapus file yang sudah terupload
            if ($uploadedPath) {
                Storage::disk('public')->delete($uploadedPath);
            }

            return back()
                ->withInput()
                ->withErrors(['general' => $e->getMessage()]);
        }

        return redirect()
            ->route('standar-tarif.index')
            ->with('success', 'Standar tarif berhasil ditambahkan.');
    }

    public function show(StandarTarif $standarTarif): View
    {
        return view('standar-tarif.show', compact('standarTarif'));
    }

    public function edit(StandarTarif $standarTarif): View
    {
        return view('standar-tarif.edit', compact('standarTarif'));
    }

    public function update(StandarTarifUpdateRequest $request, StandarTarif $standarTarif): RedirectResponse
    {
        $validated    = $request->validated();
        $uploadedPath = null;

        if ($request->hasFile('file')) {
            $uploadedPath = $request->file('file')->store('standar-tarif', 'public');
        }

        try {
            $this->service->update($standarTarif, $validated, $uploadedPath);
        } catch (\Exception $e) {
            // Rollback: hapus file baru, file lama dibiarkan
            if ($uploadedPath) {
                Storage::disk('public')->delete($uploadedPath);
            }

            return back()
                ->withInput()
                ->withErrors(['general' => $e->getMessage()]);
        }

        return redirect()
            ->route('standar-tarif.index')
            ->with('success', 'Standar tarif berhasil diperbarui.');
    }

    public function destroy(StandarTarif $standarTarif): RedirectResponse
    {
        try {
            $this->service->destroy($standarTarif);
        } catch (\Exception $e) {
            return back()->withErrors(['general' => $e->getMessage()]);
        }

        return redirect()
            ->route('standar-tarif.index')
            ->with('success', 'Standar tarif berhasil dihapus.');
    }
}
