<?php

namespace App\Repositories;

use App\Models\AuditMonitoring;
use App\Models\TahunAnggaran;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class AuditMonitoringRepository
{
    public function getList(
        ?int $tahunAnggaranId = null,
        ?int $indikatorMutuId = null,
        ?string $search = null,
        int $perPage = 10
    ): LengthAwarePaginator {

        return AuditMonitoring::query()
            ->with(['indikatorMutu.bidangKerja', 'tahunAnggaran'])
            ->when($tahunAnggaranId, fn ($q) => $q->where('tahun_anggaran_id', $tahunAnggaranId))
            ->when($indikatorMutuId, fn ($q) => $q->where('indikator_mutu_id', $indikatorMutuId))
            ->when($search, fn ($q) => $q->where(function ($q2) use ($search) {
                $q2->where('pic', 'like', "%{$search}%")
                   ->orWhere('uraian_pelaksanaan', 'like', "%{$search}%")
                   ->orWhereHas('indikatorMutu', fn ($q3) => $q3->where('nama', 'like', "%{$search}%"));
            }))
            ->latest()
            ->paginate($perPage);
    }

    public function findById(int $id): AuditMonitoring
    {
        return AuditMonitoring::query()
            ->with(['indikatorMutu.bidangKerja', 'tahunAnggaran'])
            ->findOrFail($id);
    }

    public function create(array $data): AuditMonitoring
    {
        return AuditMonitoring::query()->create($data);
    }

    public function update(AuditMonitoring $auditMonitoring, array $data): AuditMonitoring
    {
        $auditMonitoring->update($data);

        return $auditMonitoring->refresh();
    }

    public function delete(AuditMonitoring $auditMonitoring): void
    {
        $auditMonitoring->delete();
    }

    /**
     * Statistik jumlah audit per status (berdasarkan kelengkapan data).
     */
    public function getStatistik(?int $tahunAnggaranId = null): array
    {
        $query = AuditMonitoring::query()
            ->when($tahunAnggaranId, fn ($q) => $q->where('tahun_anggaran_id', $tahunAnggaranId));

        $total      = (clone $query)->count();
        $lengkap    = (clone $query)->whereNotNull('tanggal_penyelesaian')->count();
        $berPic     = (clone $query)->whereNotNull('pic')->count();
        $belumLengkap = $total - $lengkap;

        return compact('total', 'lengkap', 'berPic', 'belumLengkap');
    }
}
