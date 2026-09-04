<?php

namespace App\Observers;

use App\Enums\PengaduanStatus;
use App\Models\Jurnal;
use App\Models\Pengaduan;
use Illuminate\Support\Carbon;

/**
 * Menyinkronkan status pengaduan CS cabang setiap kali jurnal pusat berubah.
 *
 * Catatan: observer tidak terpicu pada mass delete (mis. Jurnal::query()->delete()
 * di resetAllData) — kasus tersebut ditangani langsung di controller.
 */
class JurnalObserver
{
    public function saved(Jurnal $jurnal): void
    {
        $pengaduan = Pengaduan::where('jurnal_id', $jurnal->id)->first();

        if ($pengaduan) {
            static::sinkronkan($pengaduan, $jurnal);
        }
    }

    public function deleting(Jurnal $jurnal): void
    {
        $pengaduan = Pengaduan::where('jurnal_id', $jurnal->id)->first();

        if ($pengaduan) {
            static::lepasTautan($pengaduan);
        }
    }

    /**
     * Turunkan status pengaduan dari status jurnal (Done/Success → Selesai, Rejected → Ditolak, lainnya → Diproses).
     */
    public static function sinkronkan(Pengaduan $pengaduan, Jurnal $jurnal): void
    {
        $statusBaru = PengaduanStatus::dariStatusJurnal($jurnal->status);

        $data = [
            'jurnal_id' => $jurnal->id,
            'status' => $statusBaru,
            'diproses_at' => $pengaduan->diproses_at ?? now(),
            'diterima_at' => $pengaduan->diterima_at ?? now(),
        ];

        if ($statusBaru === PengaduanStatus::Selesai) {
            $tglSelesai = $jurnal->tgl_selesai ? Carbon::parse($jurnal->tgl_selesai) : now();
            $data['selesai_at'] = $pengaduan->selesai_at ?? $tglSelesai;
            $data['ditolak_at'] = null;
        } elseif ($statusBaru === PengaduanStatus::Ditolak) {
            $data['ditolak_at'] = $pengaduan->ditolak_at ?? now();
            $data['selesai_at'] = null;
            if (empty(trim((string) $pengaduan->catatan_pusat))) {
                $data['catatan_pusat'] = 'Klaim ditolak berdasarkan hasil pemeriksaan jurnal keluhan pusat.';
            }
        } else {
            $data['selesai_at'] = null;
            $data['ditolak_at'] = null;
        }

        $pengaduan->fill($data)->save();
    }

    /**
     * Jurnal dihapus → pengaduan kembali ke status Diterima (menunggu dijurnal ulang).
     */
    public static function lepasTautan(Pengaduan $pengaduan): void
    {
        $pengaduan->fill([
            'jurnal_id' => null,
            'status' => PengaduanStatus::Diterima,
            'diproses_at' => null,
            'selesai_at' => null,
            'ditolak_at' => null,
        ])->save();
    }
}
