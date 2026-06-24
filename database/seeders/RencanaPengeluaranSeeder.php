<?php

namespace Database\Seeders;

use App\Models\BidangKerja;
use App\Models\IndikatorMutu;
use App\Models\KategoriPengeluaran;
use App\Models\RencanaPengeluaran;
use App\Models\TahunAnggaran;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RencanaPengeluaranSeeder extends Seeder
{
    public function run(): void
    {
        $tahunAktif = TahunAnggaran::where('is_aktif', true)->first();
        $bidangKerja = BidangKerja::first();
        $kategori = KategoriPengeluaran::first();
        $indikator = IndikatorMutu::first();

        if (
            ! $tahunAktif ||
            ! $bidangKerja ||
            ! $kategori ||
            ! $indikator
        ) {
            $this->command->warn('Pastikan data master sudah tersedia.');
            return;
        }

        $data = [
            [
                'kategori_pengeluaran_id' => 1,
                'nama_kegiatan' => 'Pelatihan Pengurus Komisariat',
                'jumlah_anggaran' => 5000000,
                'jumlah_realisasi' => 0,
                'keterangan' => 'Pelatihan peningkatan kapasitas pengurus.',
            ],
            [
                'kategori_pengeluaran_id' => 2,
                'nama_kegiatan' => 'Rapat Kerja Tahunan',
                'jumlah_anggaran' => 3000000,
                'jumlah_realisasi' => 0,
                'keterangan' => 'Penyusunan program kerja tahunan.',
            ],
            [
                'kategori_pengeluaran_id' => 3,
                'nama_kegiatan' => 'Seminar Kesehatan Mahasiswa',
                'jumlah_anggaran' => 7500000,
                'jumlah_realisasi' => 0,
                'keterangan' => 'Kegiatan seminar kesehatan dan edukasi.',
            ],
            [
                'kategori_pengeluaran_id' => 4,
                'nama_kegiatan' => 'Pengadaan ATK Organisasi',
                'jumlah_anggaran' => 2000000,
                'jumlah_realisasi' => 0,
                'keterangan' => 'Pengadaan alat tulis kantor.',
            ],
            [
                'kategori_pengeluaran_id' => 5,
                'nama_kegiatan' => 'Bakti Sosial',
                'jumlah_anggaran' => 4000000,
                'jumlah_realisasi' => 0,
                'keterangan' => 'Kegiatan pengabdian kepada masyarakat.',
            ],
        ];

        foreach ($data as $item) {
            RencanaPengeluaran::create([
                'tahun_anggaran_id'      => $tahunAktif->id,
                'bidang_kerja_id'        => $bidangKerja->id,
                'kategori_pengeluaran_id' => $item['kategori_pengeluaran_id'],
                'indikator_mutu_id'      => $indikator->id,
                'nama_kegiatan'          => $item['nama_kegiatan'],
                'jumlah_anggaran'        => $item['jumlah_anggaran'],
                'jumlah_realisasi'       => $item['jumlah_realisasi'],
                'keterangan'             => $item['keterangan'],
            ]);
        }
    }
}
