<?php

namespace Database\Seeders;

use App\Enums\UserRole;
use App\Models\MasterCabang;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Akun Admin Pusat (Divisi IT)
        User::updateOrCreate(
            ['email' => 'admin@banksulteng.co.id'],
            [
                'username' => 'admin',
                'name' => 'Administrator Bank Sulteng',
                'password' => Hash::make('admin123'),
                'email_verified_at' => now(),
                'role' => UserRole::Admin,
                'master_cabang_id' => null,
                'is_active' => true,
            ]
        );

        // Akun contoh CS Cabang Utama (untuk pengembangan / uji coba)
        $cabangUtama = MasterCabang::where('kode_cabang', '001')->first();

        if ($cabangUtama) {
            User::updateOrCreate(
                ['username' => 'cs.palu'],
                [
                    'name' => 'CS Cabang Utama Palu',
                    'email' => 'cs.palu@banksulteng.co.id',
                    'password' => Hash::make('cs12345'),
                    'email_verified_at' => now(),
                    'role' => UserRole::Cs,
                    'master_cabang_id' => $cabangUtama->id,
                    'is_active' => true,
                ]
            );
        }
    }
}
