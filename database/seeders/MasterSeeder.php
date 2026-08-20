<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MasterSeeder extends Seeder
{
    public function run(): void
    {
        // Data Master Cabang
        DB::table('master_cabangs')->insert([
            ['kode_cabang' => '106', 'nama_cabang' => 'KCP TINOMBO'],
            ['kode_cabang' => '405', 'nama_cabang' => 'KCP TOILI'],
            ['kode_cabang' => '201', 'nama_cabang' => 'CABANG BUOL'],
        ]);

        // Data Master Transaksi (Auto-fill nanti mengambil data ini)
        DB::table('master_transaksis')->insert([
            ['jenis_transaksi' => 'ATM_TARIK TUNAI ATM BANK SULTENG', 'biaya_admin' => 0, 'channel' => 'ATM SULTENG'],
            ['jenis_transaksi' => 'ATM_TARIK TUNAI DI BANK LAIN', 'biaya_admin' => 7500, 'channel' => 'ATM BERSAMA'],
            ['jenis_transaksi' => 'ATM_TRANSFER MESIN BANK LAIN', 'biaya_admin' => 6500, 'channel' => 'ATM BERSAMA'],
        ]);
    }
}