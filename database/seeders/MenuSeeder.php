<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Kitchen;
use App\Models\Menu;

class MenuSeeder extends Seeder
{
    public function run(): void
    {
        $daftarMenu = [
            [ 'nama' => 'Sayur sawi dan kol, ayam pedas manis, buah kelengkeng, susu Diamond Milk, dan nasi putih '],
            [ 'nama' => 'Nasi, jeruk, tempe goreng tepung, bayam bening, labu siam, jagung manis, dan ayam bumbu bistik'],
            [ 'nama' => 'Mie kecap, asam semur, sawi rebus, yakult, pisang, and tempe goreng 
'],
            [ 'nama' => 'Nasi putih, ayam semur, tumis toge, wortel, jagung, tahu goreng crispy, dan semangka kuning'],
            [ 'nama' => 'Nasi putih, ayam teriyaki gurih manis, tempe goreng renyah, tumis bayam & wortel, dan buah anggur'],
            [ 'nama' => 'nasi putih, ayam semur, tumis toge, wortel, jagung, tahu goreng crispy dan semangka kuning'],
        ];

        $semuaDapur = Kitchen::all();

        foreach ($semuaDapur as $dapur) {
            foreach ($daftarMenu as $index => $menu) {
            
                // 3. Generate Nomor Urut (5 digit: 00001, 00002, dst)
                $nomorUrut = str_pad($index + 1, 5, '0', STR_PAD_LEFT);
                
                /** * 4. Generate Kode Menu
                 * Format: MN + KODE_DAPUR + NOMOR_URUT
                 * Hasil: MNDPR1100001
                 */
                $kodeMenu = 'MN' . $dapur->kode . $nomorUrut;

                Menu::updateOrCreate(
                    ['kode' => $kodeMenu],
                    [
                        'nama' => $menu['nama'],
                        'kitchen_id' => $dapur->id,
                    ]
                );
            }
        }   
    }    
}