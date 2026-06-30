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
        Schema::create('schools', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('address')->nullable();
            $table->string('city')->default('Kota Bogor');
            $table->string('province')->default('Jawa Barat');
            $table->string('kepala_sekolah')->nullable();
            $table->string('no_telepon')->nullable();
            $table->integer('jumlah_siswa')->default(0);
            $table->foreignId('kitchen_id')->nullable()->constrained('kitchens')->onDelete('set null');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('schools');
    }
};
