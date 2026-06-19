<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('capaian_indikator', function (Blueprint $table) {
            $table->id();
            $table->foreignId('indikator_mutu_id')->constrained('indikator_mutu');
            $table->foreignId('tahun_anggaran_id')->constrained('tahun_anggaran');
            $table->enum('status', ['tercapai', 'proses', 'belum', ''])->default('');
            $table->text('catatan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('capaian_indikator');
    }
};
