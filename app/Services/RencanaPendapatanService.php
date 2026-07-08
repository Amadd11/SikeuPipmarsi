<?php

namespace App\Services;

use App\Models\KategoriPendapatan;
use App\Models\RencanaPendapatan;
use App\Models\TahunAnggaran;
use App\Repositories\RencanaPendapatanRepository;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

class RencanaPendapatanService
{
    public function __construct(
        protected RencanaPendapatanRepository $repository
    ) {}

    public function getList(
        ?int $tahunId = null,
        int $perPage = 10
    ): LengthAwarePaginator {

        $tahunId = $tahunId ?? $this->getTahunAktifOrFail()->id;

        return $this->repository->getList(
            $tahunId,
            $perPage
        );
    }

    public function getSummary(?int $tahunId = null): array
    {
        $tahunId = $tahunId ?? $this->getTahunAktifOrFail()->id;

        $summary = $this->repository->getSummary(
            $tahunId
        );

        $totalRencana   = (float) ($summary->total_rencana ?? 0);
        $totalRealisasi = (float) ($summary->total_realisasi ?? 0);

        return [
            'totalRencana'   => $totalRencana,
            'totalRealisasi' => $totalRealisasi,
            'sisaAnggaran'   => $totalRencana - $totalRealisasi,
        ];
    }

    public function getFormOptions(): array
    {
        return [
            'kategoriList' => KategoriPendapatan::query()
                ->orderBy('nama')
                ->get(),

            'tahunAnggaranList' => TahunAnggaran::query()
                ->orderByDesc('tahun')
                ->get(),
        ];
    }

    public function store(
        array $validated
    ): RencanaPendapatan {

        return DB::transaction(function () use ($validated) {

            $totalRencana = 0;
            if (!empty($validated['details'])) {
                foreach ($validated['details'] as $detail) {
                    $totalRencana += (float)$detail['jumlah'] * (int)$detail['kuantitas'];
                }
            }

            $pendapatan = $this->repository->create([
                'tahun_anggaran_id'      => $validated['tahun_anggaran_id'],
                'kategori_pendapatan_id' => $validated['kategori_pendapatan_id'],
                'nama_sumber'            => $validated['nama_sumber'],
                'keterangan'             => $validated['keterangan'] ?? null,
                'jumlah_rencana'         => $totalRencana,
                'jumlah_realisasi'       => 0,
            ]);

            if (!empty($validated['details'])) {
                foreach ($validated['details'] as $detail) {
                    $pendapatan->details()->create([
                        'uraian'    => $detail['uraian'],
                        'satuan'    => $detail['satuan'],
                        'jumlah'    => $detail['jumlah'],
                        'kuantitas' => $detail['kuantitas'],
                    ]);
                }
            }

            return $pendapatan;
        });
    }

    public function update(
        RencanaPendapatan $pendapatan,
        array $validated
    ): RencanaPendapatan {

        return DB::transaction(function () use ($pendapatan, $validated) {

            $totalRencana = 0;
            if (!empty($validated['details'])) {
                foreach ($validated['details'] as $detail) {
                    $totalRencana += (float)$detail['jumlah'] * (int)$detail['kuantitas'];
                }
            }

            $pendapatan = $this->repository->update(
                $pendapatan,
                [
                    'kategori_pendapatan_id' => $validated['kategori_pendapatan_id'],
                    'nama_sumber'            => $validated['nama_sumber'],
                    'keterangan'             => $validated['keterangan'] ?? null,
                    'jumlah_rencana'         => $totalRencana,
                ]
            );

            // Re-create details
            $pendapatan->details()->delete();
            if (!empty($validated['details'])) {
                foreach ($validated['details'] as $detail) {
                    $pendapatan->details()->create([
                        'uraian'    => $detail['uraian'],
                        'satuan'    => $detail['satuan'],
                        'jumlah'    => $detail['jumlah'],
                        'kuantitas' => $detail['kuantitas'],
                    ]);
                }
            }

            return $pendapatan;
        });
    }

    public function destroy(
        RencanaPendapatan $pendapatan
    ): void {

        DB::transaction(function () use ($pendapatan) {
            $this->repository->delete($pendapatan);
        });
    }

    private function getTahunAktifOrFail(): TahunAnggaran
    {
        $tahunAktif = TahunAnggaran::query()
            ->where('is_aktif', true)
            ->first();

        if (!$tahunAktif) {
            throw new \Exception(
                'Tahun anggaran aktif belum tersedia. Harap konfigurasi terlebih dahulu.'
            );
        }

        return $tahunAktif;
    }
}
