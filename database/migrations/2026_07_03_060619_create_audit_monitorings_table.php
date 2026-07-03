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
        Schema::create('audit_monitorings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('indikator_mutu_id')->constrained('indikator_mutu')->cascadeOnDelete();
            $table->foreignId('tahun_anggaran_id')->constrained('tahun_anggaran')->cascadeOnDelete();
            $table->text('uraian_pelaksanaan')->nullable();
            $table->text('kendala')->nullable();
            $table->text('faktor_pendukung')->nullable();
            $table->text('perbaikan')->nullable();
            $table->text('rencana_tindak_lanjut')->nullable();
            $table->string('pic')->nullable();
            $table->date('tanggal_penyelesaian')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('audit_monitorings');
    }
};
