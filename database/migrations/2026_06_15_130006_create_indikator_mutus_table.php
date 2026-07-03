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
        Schema::create('indikator_mutu', function (Blueprint $table) {
            $table->id();
            $table->string('kode', 10)->unique();
            // $table->foreignId('rencana_pengeluaran_id')->nullable()->constrained('rencana_pengeluaran')->nullOnDelete();
            $table->foreignId('bidang_kerja_id')->constrained('bidang_kerja');
            $table->string('nama', 250);
            $table->text('target');
            $table->string('periode', 50)->nullable();
            $table->enum('status', ['belum', 'tercapai', 'proses', 'tidak tercapai'])->default('belum');
            $table->text('catatan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('indikator_mutu');
    }
};
