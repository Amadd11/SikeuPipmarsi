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
        Schema::create('bidang_kerja', function (Blueprint $table) {
            $table->id();
            $table->string('kode', 5)->unique();
            $table->string('nama', 100);
            $table->text('deskripsi')->nullable();
            $table->string('warna_hex', 7)->nullable();
            $table->tinyInteger('urutan')->default(0);
            $table->timestamps();
        });

        Schema::table('users', function (Blueprint $table) {
            $table->foreign('bidang_kerja_id')->references('id')->on('bidang_kerja')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['bidang_kerja_id']);
        });
        Schema::dropIfExists('bidang_kerja');
    }
};
