<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('karyawans', function (Blueprint $table) {
            $table->id();
            $table->string('kode')->unique();
            $table->string('nik')->unique();
            $table->string('nama');
            $table->string('jabatan');
            $table->string('kitchen_kode')->nullable();
            $table->string('no_hp')->nullable();
            $table->text('alamat')->nullable();
            $table->date('tanggal_masuk')->nullable();
            $table->enum('status', ['aktif', 'nonaktif'])->default('aktif');
            $table->string('foto')->nullable();
            $table->timestamps();

            $table->foreign('kitchen_kode')->references('kode')->on('kitchens')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('karyawan');
    }
};