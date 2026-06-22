<?php

namespace Database\Seeders;

use App\Models\BidangKerja;
use Illuminate\Database\Seeder;

class BidangKerjaSeeder extends Seeder
{
    public function run(): void
    {
        $bidangs = [
            ['kode' => 'B1', 'nama' => 'Pengembangan Organisasi', 'warna_hex' => '#0e8a72', 'urutan' => 1],
            ['kode' => 'B2', 'nama' => 'Pendidikan', 'warna_hex' => '#1a5fa0', 'urutan' => 2],
            ['kode' => 'B3', 'nama' => 'Penelitian & Pengabmas', 'warna_hex' => '#7b2fa8', 'urutan' => 3],
            ['kode' => 'B4', 'nama' => 'Publikasi', 'warna_hex' => '#b85010', 'urutan' => 4],
            ['kode' => 'B5', 'nama' => 'Kerjasama Antar Lembaga', 'warna_hex' => '#a0272e', 'urutan' => 5],
        ];

        foreach ($bidangs as $bidang) {
            BidangKerja::create($bidang);
        }
    }
}
