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
        Schema::create('pendapatan_detail', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pendapatan_id')->constrained('pendapatan')->cascadeOnDelete();
            $table->text('uraian');
            $table->string('satuan', 50);
            $table->decimal('jumlah', 15, 2)->default(0);
            $table->integer('kuantitas')->default(1);
            $table->decimal('total', 15, 2)->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pendapatan_detail');
    }
};
