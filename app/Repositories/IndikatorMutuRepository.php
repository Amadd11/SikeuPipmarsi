<?php

namespace App\Repositories;

use App\Models\BidangKerja;
use App\Models\IndikatorMutu;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class IndikatorMutuRepository
{
    public function getList(
        ?int $bidangKerjaId = null,
        int $perPage = 15
    ): LengthAwarePaginator {

        return IndikatorMutu::query()
            ->with('bidangKerja')
            ->when($bidangKerjaId, fn ($q) => $q->where('bidang_kerja_id', $bidangKerjaId))
            ->orderBy('kode')
            ->paginate($perPage);
    }

    public function getAll(): Collection
    {
        return IndikatorMutu::query()
            ->with('bidangKerja')
            ->orderBy('kode')
            ->get();
    }

    public function findById(int $id): IndikatorMutu
    {
        return IndikatorMutu::query()->findOrFail($id);
    }

    public function create(array $data): IndikatorMutu
    {
        return IndikatorMutu::query()->create($data);
    }

    public function update(IndikatorMutu $indikator, array $data): IndikatorMutu
    {
        $indikator->update($data);

        return $indikator->refresh();
    }

    public function delete(IndikatorMutu $indikator): void
    {
        $indikator->delete();
    }

    public function getRingkasanByBidang(): Collection
    {
        return BidangKerja::query()
            ->withCount(['indikatorMutu as total_indikator'])
            ->withCount(['indikatorMutu as total_tercapai' => fn ($q) => $q->where('status', 'tercapai')])
            ->withCount(['indikatorMutu as total_proses'   => fn ($q) => $q->where('status', 'proses')])
            ->withCount(['indikatorMutu as total_belum'    => fn ($q) => $q->where('status', 'belum')])
            ->orderBy('nama')
            ->get()
            ->map(function (BidangKerja $bidang) {
                $bidang->persen_tercapai = $bidang->total_indikator > 0
                    ? round(($bidang->total_tercapai / $bidang->total_indikator) * 100, 2)
                    : 0;

                return $bidang;
            });
    }
}
