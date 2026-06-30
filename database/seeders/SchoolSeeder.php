<?php

namespace Database\Seeders;

use App\Models\School;
use Illuminate\Database\Seeder;

class SchoolSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $schools = [
            [
                'name' => 'SDN Polisi 1 Bogor',
                'address' => 'Jl. Paledang No.19',
                'city' => 'Kota Bogor',
                'province' => 'Jawa Barat',
                'kepala_sekolah' => 'Budi Santoso',
                'no_telepon' => '0251-1234567',
                'jumlah_siswa' => 350,
            ],
            [
                'name' => 'SDN Polisi 4 Bogor',
                'address' => 'Jl. Paledang No.21',
                'city' => 'Kota Bogor',
                'province' => 'Jawa Barat',
                'kepala_sekolah' => 'Siti Aminah',
                'no_telepon' => '0251-7654321',
                'jumlah_siswa' => 400,
            ],
            [
                'name' => 'SMPN 1 Bogor',
                'address' => 'Jl. Ir. H. Juanda No.16',
                'city' => 'Kota Bogor',
                'province' => 'Jawa Barat',
                'kepala_sekolah' => 'Drs. H. Ahmad',
                'no_telepon' => '0251-2345678',
                'jumlah_siswa' => 800,
            ],
            [
                'name' => 'SMAN 1 Bogor',
                'address' => 'Jl. Ir. H. Juanda No.16',
                'city' => 'Kota Bogor',
                'province' => 'Jawa Barat',
                'kepala_sekolah' => 'Drs. H. Ujang',
                'no_telepon' => '0251-8765432',
                'jumlah_siswa' => 1200,
            ],
        ];

        foreach ($schools as $school) {
            School::create($school);
        }
    }
}
