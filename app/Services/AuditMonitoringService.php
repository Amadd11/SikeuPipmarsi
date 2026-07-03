<?php

namespace App\Services;

use App\Models\AuditMonitoring;
use App\Models\IndikatorMutu;
use App\Models\TahunAnggaran;
use App\Repositories\AuditMonitoringRepository;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

class AuditMonitoringService
{
    public function __construct(
        protected AuditMonitoringRepository $repository
    ) {}

    public function getList(array $filters = [], int $perPage = 15): LengthAwarePaginator
    {
        return $this->repository->getList(
            tahunAnggaranId: $filters['tahun_anggaran_id'] ?? null,
            indikatorMutuId: $filters['indikator_mutu_id'] ?? null,
            search:          $filters['search'] ?? null,
            perPage:         $perPage,
        );
    }

    public function getFormOptions(): array
    {
        return [
            'indikatorMutuList' => IndikatorMutu::query()
                ->with('bidangKerja')
                ->orderBy('kode')
                ->get(),

            'tahunAnggaranList' => TahunAnggaran::query()
                ->orderByDesc('tahun')
                ->get(),

            'tahunAktif' => TahunAnggaran::query()
                ->where('is_aktif', true)
                ->first(),
        ];
    }

    public function getStatistik(?int $tahunAnggaranId = null): array
    {
        return $this->repository->getStatistik($tahunAnggaranId);
    }

    public function store(array $validated): AuditMonitoring
    {
        return DB::transaction(function () use ($validated) {
            return $this->repository->create([
                'indikator_mutu_id'    => $validated['indikator_mutu_id'],
                'tahun_anggaran_id'    => $validated['tahun_anggaran_id'],
                'uraian_pelaksanaan'   => $validated['uraian_pelaksanaan'] ?? null,
                'kendala'              => $validated['kendala'] ?? null,
                'faktor_pendukung'     => $validated['faktor_pendukung'] ?? null,
                'perbaikan'            => $validated['perbaikan'] ?? null,
                'rencana_tindak_lanjut' => $validated['rencana_tindak_lanjut'] ?? null,
                'pic'                  => $validated['pic'] ?? null,
                'tanggal_penyelesaian' => $validated['tanggal_penyelesaian'] ?? null,
            ]);
        });
    }

    public function update(AuditMonitoring $auditMonitoring, array $validated): AuditMonitoring
    {
        return DB::transaction(function () use ($auditMonitoring, $validated) {
            return $this->repository->update($auditMonitoring, [
                'indikator_mutu_id'    => $validated['indikator_mutu_id'],
                'tahun_anggaran_id'    => $validated['tahun_anggaran_id'],
                'uraian_pelaksanaan'   => $validated['uraian_pelaksanaan'] ?? null,
                'kendala'              => $validated['kendala'] ?? null,
                'faktor_pendukung'     => $validated['faktor_pendukung'] ?? null,
                'perbaikan'            => $validated['perbaikan'] ?? null,
                'rencana_tindak_lanjut' => $validated['rencana_tindak_lanjut'] ?? null,
                'pic'                  => $validated['pic'] ?? null,
                'tanggal_penyelesaian' => $validated['tanggal_penyelesaian'] ?? null,
            ]);
        });
    }

    public function destroy(AuditMonitoring $auditMonitoring): void
    {
        DB::transaction(function () use ($auditMonitoring) {
            $this->repository->delete($auditMonitoring);
        });
    }
}
