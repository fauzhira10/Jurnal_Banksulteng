<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MasterSeeder extends Seeder
{
    public function run(): void
    {
        // Data Master Cabang
        $cabangs = [
            ['kode_cabang' => '106', 'nama_cabang' => 'KCP TINOMBO'],
            ['kode_cabang' => '405', 'nama_cabang' => 'KCP TOILI'],
            ['kode_cabang' => '201', 'nama_cabang' => 'CABANG BUOL'],
        ];

        foreach ($cabangs as $cabang) {
            DB::table('master_cabangs')->updateOrInsert(
                ['kode_cabang' => $cabang['kode_cabang']],
                ['nama_cabang' => $cabang['nama_cabang'], 'updated_at' => now(), 'created_at' => now()]
            );
        }

        // Data Master Transaksi lengkap sesuai daftar master bank
        $transaksis = [
            ['jenis_transaksi' => 'ATM_TARIK TUNAI ATM BANK SULTENG', 'biaya_admin' => 0, 'channel' => 'ATM LOKAL'],
            ['jenis_transaksi' => 'ATM_TARIK TUNAI DI BANK LAIN', 'biaya_admin' => 0, 'channel' => 'ATM BERSAMA'],
            ['jenis_transaksi' => 'CRM_SETOR TUNAI', 'biaya_admin' => 0, 'channel' => 'ATM LOKAL'],
            ['jenis_transaksi' => 'ATM_TRANSFER MESIN BANK SULTENG', 'biaya_admin' => 0, 'channel' => 'ATM LOKAL'],
            ['jenis_transaksi' => 'ATM_TRANSFER MESIN BANK LAIN', 'biaya_admin' => 0, 'channel' => 'ATM BERSAMA'],
            ['jenis_transaksi' => 'ATM_TELKOM', 'biaya_admin' => 0, 'channel' => 'FINNET'],
            ['jenis_transaksi' => 'ATM_PULSA TSEL', 'biaya_admin' => 0, 'channel' => 'FINNET'],
            ['jenis_transaksi' => 'ATM_PULSA XL', 'biaya_admin' => 0, 'channel' => 'FINNET'],
            ['jenis_transaksi' => 'ATM_PLN PREPAID', 'biaya_admin' => 0, 'channel' => 'FINNET'],
            ['jenis_transaksi' => 'ATM_DANA', 'biaya_admin' => 0, 'channel' => 'FINNET'],
            ['jenis_transaksi' => 'ATM_GOPAY', 'biaya_admin' => 0, 'channel' => 'FINNET'],
            ['jenis_transaksi' => 'ATM_PEMBAYARAN HALO', 'biaya_admin' => 0, 'channel' => 'FINNET'],
            ['jenis_transaksi' => 'ATM_BPJS', 'biaya_admin' => 0, 'channel' => 'FINNET'],
            ['jenis_transaksi' => 'SMS BANKING (TRANSFER INTERNAL)', 'biaya_admin' => 0, 'channel' => 'SMS BANKING'],
            ['jenis_transaksi' => 'SMS BANKING (TRANSFER KE BANK LAIN)', 'biaya_admin' => 0, 'channel' => 'SMS BANKING'],
            ['jenis_transaksi' => 'SMS BANKING (PULSA TSEL)', 'biaya_admin' => 0, 'channel' => 'SMS BANKING'],
            ['jenis_transaksi' => 'SMS BANKING (PLN PREPAID)', 'biaya_admin' => 0, 'channel' => 'SMS BANKING'],
            ['jenis_transaksi' => 'MBANKING_TRANSFER', 'biaya_admin' => 0, 'channel' => 'MOBILE BANKING'],
            ['jenis_transaksi' => 'MBANKING_TELKOM', 'biaya_admin' => 0, 'channel' => 'MOBILE BANKING'],
            ['jenis_transaksi' => 'MBANKING_PULSA TSEL', 'biaya_admin' => 0, 'channel' => 'MOBILE BANKING'],
            ['jenis_transaksi' => 'MBANKING_PULSA XL', 'biaya_admin' => 0, 'channel' => 'MOBILE BANKING'],
            ['jenis_transaksi' => 'MBANKING_PLN PREPAID', 'biaya_admin' => 0, 'channel' => 'MOBILE BANKING'],
            ['jenis_transaksi' => 'MBANKING_DANA', 'biaya_admin' => 0, 'channel' => 'MOBILE BANKING'],
            ['jenis_transaksi' => 'MBANKING_PEMBAYARAN', 'biaya_admin' => 0, 'channel' => 'MOBILE BANKING'],
            ['jenis_transaksi' => 'MBANKING_PEMBAYARAN HALO', 'biaya_admin' => 0, 'channel' => 'MOBILE BANKING'],
            ['jenis_transaksi' => 'MBANKING_BPJS', 'biaya_admin' => 0, 'channel' => 'MOBILE BANKING'],
            ['jenis_transaksi' => 'EDC', 'biaya_admin' => 0, 'channel' => 'DEBIT'],
            ['jenis_transaksi' => 'EDC BANK LAIN', 'biaya_admin' => 0, 'channel' => 'EDC BANK LAIN'],
            ['jenis_transaksi' => 'QRIS', 'biaya_admin' => 0, 'channel' => 'MOBILE BANKING'],
            ['jenis_transaksi' => 'LAKU PANDAI', 'biaya_admin' => 0, 'channel' => 'LAKU PANDAI'],
            ['jenis_transaksi' => 'LAINNYA_PERMINTAAN CCTV', 'biaya_admin' => 0, 'channel' => 'CCTV'],
            ['jenis_transaksi' => 'PEMBAYARAN', 'biaya_admin' => 0, 'channel' => 'FINNET'],
            ['jenis_transaksi' => 'PEMBELIAN', 'biaya_admin' => 0, 'channel' => 'FINNET'],
        ];

        foreach ($transaksis as $t) {
            DB::table('master_transaksis')->updateOrInsert(
                ['jenis_transaksi' => $t['jenis_transaksi']],
                ['biaya_admin' => $t['biaya_admin'], 'channel' => $t['channel'], 'updated_at' => now(), 'created_at' => now()]
            );
        }
    }
}