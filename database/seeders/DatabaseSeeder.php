<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            TahunAnggaranSeeder::class,
            BidangKerjaSeeder::class,
            IndikatorMutuSeeder::class,
            KategoriPendapatanSeeder::class,
            KategoriPengeluaranSeeder::class,
            RencanaPendapatanSeeder::class,
            UserSeeder::class,
        ]);
    }
}
