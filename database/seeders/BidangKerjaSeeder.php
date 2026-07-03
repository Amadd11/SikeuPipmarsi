<?php

namespace Database\Seeders;

use App\Models\BidangKerja;
use Illuminate\Database\Seeder;

class BidangKerjaSeeder extends Seeder
{
    public function run(): void
    {
        $bidangs = [
            ['kode' => 'B1', 'nama' => 'Komisi A - Pengembangan Organisasi', 'warna_hex' => '#0e8a72', 'urutan' => 1],
            ['kode' => 'B2', 'nama' => 'Komisi B - Pendidikan dan Pengembangan Profesi', 'warna_hex' => '#1a5fa0', 'urutan' => 2],
            ['kode' => 'B3', 'nama' => 'Komisi C - Penelitian, Pengabdian, dan Publikasi', 'warna_hex' => '#7b2fa8', 'urutan' => 3],
            ['kode' => 'B4', 'nama' => 'Komisi D - Kerja Sama dan ICHA', 'warna_hex' => '#b85010', 'urutan' => 4],
        ];

        foreach ($bidangs as $bidang) {
            BidangKerja::create($bidang);
        }
    }
}
