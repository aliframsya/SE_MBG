<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Kitchen;
use Illuminate\Support\Facades\DB;

class KitchenSeeder extends Seeder
{
    public function run(): void
    {
        // Nonaktifkan foreign key check sebentar untuk truncate
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        Kitchen::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $kitchens = [
            // KOTA BOGOR
            [
                'kode' => 'DPR01', 'nama' => 'Dapur MBG Bogor Tengah', 'alamat' => 'Bogor Tengah', 'kota' => 'Kota Bogor',
                'kepala_dapur' => 'Admin Bogor Tengah', 'nomor_kepala_dapur' => '08110000001', 'region_id' => 1,
            ],
            [
                'kode' => 'DPR02', 'nama' => 'Dapur MBG Bogor Utara', 'alamat' => 'Bogor Utara', 'kota' => 'Kota Bogor',
                'kepala_dapur' => 'Admin Bogor Utara', 'nomor_kepala_dapur' => '08110000002', 'region_id' => 1,
            ],
            [
                'kode' => 'DPR03', 'nama' => 'Dapur MBG Bogor Selatan', 'alamat' => 'Bogor Selatan', 'kota' => 'Kota Bogor',
                'kepala_dapur' => 'Admin Bogor Selatan', 'nomor_kepala_dapur' => '08110000003', 'region_id' => 1,
            ],
            [
                'kode' => 'DPR04', 'nama' => 'Dapur MBG Bogor Timur', 'alamat' => 'Bogor Timur', 'kota' => 'Kota Bogor',
                'kepala_dapur' => 'Admin Bogor Timur', 'nomor_kepala_dapur' => '08110000004', 'region_id' => 1,
            ],
            [
                'kode' => 'DPR05', 'nama' => 'Dapur MBG Bogor Barat', 'alamat' => 'Bogor Barat', 'kota' => 'Kota Bogor',
                'kepala_dapur' => 'Admin Bogor Barat', 'nomor_kepala_dapur' => '08110000005', 'region_id' => 1,
            ],
            [
                'kode' => 'DPR06', 'nama' => 'Dapur MBG Tanah Sareal', 'alamat' => 'Tanah Sareal', 'kota' => 'Kota Bogor',
                'kepala_dapur' => 'Admin Tanah Sareal', 'nomor_kepala_dapur' => '08110000006', 'region_id' => 1,
            ],
            // KABUPATEN BOGOR
            [
                'kode' => 'DPR07', 'nama' => 'Dapur MBG Cibinong', 'alamat' => 'Cibinong', 'kota' => 'Kab. Bogor',
                'kepala_dapur' => 'Admin Cibinong', 'nomor_kepala_dapur' => '08110000007', 'region_id' => 1,
            ],
            [
                'kode' => 'DPR08', 'nama' => 'Dapur MBG Ciawi', 'alamat' => 'Ciawi', 'kota' => 'Kab. Bogor',
                'kepala_dapur' => 'Admin Ciawi', 'nomor_kepala_dapur' => '08110000008', 'region_id' => 1,
            ],
            [
                'kode' => 'DPR09', 'nama' => 'Dapur MBG Citeureup', 'alamat' => 'Citeureup', 'kota' => 'Kab. Bogor',
                'kepala_dapur' => 'Admin Citeureup', 'nomor_kepala_dapur' => '08110000009', 'region_id' => 1,
            ],
            [
                'kode' => 'DPR10', 'nama' => 'Dapur MBG Babakan Madang', 'alamat' => 'Babakan Madang', 'kota' => 'Kab. Bogor',
                'kepala_dapur' => 'Admin Babakan Madang', 'nomor_kepala_dapur' => '08110000010', 'region_id' => 1,
            ],
            [
                'kode' => 'DPR11', 'nama' => 'Dapur MBG Bojonggede', 'alamat' => 'Bojonggede', 'kota' => 'Kab. Bogor',
                'kepala_dapur' => 'Admin Bojonggede', 'nomor_kepala_dapur' => '08110000011', 'region_id' => 1,
            ],
            [
                'kode' => 'DPR12', 'nama' => 'Dapur MBG Sukaraja', 'alamat' => 'Sukaraja', 'kota' => 'Kab. Bogor',
                'kepala_dapur' => 'Admin Sukaraja', 'nomor_kepala_dapur' => '08110000012', 'region_id' => 1,
            ],
            [
                'kode' => 'DPR13', 'nama' => 'Dapur MBG Kemang', 'alamat' => 'Kemang', 'kota' => 'Kab. Bogor',
                'kepala_dapur' => 'Admin Kemang', 'nomor_kepala_dapur' => '08110000013', 'region_id' => 1,
            ],
            [
                'kode' => 'DPR14', 'nama' => 'Dapur MBG Cileungsi', 'alamat' => 'Cileungsi', 'kota' => 'Kab. Bogor',
                'kepala_dapur' => 'Admin Cileungsi', 'nomor_kepala_dapur' => '08110000014', 'region_id' => 1,
            ],
            [
                'kode' => 'DPR15', 'nama' => 'Dapur MBG Gunung Putri', 'alamat' => 'Gunung Putri', 'kota' => 'Kab. Bogor',
                'kepala_dapur' => 'Admin Gunung Putri', 'nomor_kepala_dapur' => '08110000015', 'region_id' => 1,
            ],
            [
                'kode' => 'DPR16', 'nama' => 'Dapur MBG Jonggol', 'alamat' => 'Jonggol', 'kota' => 'Kab. Bogor',
                'kepala_dapur' => 'Admin Jonggol', 'nomor_kepala_dapur' => '08110000016', 'region_id' => 1,
            ],
            [
                'kode' => 'DPR17', 'nama' => 'Dapur MBG Cariu', 'alamat' => 'Cariu', 'kota' => 'Kab. Bogor',
                'kepala_dapur' => 'Admin Cariu', 'nomor_kepala_dapur' => '08110000017', 'region_id' => 1,
            ],
            [
                'kode' => 'DPR18', 'nama' => 'Dapur MBG Leuwiliang', 'alamat' => 'Leuwiliang', 'kota' => 'Kab. Bogor',
                'kepala_dapur' => 'Admin Leuwiliang', 'nomor_kepala_dapur' => '08110000018', 'region_id' => 1,
            ],
            [
                'kode' => 'DPR19', 'nama' => 'Dapur MBG Pamijahan', 'alamat' => 'Pamijahan', 'kota' => 'Kab. Bogor',
                'kepala_dapur' => 'Admin Pamijahan', 'nomor_kepala_dapur' => '08110000019', 'region_id' => 1,
            ],
            [
                'kode' => 'DPR20', 'nama' => 'Dapur MBG Cisarua', 'alamat' => 'Cisarua', 'kota' => 'Kab. Bogor',
                'kepala_dapur' => 'Admin Cisarua', 'nomor_kepala_dapur' => '08110000020', 'region_id' => 1,
            ],
        ];

        foreach ($kitchens as $kitchen) {
            Kitchen::create($kitchen);
        }
    }
}