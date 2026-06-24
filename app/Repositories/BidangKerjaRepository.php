<?php

namespace App\Repositories;

use App\Models\BidangKerja;
use Illuminate\Support\Collection;

class BidangKerjaRepository
{
    public function getAll(): Collection
    {
        return BidangKerja::query()->orderBy('nama')->get();
    }

    public function getRingkasan(int $tahunAnggaranId): Collection
    {
        return BidangKerja::query()
            ->withSum(
                ['rencanaPengeluaran as total_anggaran' => fn($q) => $q->where('tahun_anggaran_id', $tahunAnggaranId)],
                'jumlah_anggaran'
            )
            ->withSum(
                ['rencanaPengeluaran as total_realisasi' => fn($q) => $q->where('tahun_anggaran_id', $tahunAnggaranId)],
                'jumlah_realisasi'
            )
            ->orderBy('nama')
            ->get()
            ->map(function (BidangKerja $bidang) {
                $bidang->total_anggaran  = (float) ($bidang->total_anggaran ?? 0);
                $bidang->total_realisasi = (float) ($bidang->total_realisasi ?? 0);
                $bidang->sisa_anggaran   = $bidang->total_anggaran - $bidang->total_realisasi;
                $bidang->persen          = $bidang->total_anggaran > 0
                    ? round(($bidang->total_realisasi / $bidang->total_anggaran) * 100, 2)
                    : 0;

                return $bidang;
            });
    }
}
