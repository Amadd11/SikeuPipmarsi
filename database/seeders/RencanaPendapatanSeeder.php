<?php

namespace Database\Seeders;

use App\Models\RencanaPendapatan;
use App\Models\TahunAnggaran;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RencanaPendapatanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $tahunAktif = TahunAnggaran::where('is_aktif', true)->first();

        if (! $tahunAktif) {
            return;
        }

        $data = [
            [
                'kategori_pendapatan_id' => 1,
                'nama_sumber' => 'Iuran Anggota Aktif',
                'jumlah_rencana' => 24000000,
                'keterangan' => 'Iuran bulanan anggota aktif @Rp 200.000 × 120 anggota',
            ],
            [
                'kategori_pendapatan_id' => 2,
                'nama_sumber' => 'Dana Hibah Pemerintah / Dinas Terkait',
                'jumlah_rencana' => 50000000,
                'keterangan' => 'Bantuan operasional dari dinas/instansi terkait',
            ],
            [
                'kategori_pendapatan_id' => 3,
                'nama_sumber' => 'Donasi dan Sponsor Kegiatan',
                'jumlah_rencana' => 15000000,
                'keterangan' => 'Donasi dari sponsor untuk kegiatan tertentu',
            ],
            [
                'kategori_pendapatan_id' => 4,
                'nama_sumber' => 'Usaha Organisasi (Jasa & Produk)',
                'jumlah_rencana' => 10000000,
                'keterangan' => 'Pendapatan dari unit usaha organisasi',
            ],
            [
                'kategori_pendapatan_id' => 4,
                'nama_sumber' => 'Pendaftaran Pelatihan & Workshop',
                'jumlah_rencana' => 8000000,
                'keterangan' => 'Biaya pendaftaran peserta pelatihan',
            ],
        ];

        foreach ($data as $item) {
            RencanaPendapatan::create([
                'tahun_anggaran_id' => $tahunAktif->id,
                'kategori_pendapatan_id' => $item['kategori_pendapatan_id'],
                'nama_sumber' => $item['nama_sumber'],
                'jumlah_rencana' => $item['jumlah_rencana'],
                'jumlah_realisasi' => 0,
                'keterangan' => $item['keterangan'],
            ]);
        }
    }
}
