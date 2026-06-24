<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RegionSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('regions')->updateOrInsert(
            ['kode_region' => 'RGN01'],
            ['id' => 1, 'nama_region' => 'SOLO RAYA', 'penanggung_jawab' => 'Andi Setiawan', 'created_at' => now(), 'updated_at' => now()]
        );
        DB::table('regions')->updateOrInsert(
            ['kode_region' => 'RGN02'],
            ['id' => 2, 'nama_region' => 'PANTURA BARAT', 'penanggung_jawab' => 'Budi Santoso', 'created_at' => now(), 'updated_at' => now()]
        );
        DB::table('regions')->updateOrInsert(
            ['kode_region' => 'RGN03'],
            ['id' => 3, 'nama_region' => 'PANTURA TIMUR 1', 'penanggung_jawab' => 'Citra Lestari', 'created_at' => now(), 'updated_at' => now()]
        );
        DB::table('regions')->updateOrInsert(
            ['kode_region' => 'RGN04'],
            ['id' => 4, 'nama_region' => 'PANTURA TIMUR 2', 'penanggung_jawab' => 'Dewi Kartika', 'created_at' => now(), 'updated_at' => now()]
        );
    }
}