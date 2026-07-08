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
        Schema::create('pengeluaran', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tahun_anggaran_id')->constrained('tahun_anggaran');
            $table->foreignId('bidang_kerja_id')->constrained('bidang_kerja');
            $table->foreignId('kategori_pengeluaran_id')->constrained('kategori_pengeluaran');
            $table->foreignId('indikator_mutu_id')->nullable()->constrained('indikator_mutu')->onDelete('set null');
            $table->string('nama_kegiatan', 250);
            $table->decimal('jumlah_anggaran', 15, 2)->default(0);
            $table->decimal('jumlah_realisasi', 15, 2)->default(0);
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pengeluaran');
    }
};
