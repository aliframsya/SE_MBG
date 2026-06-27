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
        Schema::table('kebutuhan_harians', function (Blueprint $table) {
            $table->integer('pm_porsi_kecil')->default(0)->after('total_pm');
            $table->integer('pm_porsi_besar')->default(0)->after('pm_porsi_kecil');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('kebutuhan_harians', function (Blueprint $table) {
            $table->dropColumn(['pm_porsi_kecil', 'pm_porsi_besar']);
        });
    }
};
