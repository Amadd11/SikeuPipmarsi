<?php

namespace App\Services;

use App\Models\BidangKerja;
use App\Repositories\BidangKerjaRepository;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

class BidangKerjaService
{
    public function __construct(
        protected BidangKerjaRepository $repository
    ) {}

    public function getList(?string $search = null, int $perPage = 15): LengthAwarePaginator
    {
        return $this->repository->getPaginated($search, $perPage);
    }

    public function store(array $validated): BidangKerja
    {
        return DB::transaction(function () use ($validated) {
            return $this->repository->create([
                'kode'      => strtoupper($validated['kode']),
                'nama'      => $validated['nama'],
                'deskripsi' => $validated['deskripsi'] ?? null,
                'warna_hex' => $validated['warna_hex'] ?? null,
                'urutan'    => $validated['urutan'] ?? 0,
            ]);
        });
    }

    public function update(BidangKerja $bidangKerja, array $validated): BidangKerja
    {
        return DB::transaction(function () use ($bidangKerja, $validated) {
            return $this->repository->update($bidangKerja, [
                'kode'      => strtoupper($validated['kode']),
                'nama'      => $validated['nama'],
                'deskripsi' => $validated['deskripsi'] ?? null,
                'warna_hex' => $validated['warna_hex'] ?? null,
                'urutan'    => $validated['urutan'] ?? 0,
            ]);
        });
    }

    public function destroy(BidangKerja $bidangKerja): void
    {
        DB::transaction(function () use ($bidangKerja) {
            $this->repository->delete($bidangKerja);
        });
    }
}
