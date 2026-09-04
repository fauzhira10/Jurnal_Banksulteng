<?php

namespace App\Models;

use App\Observers\JurnalObserver;
use App\Services\NomorTiketService;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

#[ObservedBy([JurnalObserver::class])]
class Jurnal extends Model
{
    protected $guarded = ['id'];

    /**
     * Membuat nomor tiket baru. Dipakai oleh alur pengaduan CS cabang.
     * Jurnal yang diinput langsung Admin Pusat nomor tiketnya diketik petugas,
     * karena berasal dari proses sebelum berkas sampai ke Divisi IT.
     *
     * @see NomorTiketService
     */
    public static function generateNoTiket(mixed $tanggal = null): string
    {
        return NomorTiketService::buat($tanggal);
    }

    /**
     * Apakah pola nomor sesuai format resmi BS-{YYYYMMDD}{5 angka}.
     */
    public static function formatTiketValid(?string $nomor): bool
    {
        return NomorTiketService::formatValid($nomor);
    }

    public function masterCabang()
    {
        return $this->belongsTo(MasterCabang::class, 'master_cabang_id');
    }

    public function masterTransaksi()
    {
        return $this->belongsTo(MasterTransaksi::class, 'master_transaksi_id');
    }

    public function auditTrails()
    {
        return $this->hasMany(AuditTrail::class, 'jurnal_id');
    }

    /**
     * Pengaduan CS cabang yang menjadi sumber jurnal ini (jika ada).
     */
    public function pengaduan(): HasOne
    {
        return $this->hasOne(Pengaduan::class, 'jurnal_id');
    }
}
