<?php

namespace App\Services;

use App\Models\RencanaPendapatan;
use App\Models\RencanaPengeluaran;
use App\Models\Transaksi;
use App\Repositories\TransaksiRepository;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class TransaksiService
{
    public function __construct(
        private readonly TransaksiRepository $repository,
    ) {}

    // -------------------------------------------------------------------------
    // READ
    // -------------------------------------------------------------------------

    public function getList(
        int $tahunAnggaranId,
        array $filters = [],
        int $perPage = 15
    ): LengthAwarePaginator {
        return $this->repository->paginate($tahunAnggaranId, $filters, $perPage);
    }

    // -------------------------------------------------------------------------
    // WRITE
    // -------------------------------------------------------------------------

    /**
     * Simpan transaksi baru dan langsung sync realisasi ke rencana terkait.
     *
     * @throws \Exception
     */
    public function store(array $validated): Transaksi
    {
        return DB::transaction(function () use ($validated): Transaksi {
            $transaksi = $this->repository->create([
                'kode_transaksi'    => $this->generateKode($validated['jenis']),
                'tahun_anggaran_id' => $validated['tahun_anggaran_id'],
                'tanggal'           => $validated['tanggal'],
                'jenis'             => $validated['jenis'],
                'uraian'            => $validated['uraian'],
                'bidang_kerja_id'   => $validated['bidang_kerja_id'] ?? null,
                'transaksable_type' => $validated['transaksable_type'],
                'transaksable_id'   => $validated['transaksable_id'],
                'jumlah'            => $validated['jumlah'],
                'nomor_bukti'       => $validated['nomor_bukti'] ?? null,
                'file_bukti'        => $validated['file_bukti'] ?? null,
                'user_id'           => auth()->id(),
            ]);

            // Sync realisasi ke rencana terkait
            $this->syncRealisasi(
                $transaksi->transaksable_type,
                $transaksi->transaksable_id
            );

            return $transaksi;
        });
    }

    /**
     * Update transaksi.
     * Jika transaksable berubah (pindah rencana), sync keduanya.
     *
     * @throws \Exception
     */
    public function update(Transaksi $transaksi, array $validated): Transaksi
    {
        return DB::transaction(function () use ($transaksi, $validated): Transaksi {
            // Simpan referensi lama sebelum diupdate
            $oldType = $transaksi->transaksable_type;
            $oldId   = $transaksi->transaksable_id;

            $updated = $this->repository->update($transaksi, [
                'tahun_anggaran_id' => $validated['tahun_anggaran_id'],
                'tanggal'           => $validated['tanggal'],
                'jenis'             => $validated['jenis'],
                'uraian'            => $validated['uraian'],
                'bidang_kerja_id'   => $validated['bidang_kerja_id'] ?? null,
                'transaksable_type' => $validated['transaksable_type'],
                'transaksable_id'   => $validated['transaksable_id'],
                'jumlah'            => $validated['jumlah'],
                'nomor_bukti'       => $validated['nomor_bukti'] ?? null,
                'file_bukti'        => $validated['file_bukti'] ?? null,
            ]);

            // Sync rencana lama (kalau transaksable berubah)
            $this->syncRealisasi($oldType, $oldId);

            // Sync rencana baru
            if (
                $validated['transaksable_type'] !== $oldType ||
                (int) $validated['transaksable_id'] !== (int) $oldId
            ) {
                $this->syncRealisasi(
                    $validated['transaksable_type'],
                    $validated['transaksable_id']
                );
            }

            return $updated;
        });
    }

    /**
     * Hapus transaksi (soft delete) dan recalculate realisasi rencana terkait.
     *
     * @throws \Exception
     */
    public function destroy(Transaksi $transaksi): void
    {
        DB::transaction(function () use ($transaksi): void {
            $type = $transaksi->transaksable_type;
            $id   = $transaksi->transaksable_id;

            $this->repository->delete($transaksi);

            // Recalculate realisasi setelah transaksi dihapus
            $this->syncRealisasi($type, $id);
        });
    }

    // -------------------------------------------------------------------------
    // PRIVATE HELPERS
    // -------------------------------------------------------------------------

    /**
     * Recalculate jumlah_realisasi pada rencana terkait
     * dengan menjumlahkan SELURUH transaksi aktif (non-deleted).
     *
     * Approach ini (SUM dari transaksi) lebih aman daripada increment/decrement
     * karena tidak akan drift walau ada race condition atau partial failure.
     */
    private function syncRealisasi(
        ?string $transaksableType,
        ?int $transaksableId
    ): void {
        if (!$transaksableType || !$transaksableId) {
            return;
        }

        $totalRealisasi = $this->repository->sumByTransaksable(
            $transaksableType,
            $transaksableId
        );

        match ($transaksableType) {
            RencanaPendapatan::class => RencanaPendapatan::query()
                ->where('id', $transaksableId)
                ->update(['jumlah_realisasi' => $totalRealisasi]),

            RencanaPengeluaran::class => RencanaPengeluaran::query()
                ->where('id', $transaksableId)
                ->update(['jumlah_realisasi' => $totalRealisasi]),

            default => null,
        };
    }

    private function generateKode(string $jenis): string
    {
        // FIX: value yang dipakai di seluruh aplikasi adalah
        // 'pemasukan' / 'pengeluaran', bukan 'masuk'.
        $prefix = $jenis === 'pemasukan' ? 'M' : 'K';

        do {
            $kode = sprintf(
                'TRX-%s-%s-%s',
                $prefix,
                now()->format('Ymd'),
                strtoupper(Str::random(6))
            );
        } while (Transaksi::query()->where('kode_transaksi', $kode)->exists());

        return $kode;
    }
}
