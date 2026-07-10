<?php

namespace Database\Factories;

use App\Models\BidangKerja;
use App\Models\RencanaPendapatan;
use App\Models\RencanaPengeluaran;
use App\Models\TahunAnggaran;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Transaksi>
 */
class TransaksiFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $jenis = $this->faker->randomElement(['pemasukan', 'pengeluaran']);
        $tahunAnggaran = TahunAnggaran::inRandomOrder()->first() ?? TahunAnggaran::factory()->create();
        $bidangKerja = BidangKerja::inRandomOrder()->first() ?? BidangKerja::factory()->create();
        $user = User::inRandomOrder()->first() ?? User::factory()->create();

        if ($jenis === 'pemasukan') {
            $transaksable = RencanaPendapatan::inRandomOrder()->first();
        } else {
            $transaksable = RencanaPengeluaran::inRandomOrder()->first();
        }

        return [
            'kode_transaksi' => 'TRX-' . $this->faker->unique()->numerify('####-#####'),
            'tahun_anggaran_id' => $tahunAnggaran->id,
            'tanggal' => $this->faker->dateTimeBetween('-1 year', 'now')->format('Y-m-d'),
            'jenis' => $jenis,
            'uraian' => $this->faker->sentence(6),
            'bidang_kerja_id' => $jenis === 'pengeluaran' ? $bidangKerja->id : null,
            'transaksable_type' => $transaksable ? get_class($transaksable) : null,
            'transaksable_id' => $transaksable ? $transaksable->id : null,
            'jumlah' => $this->faker->randomFloat(0, 100000, 10000000),
            'nomor_bukti' => 'NOTA/' . $this->faker->numerify('#####'),
            'user_id' => $user->id,
        ];
    }
}
