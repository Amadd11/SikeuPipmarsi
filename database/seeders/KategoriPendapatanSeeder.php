<?php

namespace Database\Seeders;

use App\Models\KategoriPendapatan;
use Illuminate\Database\Seeder;

class KategoriPendapatanSeeder extends Seeder
{
    public function run(): void
    {
        $kategoris = [
            'Iuran Anggota',
            'Dana Pemerintah / Hibah',
            'Donasi / Sponsor',
            'Usaha Organisasi',
            'Lain-lain',
        ];

        foreach ($kategoris as $kategori) {
            KategoriPendapatan::create(['nama' => $kategori]);
        }
    }
}
