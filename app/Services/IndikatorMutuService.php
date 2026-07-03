<?php

namespace App\Services;

use App\Models\BidangKerja;
use App\Models\IndikatorMutu;
use App\Repositories\IndikatorMutuRepository;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class IndikatorMutuService
{
    public function __construct(
        protected IndikatorMutuRepository $repository
    ) {}

    public function getList(
        ?int $bidangKerjaId = null,
        int $perPage = 15
    ): LengthAwarePaginator {

        return $this->repository->getList($bidangKerjaId, $perPage);
    }

    public function getAll(): Collection
    {
        return $this->repository->getAll();
    }

    public function getRingkasanByBidang(): Collection
    {
        return $this->repository->getRingkasanByBidang();
    }

    public function getFormOptions(): array
    {
        return [
            'bidangKerjaList' => BidangKerja::query()
                ->orderBy('nama')
                ->get(),

            'statusOptions' => [
                'belum'          => 'Belum',
                'proses'         => 'Proses',
                'tercapai'       => 'Tercapai',
                'tidak tercapai' => 'Tidak Tercapai',
            ],
        ];
    }

    public function store(array $validated): IndikatorMutu
    {
        return DB::transaction(function () use ($validated) {
            return $this->repository->create([
                'kode'           => $validated['kode'],
                'bidang_kerja_id' => $validated['bidang_kerja_id'],
                'nama'           => $validated['nama'],
                'target'         => $validated['target'],
                'periode'        => $validated['periode'] ?? null,
                'status'         => $validated['status'] ?? 'belum',
                'catatan'        => $validated['catatan'] ?? null,
            ]);
        });
    }

    public function update(IndikatorMutu $indikator, array $validated): IndikatorMutu
    {
        return DB::transaction(function () use ($indikator, $validated) {
            return $this->repository->update($indikator, [
                'kode'            => $validated['kode'],
                'bidang_kerja_id' => $validated['bidang_kerja_id'],
                'nama'            => $validated['nama'],
                'target'          => $validated['target'],
                'periode'         => $validated['periode'] ?? null,
                'status'          => $validated['status'] ?? $indikator->status,
                'catatan'         => $validated['catatan'] ?? null,
            ]);
        });
    }

    public function destroy(IndikatorMutu $indikator): void
    {
        DB::transaction(function () use ($indikator) {
            $this->repository->delete($indikator);
        });
    }
}
