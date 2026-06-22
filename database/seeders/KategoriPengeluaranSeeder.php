<?php

namespace Database\Seeders;

use App\Models\KategoriPengeluaran;
use Illuminate\Database\Seeder;

class KategoriPengeluaranSeeder extends Seeder
{
    public function run(): void
    {
        $kategoris = [
            'Rapat & Koordinasi',
            'Pelatihan & Workshop',
            'Penelitian',
            'Pengabdian Masyarakat',
            'Publikasi & Dokumentasi',
            'Kerjasama & MoU',
            'Honorarium',
            'Administrasi & ATK',
            'Konsumsi',
            'Transportasi',
            'Lain-lain',
        ];

        foreach ($kategoris as $kategori) {
            KategoriPengeluaran::create(['nama' => $kategori]);
        }
    }
}
