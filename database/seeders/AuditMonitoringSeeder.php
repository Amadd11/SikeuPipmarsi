<?php

namespace Database\Seeders;

use App\Models\AuditMonitoring;
use Illuminate\Database\Seeder;

class AuditMonitoringSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        AuditMonitoring::factory()->count(30)->create();
        
        $this->command->info('AuditMonitoringSeeder berhasil dijalankan: 30 data audit & monitoring ditambahkan!');
    }
}
