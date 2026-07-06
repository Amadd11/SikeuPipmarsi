<?php

namespace App\Services;

use App\Models\StandarTarif;
use App\Repositories\StandarTarifRepository;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class StandarTarifService
{
    public function __construct(
        protected StandarTarifRepository $repository
    ) {}

    public function getList(?string $search = null, int $perPage = 15): LengthAwarePaginator
    {
        return $this->repository->getList($search, $perPage);
    }

    public function store(array $validated, string $filePath): StandarTarif
    {
        return DB::transaction(function () use ($validated, $filePath) {
            return $this->repository->create([
                'kode'     => $validated['kode'] ?? null,
                'nama'     => $validated['nama'],
                'deskripsi' => $validated['deskripsi'] ?? null,
                'file'     => $filePath,
            ]);
        });
    }

    public function update(StandarTarif $standarTarif, array $validated, ?string $newFilePath = null): StandarTarif
    {
        return DB::transaction(function () use ($standarTarif, $validated, $newFilePath) {
            $data = [
                'kode'     => $validated['kode'] ?? null,
                'nama'     => $validated['nama'],
                'deskripsi' => $validated['deskripsi'] ?? null,
            ];

            if ($newFilePath) {
                // Hapus file lama sebelum simpan yang baru
                if ($standarTarif->file) {
                    Storage::disk('public')->delete($standarTarif->file);
                }
                $data['file'] = $newFilePath;
            }

            return $this->repository->update($standarTarif, $data);
        });
    }

    public function destroy(StandarTarif $standarTarif): void
    {
        DB::transaction(function () use ($standarTarif) {

            if ($standarTarif->file) {
                Storage::disk('public')->delete($standarTarif->file);
            }

            $this->repository->delete($standarTarif);
        });
    }
}
