<?php

namespace Database\Seeders;

use App\Models\Supplier;
use App\Models\Kitchen;
use Illuminate\Database\Seeder;

class SupplierSeeder extends Seeder
{
    public function run(): void
    {
        $kitchen = Kitchen::where('kode', 'DPR12')->first(); // sesuaikan kode dapur

        Supplier::create([
            'kode' => 'SUP001',
            'nama' => 'Toko Sumber Rejeki',
            'alamat' => 'Jl. Raya Kebonagung No. 10, Demak',
            'kitchen_kode' => $kitchen->kode, // sesuaikan nama kolom di tabel kamu
            'kontak_person' => 'Pak Joko',
            'nomor' => '081234567890',
        ]);

        Supplier::create([
            'kode' => 'SUP002',
            'nama' => 'CV Tani Makmur',
            'alamat' => 'Jl. Pantura Timur No. 5, Demak',
            'kitchen_kode' => $kitchen->kode,
            'kontak_person' => 'Bu Siti',
            'nomor' => '081298765432',
        ]);
    }
}
