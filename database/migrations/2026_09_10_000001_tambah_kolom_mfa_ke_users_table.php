<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Kolom autentikasi dua faktor (TOTP) pada tabel users.
 *
 * `mfa_rahasia` dan `mfa_kode_pemulihan` disimpan terenkripsi lewat cast model,
 * jadi isinya tidak terbaca oleh siapa pun yang hanya memegang salinan basis
 * data. Konsekuensinya: bila APP_KEY hilang, MFA seluruh akun harus disetel
 * ulang lewat `php artisan admin:mfa-reset` — data lain tidak terpengaruh.
 *
 * `mfa_dikonfirmasi_pada` sengaja terpisah dari `mfa_rahasia`: rahasianya dibuat
 * lebih dulu agar dapat dipindai petugas, tetapi MFA baru berlaku setelah satu
 * kode benar terbukti masuk. Tanpa pemisahan ini, petugas yang gagal memindai
 * akan langsung terkunci dari akunnya sendiri.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->text('mfa_rahasia')->nullable()->after('is_active');
            $table->text('mfa_kode_pemulihan')->nullable()->after('mfa_rahasia');
            $table->timestamp('mfa_dikonfirmasi_pada')->nullable()->after('mfa_kode_pemulihan');

            // Langkah waktu TOTP terakhir yang diterima. Satu kode hanya boleh
            // dipakai sekali: tanpa ini, kode yang terlihat orang lain di layar
            // masih dapat dipakai ulang selama jendela 30 detiknya belum lewat.
            $table->unsignedBigInteger('mfa_langkah_terakhir')->nullable()->after('mfa_dikonfirmasi_pada');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'mfa_rahasia',
                'mfa_kode_pemulihan',
                'mfa_dikonfirmasi_pada',
                'mfa_langkah_terakhir',
            ]);
        });
    }
};
