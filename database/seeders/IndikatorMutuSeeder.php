<?php

namespace Database\Seeders;

use App\Models\BidangKerja;
use App\Models\IndikatorMutu;
use Illuminate\Database\Seeder;

class IndikatorMutuSeeder extends Seeder
{
    public function run(): void
    {
        $indikators = [
            // B1
            ['kode' => 'B1.1', 'bidang_kode' => 'B1', 'nama' => 'Struktur organisasi tersusun dan disahkan', 'target' => '100%', 'periode' => 'Tahunan'],
            ['kode' => 'B1.2', 'bidang_kode' => 'B1', 'nama' => 'Tingkat kehadiran rapat pengurus ≥ 80%', 'target' => '≥ 80%', 'periode' => 'Per Rapat'],
            ['kode' => 'B1.3', 'bidang_kode' => 'B1', 'nama' => 'Program kerja tahunan tersusun', 'target' => '100%', 'periode' => 'Tahunan'],
            ['kode' => 'B1.4', 'bidang_kode' => 'B1', 'nama' => 'Rekrutmen anggota baru ≥ 10%/tahun', 'target' => '≥ 10%', 'periode' => 'Tahunan'],
            ['kode' => 'B1.5', 'bidang_kode' => 'B1', 'nama' => 'Database anggota terupdate (akurasi ≥ 95%)', 'target' => '≥ 95%', 'periode' => 'Semesteran'],
            ['kode' => 'B1.6', 'bidang_kode' => 'B1', 'nama' => 'Musyawarah/Kongres terlaksana sesuai AD/ART', 'target' => 'Terlaksana', 'periode' => 'Periodik'],
            ['kode' => 'B1.7', 'bidang_kode' => 'B1', 'nama' => 'LPJ tersusun dan diserahkan tepat waktu', 'target' => 'Tepat Waktu', 'periode' => 'Tahunan'],

            // B2
            ['kode' => 'B2.1', 'bidang_kode' => 'B2', 'nama' => 'Min. 2 pelatihan/workshop per tahun', 'target' => 'Min. 2', 'periode' => 'Tahunan'],
            ['kode' => 'B2.2', 'bidang_kode' => 'B2', 'nama' => 'Kepuasan peserta pelatihan ≥ 80', 'target' => '≥ 80', 'periode' => 'Per Kegiatan'],
            ['kode' => 'B2.3', 'bidang_kode' => 'B2', 'nama' => '≥ 70% peserta lulus uji kompetensi', 'target' => '≥ 70%', 'periode' => 'Per Kegiatan'],
            ['kode' => 'B2.4', 'bidang_kode' => 'B2', 'nama' => 'Modul/kurikulum diperbarui min. 1x/tahun', 'target' => 'Min. 1x', 'periode' => 'Tahunan'],
            ['kode' => 'B2.5', 'bidang_kode' => 'B2', 'nama' => '≥ 60% anggota mendapat pendampingan pendidikan', 'target' => '≥ 60%', 'periode' => 'Tahunan'],
            ['kode' => 'B2.6', 'bidang_kode' => 'B2', 'nama' => 'Min. 1 MoU dengan institusi pendidikan aktif', 'target' => 'Min. 1', 'periode' => 'Tahunan'],

            // B3
            ['kode' => 'B3.1', 'bidang_kode' => 'B3', 'nama' => 'Min. 1 penelitian terdokumentasi/tahun', 'target' => 'Min. 1', 'periode' => 'Tahunan'],
            ['kode' => 'B3.2', 'bidang_kode' => 'B3', 'nama' => 'Min. 2 kegiatan pengabdian masyarakat/tahun', 'target' => 'Min. 2', 'periode' => 'Tahunan'],
            ['kode' => 'B3.3', 'bidang_kode' => 'B3', 'nama' => '≥ 30% anggota terlibat kegiatan riset', 'target' => '≥ 30%', 'periode' => 'Tahunan'],
            ['kode' => 'B3.4', 'bidang_kode' => 'B3', 'nama' => 'Laporan dampak pengabmas tersusun pasca kegiatan', 'target' => 'Tersusun', 'periode' => 'Per Kegiatan'],
            ['kode' => 'B3.5', 'bidang_kode' => 'B3', 'nama' => 'Min. 1 proposal diajukan ke sumber dana eksternal', 'target' => 'Min. 1', 'periode' => 'Tahunan'],
            ['kode' => 'B3.6', 'bidang_kode' => 'B3', 'nama' => '100% kegiatan terdokumentasi resmi', 'target' => '100%', 'periode' => 'Per Kegiatan'],

            // B4
            ['kode' => 'B4.1', 'bidang_kode' => 'B4', 'nama' => 'Min. 2 edisi buletin terbit/tahun', 'target' => 'Min. 2', 'periode' => 'Semesteran'],
            ['kode' => 'B4.2', 'bidang_kode' => 'B4', 'nama' => 'Konten website/medsos diperbarui ≥ 2x/bulan', 'target' => '≥ 2x', 'periode' => 'Bulanan'],
            ['kode' => 'B4.3', 'bidang_kode' => 'B4', 'nama' => 'Min. 4 artikel terpublikasi per tahun', 'target' => 'Min. 4', 'periode' => 'Tahunan'],
            ['kode' => 'B4.4', 'bidang_kode' => 'B4', 'nama' => '100% kegiatan utama terpublikasikan', 'target' => '100%', 'periode' => 'Per Kegiatan'],
            ['kode' => 'B4.5', 'bidang_kode' => 'B4', 'nama' => 'Engagement rate medsos ≥ 3%', 'target' => '≥ 3%', 'periode' => 'Bulanan'],
            ['kode' => 'B4.6', 'bidang_kode' => 'B4', 'nama' => 'Min. 1 produk edukasi per kuartal', 'target' => 'Min. 1', 'periode' => 'Kuartalan'],

            // B5
            ['kode' => 'B5.1', 'bidang_kode' => 'B5', 'nama' => 'Min. 3 MoU/perjanjian kerjasama aktif', 'target' => 'Min. 3', 'periode' => 'Tahunan'],
            ['kode' => 'B5.2', 'bidang_kode' => 'B5', 'nama' => '≥ 70% MoU terealisasi dalam program nyata', 'target' => '≥ 70%', 'periode' => 'Tahunan'],
            ['kode' => 'B5.3', 'bidang_kode' => 'B5', 'nama' => 'Hadir min. 2 forum nasional/tahun', 'target' => 'Min. 2', 'periode' => 'Tahunan'],
            ['kode' => 'B5.4', 'bidang_kode' => 'B5', 'nama' => 'Min. 1 kegiatan bersama pemerintah/tahun', 'target' => 'Min. 1', 'periode' => 'Tahunan'],
            ['kode' => 'B5.5', 'bidang_kode' => 'B5', 'nama' => 'Kepuasan mitra kerjasama ≥ 75', 'target' => '≥ 75', 'periode' => 'Tahunan'],
            ['kode' => 'B5.6', 'bidang_kode' => 'B5', 'nama' => 'Laporan kerjasama terarsip tiap semester', 'target' => 'Terarsip', 'periode' => 'Semesteran'],
        ];

        $bidangs = BidangKerja::pluck('id', 'kode');

        foreach ($indikators as $indikator) {
            if (! isset($bidangs[$indikator['bidang_kode']])) {
                continue;
            }

            IndikatorMutu::updateOrCreate(
                ['kode' => $indikator['kode']],
                [
                    'bidang_kerja_id' => $bidangs[$indikator['bidang_kode']],
                    'nama'            => $indikator['nama'],
                    'target'          => $indikator['target'],
                    'periode'         => $indikator['periode'],
                    'status'          => 'belum',
                    'catatan'         => null,
                ]
            );
        }
    }
}