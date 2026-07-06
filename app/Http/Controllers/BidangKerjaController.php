<?php

namespace App\Http\Controllers;

use App\Http\Requests\BidangKerjaStoreRequest;
use App\Http\Requests\BidangKerjaUpdateRequest;
use App\Models\BidangKerja;
use App\Services\BidangKerjaService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class BidangKerjaController extends Controller
{
    public function __construct(
        private readonly BidangKerjaService $service
    ) {}

    public function index(Request $request): View
    {
        $search      = $request->string('search')->toString() ?: null;
        $bidangKerjaList = $this->service->getList($search);

        return view('bidang-kerja.index', compact('bidangKerjaList', 'search'));
    }

    public function create(): View
    {
        return view('bidang-kerja.create');
    }

    public function store(BidangKerjaStoreRequest $request): RedirectResponse
    {
        try {
            $this->service->store($request->validated());
        } catch (\Exception $e) {
            return back()
                ->withInput()
                ->withErrors(['general' => $e->getMessage()]);
        }

        return redirect()
            ->route('bidang-kerja.index')
            ->with('success', 'Bidang kerja berhasil ditambahkan.');
    }

    public function edit(BidangKerja $bidangKerja): View
    {
        return view('bidang-kerja.edit', compact('bidangKerja'));
    }

    public function update(BidangKerjaUpdateRequest $request, BidangKerja $bidangKerja): RedirectResponse
    {
        try {
            $this->service->update($bidangKerja, $request->validated());
        } catch (\Exception $e) {
            return back()
                ->withInput()
                ->withErrors(['general' => $e->getMessage()]);
        }

        return redirect()
            ->route('bidang-kerja.index')
            ->with('success', 'Bidang kerja berhasil diperbarui.');
    }

    public function destroy(BidangKerja $bidangKerja): RedirectResponse
    {
        try {
            $this->service->destroy($bidangKerja);
        } catch (\Exception $e) {
            return back()->withErrors(['general' => $e->getMessage()]);
        }

        return redirect()
            ->route('bidang-kerja.index')
            ->with('success', 'Bidang kerja berhasil dihapus.');
    }
}
