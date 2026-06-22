<?php

namespace App\Services;

use App\Models\KategoriPendapatan;
use App\Models\RencanaPendapatan;
use App\Models\TahunAnggaran;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

class RencanaPendapatanService
{
    // -------------------------------------------------------------------------
    // READ
    // -------------------------------------------------------------------------

    /**
     * Ambil daftar rencana pendapatan untuk tahun anggaran aktif.
     */
    public function getListByTahunAktif(int $perPage = 10): LengthAwarePaginator
    {
        $tahunAktif = $this->getTahunAktifOrFail();

        return RencanaPendapatan::with(['kategoriPendapatan', 'tahunAnggaran'])
            ->where('tahun_anggaran_id', $tahunAktif->id)
            ->latest()
            ->paginate($perPage);
    }

    /**
     * Hitung ringkasan total rencana, realisasi, dan sisa untuk tahun aktif.
     */
    public function getSummaryByTahunAktif(): array
    {
        $tahunAktif = $this->getTahunAktifOrFail();

        $totalRencana = RencanaPendapatan::where('tahun_anggaran_id', $tahunAktif->id)
            ->sum('jumlah_rencana');

        $totalRealisasi = RencanaPendapatan::where('tahun_anggaran_id', $tahunAktif->id)
            ->sum('jumlah_realisasi');

        return [
            'totalRencana'   => $totalRencana,
            'totalRealisasi' => $totalRealisasi,
            'sisaAnggaran'   => $totalRencana - $totalRealisasi,
        ];
    }

    /**
     * Data untuk dropdown create/edit form.
     */
    public function getFormOptions(): array
    {
        return [
            'kategoriList'     => KategoriPendapatan::orderBy('nama')->get(),
            'tahunAnggaranList' => TahunAnggaran::orderByDesc('tahun')->get(),
        ];
    }

    // -------------------------------------------------------------------------
    // WRITE
    // -------------------------------------------------------------------------

    /**
     * Simpan rencana pendapatan baru.
     * Dibungkus DB::transaction karena:
     * - Ada dua langkah: resolve tahun aktif + insert record.
     * - Mencegah data tersimpan setengah jalan jika ada exception setelah
     *   validasi bisnis lolos (misal: constraint DB, trigger, dll).
     *
     * @throws \Exception jika tahun anggaran aktif tidak ditemukan
     */
    public function store(array $validated): RencanaPendapatan
    {
        return DB::transaction(function () use ($validated) {
            $tahunAktif = $this->getTahunAktifOrFail();

            return RencanaPendapatan::create([
                'tahun_anggaran_id'      => $tahunAktif->id,
                'kategori_pendapatan_id' => $validated['kategori_pendapatan_id'],
                'nama_sumber'            => $validated['nama_sumber'],
                'keterangan'             => $validated['keterangan'] ?? null,
                'jumlah_rencana'         => $validated['jumlah_rencana'],
                'jumlah_realisasi'       => 0,
            ]);
        });
    }

    public function update(RencanaPendapatan $pendapatan, array $validated): RencanaPendapatan
    {
        return DB::transaction(function () use ($pendapatan, $validated) {

            $pendapatan->update([
                'kategori_pendapatan_id' => $validated['kategori_pendapatan_id'],
                'nama_sumber'            => $validated['nama_sumber'],
                'keterangan'             => $validated['keterangan'] ?? null,
                'jumlah_rencana'         => $validated['jumlah_rencana'],
            ]);

            return $pendapatan->refresh();
        });
    }

    /**
     * Hapus rencana pendapatan.
     */
    public function destroy(RencanaPendapatan $pendapatan): void
    {
        DB::transaction(function () use ($pendapatan) {
            $pendapatan->delete();
        });
    }

    private function getTahunAktifOrFail(): TahunAnggaran
    {
        $tahunAktif = TahunAnggaran::query()->where('is_aktif', true)->first();

        if (!$tahunAktif) {
            throw new \Exception('Tahun anggaran aktif belum tersedia. Harap konfigurasi terlebih dahulu.');
        }

        return $tahunAktif;
    }
}
