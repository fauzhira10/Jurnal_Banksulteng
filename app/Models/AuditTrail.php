<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Satu baris jejak audit.
 *
 * Baris tidak pernah diubah atau dihapus setelah ditulis. Keutuhannya dijaga
 * rantai hash: setiap baris ikut menyegel hash baris sebelumnya, sehingga
 * menyunting atau membuang satu baris akan memutus rantai pada baris sesudahnya
 * dan terdeteksi oleh `php artisan audit:periksa`.
 */
class AuditTrail extends Model
{
    protected $fillable = [
        'user_id',
        'username',
        'aksi',
        'auditable_type',
        'auditable_id',
        'jurnal_id',
        'nilai_lama',
        'nilai_baru',
        'keterangan',
        'ip',
        'user_agent',
    ];

    protected function casts(): array
    {
        return [
            'nilai_lama' => 'array',
            'nilai_baru' => 'array',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function jurnal(): BelongsTo
    {
        return $this->belongsTo(Jurnal::class, 'jurnal_id');
    }

    /**
     * Ringkasan isi baris dalam bentuk yang selalu sama, dipakai sebagai bahan hash.
     *
     * `user_agent` sengaja tidak ikut disegel: nilainya panjang, dipotong kolom
     * database, dan tidak menentukan makna kejadian. Kolom yang menentukan siapa
     * melakukan apa terhadap data apa semuanya ikut.
     */
    public function sidikJari(): string
    {
        return json_encode([
            'user_id' => $this->user_id,
            'username' => $this->username,
            'aksi' => $this->aksi,
            'auditable_type' => $this->auditable_type,
            'auditable_id' => $this->auditable_id !== null ? (int) $this->auditable_id : null,
            'jurnal_id' => $this->jurnal_id !== null ? (int) $this->jurnal_id : null,
            'nilai_lama' => $this->nilai_lama,
            'nilai_baru' => $this->nilai_baru,
            'keterangan' => $this->keterangan,
            'ip' => $this->ip,
            'created_at' => $this->created_at?->format('Y-m-d H:i:s'),
        ], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    }

    /**
     * Hitung ulang hash baris ini berdasarkan hash baris sebelumnya.
     */
    public function hitungHash(?string $hashSebelumnya): string
    {
        return hash('sha256', ($hashSebelumnya ?? '').'|'.$this->sidikJari());
    }

    /**
     * Apakah isi baris ini masih sesuai dengan hash yang tersimpan.
     */
    public function hashCocok(): bool
    {
        return hash_equals((string) $this->hash_sekarang, $this->hitungHash($this->hash_sebelumnya));
    }
}
