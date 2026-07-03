<?php

namespace App\Repositories;

use App\Models\StandarTarif;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Storage;

class StandarTarifRepository
{
    public function getList(
        ?string $search = null,
        int $perPage = 15
    ): LengthAwarePaginator {

        return StandarTarif::query()
            ->when($search, fn ($q) => $q->where(function ($q2) use ($search) {
                $q2->where('nama', 'like', "%{$search}%")
                   ->orWhere('kode', 'like', "%{$search}%")
                   ->orWhere('deskripsi', 'like', "%{$search}%");
            }))
            ->latest()
            ->paginate($perPage);
    }

    public function findById(int $id): StandarTarif
    {
        return StandarTarif::query()->findOrFail($id);
    }

    public function create(array $data): StandarTarif
    {
        return StandarTarif::query()->create($data);
    }

    public function update(StandarTarif $standarTarif, array $data): StandarTarif
    {
        $standarTarif->update($data);

        return $standarTarif->refresh();
    }

    public function delete(StandarTarif $standarTarif): void
    {
        // Hapus file PDF dari storage sebelum menghapus record
        if ($standarTarif->file) {
            Storage::disk('public')->delete($standarTarif->file);
        }

        $standarTarif->delete();
    }
}
