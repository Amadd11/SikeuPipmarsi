<?php

namespace Database\Seeders;

use App\Models\TahunAnggaran;
use Illuminate\Database\Seeder;

class TahunAnggaranSeeder extends Seeder
{
    public function run(): void
    {
        $tahun = [
            ['tahun' => 2024, 'is_aktif' => false],
            ['tahun' => 2025, 'is_aktif' => false],
            ['tahun' => 2026, 'is_aktif' => true],
        ];

        foreach ($tahun as $item) {
            TahunAnggaran::updateOrCreate(
                ['tahun' => $item['tahun']],
                [
                    'label' => 'TA ' . $item['tahun'],
                    'is_aktif' => $item['is_aktif'],
                ]
            );
        }
    }
}
