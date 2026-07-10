<?php

namespace App\Repositories;

use App\Models\Transaksi;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class TransaksiRepository
{
    public function paginate(
        int $tahunAnggaranId,
        array $filters = [],
        int $perPage = 25
    ): LengthAwarePaginator {
        return Transaksi::query()
            ->with(['tahunAnggaran', 'bidangKerja', 'transaksable', 'user'])
            ->where('tahun_anggaran_id', $tahunAnggaranId)
            ->when($filters['jenis'] ?? null, fn($q, $v) => $q->where('jenis', $v))
            ->when($filters['bidang_kerja_id'] ?? null, fn($q, $v) => $q->where('bidang_kerja_id', $v))
            ->when($filters['tanggal_dari'] ?? null, fn($q, $v) => $q->whereDate('tanggal', '>=', $v))
            ->when($filters['tanggal_sampai'] ?? null, fn($q, $v) => $q->whereDate('tanggal', '<=', $v))
            ->when($filters['search'] ?? null, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('kode_transaksi', 'like', '%' . $search . '%')
                        ->orWhere('uraian', 'like', '%' . $search . '%');
                });
            })

            ->latest('tanggal') 
            ->paginate($perPage);
    }

    /**
     * Get all records without pagination (for report exports).
     * Reuses the same filter logic as paginate().
     */
    public function getAll(
        int $tahunAnggaranId,
        array $filters = []
    ): Collection {
        return Transaksi::query()
            ->with(['tahunAnggaran', 'bidangKerja', 'transaksable', 'user'])
            ->where('tahun_anggaran_id', $tahunAnggaranId)
            ->when($filters['jenis'] ?? null, fn($q, $v) => $q->where('jenis', $v))
            ->when($filters['bidang_kerja_id'] ?? null, fn($q, $v) => $q->where('bidang_kerja_id', $v))
            ->when($filters['tanggal_dari'] ?? null, fn($q, $v) => $q->whereDate('tanggal', '>=', $v))
            ->when($filters['tanggal_sampai'] ?? null, fn($q, $v) => $q->whereDate('tanggal', '<=', $v))
            ->when($filters['search'] ?? null, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('kode_transaksi', 'like', '%' . $search . '%')
                        ->orWhere('uraian', 'like', '%' . $search . '%');
                });
            })
            ->latest('tanggal')
            ->get();
    }

    public function create(array $data): Transaksi
    {
        /** @var Transaksi $transaksi */
        $transaksi = Transaksi::query()->create($data);

        return $transaksi;
    }

    public function update(Transaksi $transaksi, array $data): Transaksi
    {
        $transaksi->update($data);

        return $transaksi->refresh();
    }

    public function delete(Transaksi $transaksi): void
    {
        $transaksi->delete();
    }

    /**
     * Hitung total transaksi yang masih aktif (non-deleted)
     * untuk satu transaksable tertentu.
     * Dipakai untuk recalculate jumlah_realisasi.
     */
    public function sumByTransaksable(
        string $transaksableType,
        int $transaksableId
    ): float {
        return (float) Transaksi::query()
            ->where('transaksable_type', $transaksableType)
            ->where('transaksable_id', $transaksableId)
            ->sum('jumlah');
    }

    /**
     * Ambil semua transaksable_type+id unik yang terpengaruh oleh
     * perubahan data (dipakai saat update untuk sync dua record sekaligus).
     */
    public function getAffectedTransaksable(Transaksi $transaksi): Collection
    {
        return collect([
            [
                'type' => $transaksi->transaksable_type,
                'id'   => $transaksi->transaksable_id,
            ],
        ])->filter(fn($item) => $item['type'] && $item['id']);
    }
}
