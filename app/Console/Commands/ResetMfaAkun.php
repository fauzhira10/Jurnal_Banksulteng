<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Services\AuditTrailService;
use Illuminate\Console\Command;

/**
 * Mematikan autentikasi dua faktor satu akun dari server.
 *
 * Ini jalan keluar terakhir ketika petugas kehilangan ponselnya DAN kode
 * pemulihannya habis. Sengaja hanya tersedia sebagai perintah terminal, bukan
 * tombol di halaman web: siapa pun yang dapat melucuti dua faktor akun lain
 * lewat web akan menjadikan dua faktor itu sendiri tidak ada artinya. Untuk
 * menjalankannya, seseorang harus sudah punya akses ke server.
 *
 * Tindakannya dicatat pada jejak audit, dan petugas yang bersangkutan wajib
 * mendaftarkan ulang dua faktornya begitu bisa masuk kembali.
 */
class ResetMfaAkun extends Command
{
    protected $signature = 'admin:mfa-reset
                            {username : Username akun yang dua faktornya disetel ulang}
                            {--force : Lewati pertanyaan konfirmasi (untuk skrip)}';

    protected $description = 'Mematikan autentikasi dua faktor satu akun, untuk petugas yang kehilangan ponselnya.';

    public function handle(): int
    {
        $username = (string) $this->argument('username');
        $user = User::where('username', $username)->first();

        if (! $user) {
            $this->error("Akun dengan username \"{$username}\" tidak ditemukan.");

            return self::FAILURE;
        }

        if (! $user->mfaAktif() && ! $user->mfaMenungguKonfirmasi()) {
            $this->warn("Akun {$user->name} (@{$user->username}) memang tidak memakai autentikasi dua faktor.");

            return self::SUCCESS;
        }

        $this->line("Akun    : {$user->name} (@{$user->username})");
        $this->line('Peran   : '.$user->labelRole());
        $this->line('Status  : '.($user->mfaAktif() ? 'dua faktor aktif' : 'pendaftaran belum dikonfirmasi'));
        $this->newLine();

        if (! $this->option('force') && ! $this->confirm('Matikan autentikasi dua faktor untuk akun ini?', false)) {
            $this->warn('Dibatalkan. Tidak ada yang diubah.');

            return self::FAILURE;
        }

        $user->matikanMfa();

        AuditTrailService::catat('mfa.direset', [
            'objek' => $user,
            'keterangan' => "Autentikasi dua faktor akun \"{$user->username}\" disetel ulang dari terminal server.",
        ]);

        $this->newLine();
        $this->info("Dua faktor untuk {$user->name} (@{$user->username}) sudah dimatikan.");
        $this->line('Akun kini dapat masuk dengan kata sandi saja. Minta petugas segera mendaftarkan');
        $this->line('ulang dua faktornya lewat menu Keamanan Akun.');

        return self::SUCCESS;
    }
}
