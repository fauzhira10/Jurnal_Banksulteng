<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Enums\UserRole;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;

#[Fillable(['name', 'username', 'email', 'password', 'role', 'is_superadmin', 'master_cabang_id', 'is_active'])]
#[Hidden(['password', 'remember_token', 'mfa_rahasia', 'mfa_kode_pemulihan'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'username',
        'email',
        'password',
        'role',
        'is_superadmin',
        'master_cabang_id',
        'is_active',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'role' => UserRole::class,
            'is_superadmin' => 'boolean',
            'is_active' => 'boolean',
            // Terenkripsi: salinan basis data yang bocor tidak boleh cukup untuk
            // membuat kode dua faktor petugas. Sengaja TIDAK masuk $fillable —
            // rahasianya hanya boleh disetel lewat alur pendaftaran MFA.
            'mfa_rahasia' => 'encrypted',
            'mfa_kode_pemulihan' => 'encrypted:array',
            'mfa_dikonfirmasi_pada' => 'datetime',
        ];
    }

    // ==================== RELASI ====================

    /**
     * Cabang penempatan (hanya untuk role CS).
     */
    public function cabang(): BelongsTo
    {
        return $this->belongsTo(MasterCabang::class, 'master_cabang_id');
    }

    /**
     * Pengaduan yang dikirim oleh pengguna ini (CS).
     */
    public function pengaduans(): HasMany
    {
        return $this->hasMany(Pengaduan::class, 'user_id');
    }

    // ==================== HELPER PERAN ====================

    public function isAdmin(): bool
    {
        return $this->role === UserRole::Admin;
    }

    public function isSuperAdmin(): bool
    {
        return $this->isAdmin() && ((bool) $this->is_superadmin || $this->username === 'admin');
    }

    public function isCs(): bool
    {
        return $this->role === UserRole::Cs;
    }

    /**
     * Nama route beranda sesuai peran pengguna.
     */
    public function homeRoute(): string
    {
        return ($this->role ?? UserRole::Admin)->homeRoute();
    }

    public function labelRole(): string
    {
        return ($this->role ?? UserRole::Admin)->label();
    }

    // ==================== AUTENTIKASI DUA FAKTOR ====================

    /**
     * Banyaknya kode pemulihan yang dibuat sekali jalan.
     */
    public const JUMLAH_KODE_PEMULIHAN = 8;

    /**
     * MFA benar-benar berlaku hanya setelah satu kode terbukti masuk.
     *
     * Rahasianya sudah tersimpan sejak petugas menekan "Aktifkan", tetapi
     * memberlakukannya saat itu juga akan mengunci petugas yang ternyata gagal
     * memindai atau salah mengetik rahasianya ke aplikasi autentikator.
     */
    public function mfaAktif(): bool
    {
        return $this->mfa_dikonfirmasi_pada !== null && ! empty($this->mfa_rahasia);
    }

    /**
     * Rahasia sudah dibuat, tetapi belum pernah dibuktikan dengan satu kode benar.
     */
    public function mfaMenungguKonfirmasi(): bool
    {
        return $this->mfa_dikonfirmasi_pada === null && ! empty($this->mfa_rahasia);
    }

    /**
     * Buat ulang seluruh kode pemulihan dan simpan.
     *
     * Kode disimpan terenkripsi, bukan di-hash, supaya dapat ditampilkan ulang
     * kepada pemilik akunnya sendiri. Yang memegang salinan basis data tanpa
     * APP_KEY tetap tidak bisa membacanya.
     *
     * @return array<int, string>
     */
    public function buatKodePemulihan(): array
    {
        $kode = [];

        for ($i = 0; $i < self::JUMLAH_KODE_PEMULIHAN; $i++) {
            $kode[] = strtoupper(Str::random(5).'-'.Str::random(5));
        }

        $this->mfa_kode_pemulihan = $kode;
        $this->save();

        return $kode;
    }

    /**
     * Pakai satu kode pemulihan; kode yang terpakai langsung dibuang.
     *
     * Kode pemulihan adalah jalan masuk terakhir ketika ponsel petugas hilang
     * atau terformat, jadi perbandingannya mengabaikan besar-kecil huruf dan
     * spasi — tetapi tetap `hash_equals`, supaya lama pemeriksaannya tidak
     * membocorkan seberapa jauh tebakan seseorang sudah benar.
     */
    public function pakaiKodePemulihan(string $kode): bool
    {
        $kode = strtoupper(trim($kode));
        $tersimpan = $this->mfa_kode_pemulihan ?? [];

        foreach ($tersimpan as $indeks => $satu) {
            if (hash_equals(strtoupper($satu), $kode)) {
                unset($tersimpan[$indeks]);

                $this->mfa_kode_pemulihan = array_values($tersimpan);
                $this->save();

                return true;
            }
        }

        return false;
    }

    /**
     * Matikan MFA dan hapus seluruh jejaknya dari akun ini.
     */
    public function matikanMfa(): void
    {
        $this->forceFill([
            'mfa_rahasia' => null,
            'mfa_kode_pemulihan' => null,
            'mfa_dikonfirmasi_pada' => null,
            'mfa_langkah_terakhir' => null,
        ])->save();
    }
}
