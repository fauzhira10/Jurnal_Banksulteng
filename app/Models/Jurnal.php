<?php

namespace App\Models;

use App\Observers\JurnalObserver;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

#[ObservedBy([JurnalObserver::class])]
class Jurnal extends Model
{
    protected $guarded = ['id'];

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
