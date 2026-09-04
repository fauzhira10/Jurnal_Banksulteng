<?php

namespace App\Models;

use App\Enums\PengaduanStatus;
use App\Services\NomorTiketService;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Pengaduan nasabah yang dikirim CS cabang ke Admin Pusat.
 */
class Pengaduan extends Model
{
    protected $guarded = ['id'];

    protected function casts(): array
    {
        return [
            'status' => PengaduanStatus::class,
            'tgl_transaksi' => 'date',
            'nominal_transaksi' => 'decimal:2',
            'diterima_at' => 'datetime',
            'diproses_at' => 'datetime',
            'selesai_at' => 'datetime',
            'ditolak_at' => 'datetime',
        ];
    }

    protected static function booted(): void
    {
        static::creating(function (Pengaduan $pengaduan) {
            // Nomor tiket lahir di sini, yaitu saat CS cabang mengirim keluhan,
            // lalu dibawa terus ke Jurnal Keluhan tanpa dibuat ulang.
            if (empty($pengaduan->nomor_tiket)) {
                $pengaduan->nomor_tiket = NomorTiketService::buat();
            }
            if (empty($pengaduan->status)) {
                $pengaduan->status = PengaduanStatus::Terkirim;
            }
        });
    }

    // ==================== RELASI ====================

    /** CS yang mengirim pengaduan */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /** Cabang asal pengaduan */
    public function cabang(): BelongsTo
    {
        return $this->belongsTo(MasterCabang::class, 'master_cabang_id');
    }

    /** Jenis transaksi (master) */
    public function transaksi(): BelongsTo
    {
        return $this->belongsTo(MasterTransaksi::class, 'master_transaksi_id');
    }

    /** Jurnal keluhan pusat yang tertaut (setelah diproses) */
    public function jurnal(): BelongsTo
    {
        return $this->belongsTo(Jurnal::class, 'jurnal_id');
    }

    /** Admin pusat yang menerima pengaduan */
    public function penerima(): BelongsTo
    {
        return $this->belongsTo(User::class, 'diterima_oleh');
    }

    /** Lampiran (PDF) */
    public function lampirans(): HasMany
    {
        return $this->hasMany(PengaduanLampiran::class, 'pengaduan_id')->orderBy('id');
    }

    // ==================== SCOPE ====================

    public function scopeUntukCabang(Builder $query, ?int $masterCabangId): Builder
    {
        return $query->where('master_cabang_id', $masterCabangId);
    }

    /**
     * Lingkup pengaduan yang boleh dilihat seorang CS:
     * pengaduan yang ia kirim sendiri, pengaduan dengan asal cabang = cabang akunnya,
     * atau pengaduan yang dikirim CS lain dari cabang akun yang sama.
     */
    public function scopeUntukCs(Builder $query, User $user): Builder
    {
        $cabangId = $user->master_cabang_id;

        return $query->where(function (Builder $q) use ($user, $cabangId) {
            $q->where('user_id', $user->id);

            if ($cabangId !== null) {
                $q->orWhere('master_cabang_id', $cabangId)
                    ->orWhereHas('user', fn (Builder $u) => $u->where('master_cabang_id', $cabangId));
            }
        });
    }

    public function scopeStatus(Builder $query, PengaduanStatus|string|null $status): Builder
    {
        if ($status === null || $status === '') {
            return $query;
        }

        $nilai = $status instanceof PengaduanStatus ? $status->value : $status;

        return $query->where('status', $nilai);
    }

    /**
     * Pencarian kata kunci sederhana (nomor, nama nasabah, rekening, resi, pelapor).
     */
    public function scopeCari(Builder $query, ?string $keyword): Builder
    {
        $keyword = trim((string) $keyword);
        if ($keyword === '') {
            return $query;
        }

        return $query->where(function (Builder $q) use ($keyword) {
            $q->where('nomor_tiket', 'LIKE', "%{$keyword}%")
                ->orWhere('nama_nasabah', 'LIKE', "%{$keyword}%")
                ->orWhere('no_rekening', 'LIKE', "%{$keyword}%")
                ->orWhere('no_resi', 'LIKE', "%{$keyword}%")
                ->orWhere('no_kartu', 'LIKE', "%{$keyword}%")
                ->orWhere('no_hp', 'LIKE', "%{$keyword}%")
                ->orWhere('nama_pelapor', 'LIKE', "%{$keyword}%")
                ->orWhere('kategori', 'LIKE', "%{$keyword}%");
        });
    }

    // ==================== HELPER ====================

    public function bisaDiedit(): bool
    {
        return $this->status instanceof PengaduanStatus && $this->status->bisaDiedit();
    }

    public function sudahMasukJurnal(): bool
    {
        return $this->jurnal_id !== null;
    }

    /**
     * Label cabang: "001 - CABANG UTAMA" (kode disembunyikan untuk CALL CENTER).
     */
    public function labelCabang(): string
    {
        $cabang = $this->cabang;
        if (! $cabang) {
            return '-';
        }

        $nama = $cabang->nama_cabang ?? '-';
        $kode = $cabang->kode_cabang ?? '';

        return ($kode !== '' && strtoupper(trim($nama)) !== 'CALL CENTER') ? "{$kode} - {$nama}" : $nama;
    }

    /**
     * Gabungan kategori: "KATEGORI / SUB / SUB 2" (bagian kosong dilewati).
     */
    public function kategoriLengkap(): string
    {
        $bagian = array_filter([
            trim((string) $this->kategori),
            trim((string) $this->sub_kategori),
            trim((string) $this->sub_kategori_2),
        ], fn ($v) => $v !== '');

        return $bagian ? implode(' / ', $bagian) : '-';
    }

    public function nominalRupiah(): string
    {
        return 'Rp '.number_format((float) $this->nominal_transaksi, 0, ',', '.');
    }
}
