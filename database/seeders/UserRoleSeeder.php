<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Kitchen;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\PermissionRegistrar;

class UserRoleSeeder extends Seeder
{
    public function run(): void
    {
        // Clear cache permission
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        $users = [

            // ================= SUPERADMIN =================
            [
                'name' => 'Super Admin',
                'email' => 'superadmin@example.com',
                'role' => 'superadmin',
                'kitchens' => Kitchen::pluck('kode')->toArray(),
            ],

            // ================= OPERATOR REGION =================
            [
                'name' => 'Operator Region Pantura Timur',
                'email' => 'operator.region@example.com',
                'role' => 'operatorRegion',
                'kitchens' => Kitchen::where('region_id', 3)->pluck('kode')->toArray(),
            ],

            // ================= OPERATOR DAPUR =================
            [
                'name' => 'Operator Dapur DPR12',
                'email' => 'operator.dapur12@example.com',
                'role' => 'operatorDapur',
                'kitchens' => Kitchen::where('kode', 'DPR12')->pluck('kode')->toArray(),
            ],

            [
                'name' => 'Operator Dapur DPR13',
                'email' => 'operator.dapur13@example.com',
                'role' => 'operatorDapur',
                'kitchens' => Kitchen::where('kode', 'DPR13')->pluck('kode')->toArray(),
            ],

            // ================= OPERATOR KOPERASI =================
            [
                'name' => 'Operator Koperasi',
                'email' => 'koperasi@example.com',
                'role' => 'operatorKoperasi',
                'kitchens' => Kitchen::pluck('kode')->toArray(),
            ],

            // ================= MITRA =================
            [
                'name' => 'Mitra Vendor',
                'email' => 'mitra@example.com',
                'role' => 'mitra',
                'kitchens' => Kitchen::pluck('kode')->toArray(),
            ],

            // ================= KARYAWAN =================
            [
                'name' => 'Karyawan',
                'email' => 'karyawan@gmail.com',
                'role' => 'karyawan',
                'kitchens' => [],
            ],

        ];

        foreach ($users as $data) {

            $user = User::updateOrCreate(
                [
                    'email' => $data['email']
                ],
                [
                    'name' => $data['name'],
                    'password' => Hash::make('password'),
                    'status' => 'disetujui',
                ]
            );

            // Assign Role
            $user->syncRoles([$data['role']]);

            // Assign Kitchen jika ada
            if (!empty($data['kitchens'])) {
                $user->kitchens()->sync($data['kitchens']);
            }
        }
    }
}