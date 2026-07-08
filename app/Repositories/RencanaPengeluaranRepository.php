<?php

namespace App\Repositories;

use App\Models\RencanaPengeluaran;
use Illuminate\Pagination\LengthAwarePaginator;

class RencanaPengeluaranRepository
{
    public function getList(
        int $tahunAnggaranId,
        ?int $bidangKerjaId = null,
        int $perPage = 15
    ): LengthAwarePaginator {
        return RencanaPengeluaran::query()
            ->with(['kategoriPengeluaran', 'indikatorMutu', 'bidangKerja', 'tahunAnggaran'])
            ->where('tahun_anggaran_id', $tahunAnggaranId)
            ->when($bidangKerjaId, fn($q) => $q->where('bidang_kerja_id', $bidangKerjaId))
            ->latest()
            ->paginate($perPage);
    }

    /**
     * Get all records without pagination (for report exports).
     */
    public function getAll(
        int $tahunAnggaranId,
        ?int $bidangKerjaId = null
    ): \Illuminate\Support\Collection {
        return RencanaPengeluaran::query()
            ->with(['kategoriPengeluaran', 'indikatorMutu', 'bidangKerja', 'tahunAnggaran'])
            ->where('tahun_anggaran_id', $tahunAnggaranId)
            ->when($bidangKerjaId, fn($q) => $q->where('bidang_kerja_id', $bidangKerjaId))
            ->latest()
            ->get();
    }

    public function getSummary(
        int $tahunAnggaranId,
        ?int $bidangKerjaId = null
    ): object {
        return RencanaPengeluaran::query()
            ->where('tahun_anggaran_id', $tahunAnggaranId)
            ->when($bidangKerjaId, fn($q) => $q->where('bidang_kerja_id', $bidangKerjaId))
            ->selectRaw('
                COALESCE(SUM(jumlah_anggaran), 0)  AS total_anggaran,
                COALESCE(SUM(jumlah_realisasi), 0) AS total_realisasi
            ')
            ->first();
    }

    public function create(array $data): RencanaPengeluaran
    {
        $pengeluaran = RencanaPengeluaran::query()->create($data);

        return $pengeluaran;
    }

    public function update(
        RencanaPengeluaran $pengeluaran,
        array $data
    ): RencanaPengeluaran {
        $pengeluaran->update($data);

        return $pengeluaran->refresh();
    }

    public function delete(RencanaPengeluaran $pengeluaran): void
    {
        $pengeluaran->delete();
    }
}
