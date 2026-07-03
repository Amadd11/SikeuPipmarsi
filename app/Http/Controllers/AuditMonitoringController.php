<?php

namespace App\Http\Controllers;

use App\Http\Requests\AuditMonitoringStoreRequest;
use App\Http\Requests\AuditMonitoringUpdateRequest;
use App\Models\AuditMonitoring;
use App\Models\TahunAnggaran;
use App\Services\AuditMonitoringService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\View\View;

class AuditMonitoringController extends Controller
{
    public function __construct(
        private readonly AuditMonitoringService $service
    ) {}

    public function index(Request $request): View
    {
        $tahunAnggaranList = TahunAnggaran::query()->orderByDesc('tahun')->get();

        $tahunAnggaranId = $request->integer('tahun') ?: optional(
            TahunAnggaran::query()->where('is_aktif', true)->first()
        )->id;

        // Tidak ada tahun anggaran — tampilkan halaman kosong
        if (! $tahunAnggaranId) {
            $emptyPaginator = new LengthAwarePaginator(new Collection(), 0, 15);

            return view('audit-monitoring.index', [
                'auditList'         => $emptyPaginator,
                'tahunAnggaranList' => $tahunAnggaranList,
                'activeTahun'       => null,
                'filters'           => [],
                'statistik'         => ['total' => 0, 'lengkap' => 0, 'berPic' => 0, 'belumLengkap' => 0],
                'indikatorMutuList' => collect(),
            ])->with('warning', 'Belum ada Tahun Anggaran aktif. Silakan pilih atau buat Tahun Anggaran terlebih dahulu.');
        }

        $filters = array_merge(
            $request->only(['indikator_mutu_id', 'search']),
            ['tahun_anggaran_id' => $tahunAnggaranId]
        );

        $auditList  = $this->service->getList($filters);
        $statistik  = $this->service->getStatistik($tahunAnggaranId);
        $options    = $this->service->getFormOptions();

        return view('audit-monitoring.index', [
            'auditList'         => $auditList,
            'tahunAnggaranList' => $tahunAnggaranList,
            'activeTahun'       => $tahunAnggaranId,
            'filters'           => $filters,
            'statistik'         => $statistik,
            'indikatorMutuList' => $options['indikatorMutuList'],
        ]);
    }

    public function create(): View
    {
        $options = $this->service->getFormOptions();

        return view('audit-monitoring.create', $options);
    }

    public function store(AuditMonitoringStoreRequest $request): RedirectResponse
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
            ->route('audit-monitoring.index')
            ->with('success', 'Data audit monitoring berhasil ditambahkan.');
    }

    public function edit(AuditMonitoring $auditMonitoring): View
    {
        $options = $this->service->getFormOptions();

        return view('audit-monitoring.edit', [
            'auditMonitoring' => $auditMonitoring,
            ...$options,
        ]);
    }

    public function update(AuditMonitoringUpdateRequest $request, AuditMonitoring $auditMonitoring): RedirectResponse
    {
        $validated = $request->validated();

        try {
            $this->service->update($auditMonitoring, $validated);
        } catch (\Exception $e) {
            return back()
                ->withInput()
                ->withErrors(['general' => $e->getMessage()]);
        }

        return redirect()
            ->route('audit-monitoring.index')
            ->with('success', 'Data audit monitoring berhasil diperbarui.');
    }

    public function destroy(AuditMonitoring $auditMonitoring): RedirectResponse
    {
        try {
            $this->service->destroy($auditMonitoring);
        } catch (\Exception $e) {
            return back()->withErrors(['general' => $e->getMessage()]);
        }

        return redirect()
            ->route('audit-monitoring.index')
            ->with('success', 'Data audit monitoring berhasil dihapus.');
    }
}
