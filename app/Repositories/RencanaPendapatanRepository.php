<?php

namespace App\Repositories;

use App\Models\RencanaPendapatan;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use stdClass;

class RencanaPendapatanRepository
{
    public function getList(
        int $tahunAnggaranId,
        int $perPage = 10
    ): LengthAwarePaginator {

        return RencanaPendapatan::query()
            ->where('tahun_anggaran_id', $tahunAnggaranId)
            ->with([
                'kategoriPendapatan',
                'tahunAnggaran',
            ])
            ->latest()
            ->paginate($perPage);
    }

    /**
     * Get all records without pagination (for report exports).
     */
    public function getAll(int $tahunAnggaranId): Collection
    {
        return RencanaPendapatan::query()
            ->where('tahun_anggaran_id', $tahunAnggaranId)
            ->with(['kategoriPendapatan', 'tahunAnggaran'])
            ->latest()
            ->get();
    }

    public function getSummary(
        int $tahunAnggaranId
    ): object {

        return RencanaPendapatan::query()
            ->where('tahun_anggaran_id', $tahunAnggaranId)
            ->selectRaw('
                COALESCE(SUM(jumlah_rencana), 0) AS total_rencana,
                COALESCE(SUM(jumlah_realisasi), 0) AS total_realisasi
            ')
            ->first();
    }

    public function create(
        array $data
    ): RencanaPendapatan {

        return RencanaPendapatan::query()->create($data);
    }

    public function update(
        RencanaPendapatan $pendapatan,
        array $data
    ): RencanaPendapatan {

        $pendapatan->update($data);

        return $pendapatan->refresh();
    }

    public function delete(
        RencanaPendapatan $pendapatan
    ): void {

        $pendapatan->delete();
    }
}
