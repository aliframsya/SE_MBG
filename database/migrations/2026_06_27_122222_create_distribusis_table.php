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
        Schema::create('distribusis', function (Blueprint $table) {
            $table->id();
            $table->date('tanggal');
            $table->string('nama_sekolah');
            $table->string('nama_driver');
            $table->integer('jumlah_porsi_dikirim')->default(0);
            $table->integer('jumlah_sisa_kembali')->default(0);
            $table->text('keterangan_dibuang')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('distribusis');
    }
};
