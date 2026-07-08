<?php

namespace App\Services;

use App\Models\IndikatorMutu;
use App\Models\KategoriPengeluaran;
use App\Models\RencanaPengeluaran;
use App\Models\TahunAnggaran;
use App\Repositories\BidangKerjaRepository;
use App\Repositories\RencanaPengeluaranRepository;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class RencanaPengeluaranService
{
    public function __construct(
        protected RencanaPengeluaranRepository $pengeluaranRepo,
        protected BidangKerjaRepository $bidangKerjaRepo,
    ) {}

    public function getList(
        int $tahunAnggaranId,
        ?int $bidangKerjaId = null,
        int $perPage = 15
    ) {
        return $this->pengeluaranRepo->getList($tahunAnggaranId, $bidangKerjaId, $perPage);
    }

    public function getSummaryByBidang(int $tahunAnggaranId, int $bidangKerjaId): array
    {
        return $this->buildSummary(
            $this->pengeluaranRepo->getSummary($tahunAnggaranId, $bidangKerjaId)
        );
    }

    public function getSummaryAll(int $tahunAnggaranId): array
    {
        return $this->buildSummary(
            $this->pengeluaranRepo->getSummary($tahunAnggaranId)
        );
    }

    public function getRingkasanAllBidang(int $tahunAnggaranId): Collection
    {
        return $this->bidangKerjaRepo->getRingkasan($tahunAnggaranId);
    }

    public function getAllBidang(): Collection
    {
        return $this->bidangKerjaRepo->getAll();
    }

    public function getFormOptions(): array
    {
        return [
            'tahunAnggaranList' => TahunAnggaran::query()->orderByDesc('tahun')->get(),
            'bidangKerjaList'   => $this->bidangKerjaRepo->getAll(),
            'kategoriList'      => KategoriPengeluaran::query()->orderBy('nama')->get(),
            'indikatorList'     => IndikatorMutu::query()->orderBy('kode')->get(),
        ];
    }

    public function store(array $validated): RencanaPengeluaran
    {
        return DB::transaction(function () use ($validated): RencanaPengeluaran {
            $pengeluaran = $this->pengeluaranRepo->create([
                'tahun_anggaran_id'       => $validated['tahun_anggaran_id'],
                'bidang_kerja_id'         => $validated['bidang_kerja_id'],
                'kategori_pengeluaran_id' => $validated['kategori_pengeluaran_id'],
                'indikator_mutu_id'       => $validated['indikator_mutu_id'] ?? null,
                'nama_kegiatan'           => $validated['nama_kegiatan'],
                'jumlah_anggaran'         => $validated['jumlah_anggaran'],
                'jumlah_realisasi'        => 0,
            ]);

            foreach ($validated['details'] as $detail) {
                $pengeluaran->details()->create([
                    'uraian'    => $detail['uraian'],
                    'satuan'    => $detail['satuan'],
                    'harga'     => $detail['harga'],
                    'kuantitas' => $detail['kuantitas'],
                ]);
            }

            return $pengeluaran;
        });
    }

    public function update(RencanaPengeluaran $pengeluaran, array $validated): RencanaPengeluaran
    {
        return DB::transaction(function () use ($pengeluaran, $validated): RencanaPengeluaran {
            $pengeluaran = $this->pengeluaranRepo->update($pengeluaran, [
                'tahun_anggaran_id'       => $validated['tahun_anggaran_id'],
                'bidang_kerja_id'         => $validated['bidang_kerja_id'],
                'kategori_pengeluaran_id' => $validated['kategori_pengeluaran_id'],
                'indikator_mutu_id'       => $validated['indikator_mutu_id'] ?? null,
                'nama_kegiatan'           => $validated['nama_kegiatan'],
                'jumlah_anggaran'         => $validated['jumlah_anggaran'],
            ]);

            $pengeluaran->details()->delete();
            foreach ($validated['details'] as $detail) {
                $pengeluaran->details()->create([
                    'uraian'    => $detail['uraian'],
                    'satuan'    => $detail['satuan'],
                    'harga'     => $detail['harga'],
                    'kuantitas' => $detail['kuantitas'],
                ]);
            }

            return $pengeluaran;
        });
    }

    public function destroy(RencanaPengeluaran $pengeluaran): void
    {
        DB::transaction(function () use ($pengeluaran): void {
            $this->pengeluaranRepo->delete($pengeluaran);
        });
    }

    // -------------------------------------------------------------------------
    // PRIVATE HELPERS
    // -------------------------------------------------------------------------

    private function buildSummary(object $result): array
    {
        $totalAnggaran  = (float) $result->total_anggaran;
        $totalRealisasi = (float) $result->total_realisasi;

        return [
            'totalAnggaran'  => $totalAnggaran,
            'totalRealisasi' => $totalRealisasi,
            'sisaAnggaran'   => $totalAnggaran - $totalRealisasi,
        ];
    }
}
