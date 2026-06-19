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
        Schema::create('transaksi', function (Blueprint $table) {
            $table->id();
            $table->string('kode_transaksi', 30)->unique();
            $table->foreignId('tahun_anggaran_id')->constrained('tahun_anggaran');
            $table->date('tanggal');
            $table->enum('jenis', ['masuk', 'keluar']);
            $table->string('uraian', 300);
            $table->foreignId('bidang_kerja_id')->nullable()->constrained('bidang_kerja')->onDelete('set null');
            $table->nullableMorphs('transaksable');
            $table->decimal('jumlah', 15, 2);
            $table->string('nomor_bukti', 50)->nullable();
            $table->string('dicatat_oleh', 100)->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transaksi');
    }
};
