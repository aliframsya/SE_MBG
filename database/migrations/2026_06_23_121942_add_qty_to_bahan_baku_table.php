<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('bahan_baku', function (Blueprint $table) {
            // Menambahkan kolom qty dengan nilai default 0
            $table->integer('qty')->default(0)->after('harga');
        });
    }

    public function down()
    {
        Schema::table('bahan_baku', function (Blueprint $table) {
            $table->dropColumn('qty');
        });
    }
};