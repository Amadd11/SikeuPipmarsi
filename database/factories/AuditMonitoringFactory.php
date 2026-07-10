<?php

namespace Database\Factories;

use App\Models\IndikatorMutu;
use App\Models\TahunAnggaran;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\AuditMonitoring>
 */
class AuditMonitoringFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $indikatorMutu = IndikatorMutu::inRandomOrder()->first() ?? IndikatorMutu::factory()->create();
        $tahunAnggaran = TahunAnggaran::inRandomOrder()->first() ?? TahunAnggaran::factory()->create();

        return [
            'indikator_mutu_id' => $indikatorMutu->id,
            'tahun_anggaran_id' => $tahunAnggaran->id,
            'uraian_pelaksanaan' => $this->faker->paragraph(2),
            'kendala' => $this->faker->optional(0.7)->paragraph(),
            'faktor_pendukung' => $this->faker->optional(0.7)->paragraph(),
            'perbaikan' => $this->faker->optional(0.7)->paragraph(),
            'rencana_tindak_lanjut' => $this->faker->optional(0.7)->paragraph(),
            'pic' => $this->faker->name(),
            'tanggal_penyelesaian' => $this->faker->dateTimeBetween('now', '+6 months')->format('Y-m-d'),
        ];
    }
}
