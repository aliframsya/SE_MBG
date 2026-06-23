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
    [
        'nama_region' => 'SOLO RAYA',
        'penanggung_jawab' => 'Andi Setiawan',
        'created_at' => now(),
        'updated_at' => now(),
    ]
);

        DB::table('regions')->updateOrInsert(
            ['kode_region' => 'RGN02'],
            [
                'nama_region' => 'PANTURA BARAT',
                'penanggung_jawab' => 'Budi Santoso',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );

        DB::table('regions')->updateOrInsert(
            ['kode_region' => 'RGN03'],
            [
                'nama_region' => 'PANTURA TIMUR 1',
                'penanggung_jawab' => 'Citra Lestari',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );

        DB::table('regions')->updateOrInsert(
            ['kode_region' => 'RGN04'],
            [
                'nama_region' => 'PANTURA TIMUR 2',
                'penanggung_jawab' => 'Dewi Kartika',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );
    }
}