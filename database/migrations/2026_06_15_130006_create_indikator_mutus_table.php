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
            $table->foreignId('bidang_kerja_id')->constrained('bidang_kerja');
            $table->string('nama', 250);
            $table->text('target');
            $table->enum('periode_evaluasi', ['Per kegiatan', 'Bulanan', 'Semesteran', 'Kuartalan', 'Tahunan', 'Per rapat', 'Periodik']);
            $table->tinyInteger('urutan')->default(0);
            $table->boolean('is_aktif')->default(true);
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
