<?php

namespace Database\Seeders;

use App\Models\Transaksi;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TransaksiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Nonaktifkan event/sync listener sementara agar tidak error saat mass seeding 
        // (Atau biarkan saja jika ingin mentrigger sinkronisasi jumlah realisasi)
        Transaksi::factory()->count(30)->create();
        
        $this->command->info('Transaksi Seeder berhasil dijalankan: 30 Transaksi ditambahkan!');
    }
}
