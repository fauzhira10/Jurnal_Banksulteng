<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

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
}
