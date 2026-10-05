<?php

namespace App\Console\Commands;

use App\Models\OsisElection;
use App\Models\Pemilih;
use App\Models\Siswa;
use App\Models\Voting;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

/**
 * Command untuk membuat/mengelola election OSIS voting.
 *
 * Karena aplikasi tidak memiliki UI CRUD election, command ini
 * menyediakan cara membuat, menutup, dan memantau election OSIS
 * langsung dari terminal (tanpa perlu tinker manual).
 *
 * Usage:
 *   php artisan osis:manage create --title="Pemilihan OSIS 2026" --days=7
 *   php artisan osis:manage status
 *   php artisan osis:manage close
 *   php artisan osis:manage close --id=3
 *   php artisan osis:manage reset-vote --id=1 --user=661   # reset vote test 1 user
 *   php artisan osis:manage reset-vote --id=1 --siswa=12  # reset vote test via Siswa ID
 *   php artisan osis:manage reset-vote --id=1 --all       # reset semua vote (testing penuh)
 */
class OsisElectionCommand extends Command
{
    protected $signature = 'osis:manage
                            {action : Aksi yang akan dilakukan (create|close|status|reset-vote)}
                            {--title= : Judul election (wajib untuk create)}
                            {--description= : Deskripsi election}
                            {--days=7 : Durasi election dalam hari (create)}
                            {--classes= : Kelas yang diizinkan vote, pisahkan koma (opsional, contoh: "X-1,X-2")}
                            {--max-votes=1 : Maksimal vote per siswa}
                            {--id= : ID election (wajib untuk reset-vote, opsional untuk close)}
                            {--user= : ID user untuk reset vote test (reset-vote)}
                            {--siswa= : ID siswa untuk reset vote test (reset-vote)}
                            {--all : Reset SEMUA vote di election untuk testing penuh (reset-vote)}';

    protected $description = 'Buat dan kelola election OSIS (create|close|status|reset-vote) — tanpa UI CRUD';

    public function handle(): int
    {
        $action = strtolower(trim((string) $this->argument('action')));

        return match ($action) {
            'create' => $this->handleCreate(),
            'close' => $this->handleClose(),
            'status' => $this->handleStatus(),
            'reset-vote' => $this->handleResetVote(),
            default => $this->handleInvalidAction($action),
        };
    }

    /**
     * Handle action yang tidak dikenal.
     */
    private function handleInvalidAction(string $action): int
    {
        $this->error("❌ Action tidak dikenal: \"{$action}\"");
        $this->line('   Action yang tersedia: create | close | status | reset-vote');
        $this->line('   Contoh: php artisan osis:manage create --title="Pemilihan OSIS 2026"');
        $this->line('           php artisan osis:manage status');
        $this->line('           php artisan osis:manage close');
        $this->line('           php artisan osis:manage reset-vote --id=1 --user=661');

        return self::FAILURE;
    }

    /**
     * Buat election OSIS baru.
     *
     * Penanganan election aktif ganda: jika sudah ada election aktif,
     * peringatkan user + minta konfirmasi. Jika user setuju, election
     * lama dinonaktifkan (is_active=false) SEBELUM election baru dibuat.
     * Pendekatan ini yang paling aman karena voting app memakai
     * OsisElection::active()->first() — election ganda aktif berisiko
     * mengambil election yang salah.
     */
    private function handleCreate(): int
    {
        $title = trim((string) $this->option('title'));
        if ($title === '') {
            $this->error('❌ Opsi --title wajib diisi untuk action create.');
            $this->line('   Contoh: php artisan osis:manage create --title="Pemilihan OSIS 2026"');

            return self::FAILURE;
        }

        $days = (int) $this->option('days');
        if ($days < 1) {
            $this->error('❌ Opsi --days harus >= 1 hari.');

            return self::FAILURE;
        }

        $maxVotes = (int) $this->option('max-votes');
        if ($maxVotes < 1) {
            $this->error('❌ Opsi --max-votes harus >= 1.');

            return self::FAILURE;
        }

        $description = trim((string) $this->option('description'));
        if ($description === '') {
            $description = $title; // Default: deskripsi = judul
        }

        $allowedClasses = $this->parseAllowedClasses();

        // Cek election aktif yang sudah ada — jangan paksa bikin election ganda aktif.
        $existingActive = OsisElection::active()->get();
        if ($existingActive->isNotEmpty()) {
            $this->warn("⚠️ Sudah ada {$existingActive->count()} election yang sedang aktif:");
            foreach ($existingActive as $existing) {
                $this->line("   - ID {$existing->id}: {$existing->title} ({$existing->start_date->format('d M Y H:i')} → {$existing->end_date->format('d M Y H:i')})");
            }
            $this->newLine();
            $this->warn('   Voting app memakai OsisElection::active()->first(), jadi election ganda aktif');
            $this->warn('   berisiko mengambil election yang salah. Election lama akan dinonaktifkan');
            $this->warn('   (is_active=false) agar hanya ada 1 election aktif setelah create.');

            if (! $this->confirm('Nonaktifkan election lama dan buat election baru?', true)) {
                $this->info('❌ Dibatalkan. Tidak ada election yang dibuat/diubah.');

                return self::FAILURE;
            }

            foreach ($existingActive as $existing) {
                $existing->update(['is_active' => false]);
                $this->line("   ↩️ Election ID {$existing->id} ({$existing->title}) dinonaktifkan.");
            }
        }

        $election = OsisElection::create([
            'title' => $title,
            'description' => $description,
            'start_date' => now(),
            'end_date' => now()->addDays($days),
            'is_active' => true,
            'is_locked' => false,
            'max_votes_per_student' => $maxVotes,
            'allowed_classes' => $allowedClasses,
        ]);

        $this->newLine();
        $this->info('✅ Election OSIS berhasil dibuat:');
        $this->table(
            ['Field', 'Value'],
            [
                ['ID', $election->id],
                ['Title', $election->title],
                ['Description', $election->description],
                ['Start Date', $election->start_date->format('d M Y H:i')],
                ['End Date', $election->end_date->format('d M Y H:i')],
                ['Duration', "{$days} hari"],
                ['Max Votes/Student', $election->max_votes_per_student],
                ['Allowed Classes', $allowedClasses === null ? '(semua kelas)' : implode(', ', $allowedClasses)],
                ['Status', 'ACTIVE (is_active=true, is_locked=false)'],
            ]
        );

        return self::SUCCESS;
    }

    /**
     * Tutup (dan kunci) election OSIS aktif.
     *
     * Default: cari election aktif terbaru. Bisa spesifik dengan --id=.
     */
    private function handleClose(): int
    {
        $idOption = trim((string) $this->option('id'));

        if ($idOption !== '') {
            $election = OsisElection::find((int) $idOption);
            if (! $election) {
                $this->error("❌ Election dengan ID {$idOption} tidak ditemukan.");

                return self::FAILURE;
            }
            if ($election->is_locked && ! $election->is_active) {
                $this->warn("⚠️ Election ID {$election->id} ({$election->title}) sudah tertutup/terkunci sebelumnya.");

                return self::SUCCESS;
            }
        } else {
            $election = OsisElection::active()->latest()->first();
            if (! $election) {
                $this->error('❌ Tidak ada election yang sedang aktif untuk ditutup.');
                $this->line('   Jalankan "php artisan osis:manage status" untuk melihat daftar election,');
                $this->line('   lalu tutup election tertentu dengan: php artisan osis:manage close --id=<ID>');

                return self::FAILURE;
            }
        }

        $election->update([
            'is_active' => false,
            'is_locked' => true,
        ]);

        $this->info("✅ Election ID {$election->id} ({$election->title}) berhasil ditutup dan dikunci.");
        $this->line('   is_active = false, is_locked = true — voting tidak lagi menerima vote baru.');
        $this->line("   Total vote tersimpan: {$election->votes()->count()}");

        return self::SUCCESS;
    }

    /**
     * Tampilkan daftar election OSIS beserta statusnya.
     */
    private function handleStatus(): int
    {
        $elections = OsisElection::withCount('votes')->latest()->get();

        if ($elections->isEmpty()) {
            $this->warn('⚠️ Belum ada election OSIS di database.');
            $this->line('   Buat election baru dengan: php artisan osis:manage create --title="Judul Election"');

            return self::SUCCESS;
        }

        $rows = [];
        foreach ($elections as $election) {
            $rows[] = [
                $election->id,
                $election->title,
                $election->start_date->format('d M Y H:i'),
                $election->end_date->format('d M Y H:i'),
                $election->status_display,
                $election->is_locked ? 'Yes' : 'No',
                (string) $election->votes_count,
                $election->isCurrentlyActive() ? '✓ ACTIVE' : '—',
            ];
        }

        $this->info("📋 Daftar Election OSIS (total: {$elections->count()}):");
        $this->table(
            ['ID', 'Title', 'Start', 'End', 'Status', 'Locked', 'Votes', 'Sekarang'],
            $rows
        );

        $activeNow = $elections->filter(fn (OsisElection $election): bool => $election->isCurrentlyActive());
        if ($activeNow->isEmpty()) {
            $this->warn('⚠️ Tidak ada election yang sedang aktif saat ini. Voting tidak akan menerima vote baru.');
        } else {
            $this->info('🗳️ Election yang sedang dipakai voting app (active()->first()):');
            foreach ($activeNow as $election) {
                $this->line("   - ID {$election->id}: {$election->title} (berakhir {$election->end_date->format('d M Y H:i')})");
            }
        }

        return self::SUCCESS;
    }

    /**
     * Parse opsi --classes (pisah koma) menjadi array kelas, atau null jika tidak diset.
     *
     * Kolom allowed_classes bertipe JSON nullable di DB
     * (2025_09_27_084649_create_osis_elections_table.php) dengan
     * cast 'array' di model OsisElection.
     */
    private function parseAllowedClasses(): ?array
    {
        $classesOption = trim((string) $this->option('classes'));
        if ($classesOption === '') {
            return null; // Tidak ada batasan kelas (kolom nullable)
        }

        $classes = collect(explode(',', $classesOption))
            ->map(fn (string $class): string => trim($class))
            ->filter(fn (string $class): bool => $class !== '')
            ->values()
            ->all();

        return $classes === [] ? null : $classes;
    }

    /**
     * Reset vote test di election tertentu.
     *
     * Fitur testing: menghapus baris `votings` + mereset flag one-vote
     * (siswas.has_voted_osis, pemilihs.status) agar user yang sudah vote
     * bisa vote ulang. Election, calons, dan pemilihs TIDAK dihapus.
     *
     * One-vote dicegah 3 lapis di OSISController::processVote():
     *   1. Flag: siswas.has_voted_osis / pemilihs.status='sudah_memilih'
     *   2. Existence check: row Voting (siswa_id/pemilih_id + election_id + is_valid)
     *   3. Unique constraint DB: votings_siswa_election_unique /
     *      votings_pemilih_election_unique
     * Reset harus menghapus row Voting (lapis 3) DAN flag (lapis 1) — keduanya.
     */
    private function handleResetVote(): int
    {
        $idOption = trim((string) $this->option('id'));
        if ($idOption === '') {
            $this->error('❌ Opsi --id wajib diisi untuk action reset-vote (ID election).');
            $this->line('   Contoh: php artisan osis:manage reset-vote --id=1 --user=661');
            $this->line('           php artisan osis:manage reset-vote --id=1 --siswa=12');
            $this->line('           php artisan osis:manage reset-vote --id=1 --all');

            return self::FAILURE;
        }

        $election = OsisElection::find((int) $idOption);
        if (! $election) {
            $this->error("❌ Election dengan ID {$idOption} tidak ditemukan.");

            return self::FAILURE;
        }

        $userOption = trim((string) $this->option('user'));
        $siswaOption = trim((string) $this->option('siswa'));
        $all = (bool) $this->option('all');

        $targetCount = collect([$userOption !== '', $siswaOption !== '', $all])
            ->filter()
            ->count();

        if ($targetCount === 0) {
            $this->error('❌ Tentukan target reset: --user=, --siswa=, atau --all.');
            $this->line('   Contoh: php artisan osis:manage reset-vote --id=1 --user=661');
            $this->line('           php artisan osis:manage reset-vote --id=1 --siswa=12');
            $this->line('           php artisan osis:manage reset-vote --id=1 --all');

            return self::FAILURE;
        }

        if ($targetCount > 1) {
            $this->error('❌ Kombinasi target tidak valid — gunakan hanya salah satu: --user=, --siswa=, atau --all.');

            return self::FAILURE;
        }

        return match (true) {
            $all => $this->resetAllVotes($election),
            $userOption !== '' => $this->resetVotesByUser($election, (int) $userOption),
            default => $this->resetVotesBySiswa($election, (int) $siswaOption),
        };
    }

    /**
     * Reset vote untuk 1 user di election tertentu.
     *
     * Relasi (diverifikasi dari struktur tabel — `votings` TIDAK punya kolom user_id):
     *   - Siswa: siswas.user_id → votings.siswa_id
     *   - Guru/Pemilih: pemilihs.user_id → votings.pemilih_id
     * Keduanya ditangani agar akun siswa ATAU guru bisa di-reset.
     */
    private function resetVotesByUser(OsisElection $election, int $userId): int
    {
        if ($userId <= 0) {
            $this->error('❌ Opsi --user harus berisi ID user yang valid (angka > 0).');

            return self::FAILURE;
        }

        $siswa = Siswa::where('user_id', $userId)->first();
        $pemilihs = Pemilih::where('user_id', $userId)->get();

        if (! $siswa && $pemilihs->isEmpty()) {
            $this->error("❌ Tidak ditemukan Siswa maupun Pemilih untuk user ID {$userId}.");
            $this->line('   Pastikan user ID benar dan sudah memiliki baris di tabel siswas/pemilihs.');

            return self::FAILURE;
        }

        $result = DB::transaction(function () use ($election, $siswa, $pemilihs): array {
            $deletedVotes = 0;
            $resetSiswas = 0;
            $resetPemilihs = 0;

            // Jalur siswa: hapus Voting via siswa_id + reset flag has_voted_osis
            if ($siswa) {
                $deletedVotes += Voting::where('siswa_id', $siswa->id)
                    ->where('election_id', $election->id)
                    ->delete();

                if ($siswa->hasVotedOsis()) {
                    $siswa->resetVotingStatus(); // has_voted_osis=false + nulls voted_at/ip/ua
                    $resetSiswas = 1;
                }
            }

            // Jalur pemilih (guru + dual-tracking marker 2 milik siswa):
            // hapus Voting via pemilih_id + reset pemilihs.status → belum_memilih
            $pemilihIds = $pemilihs->pluck('id');
            if ($pemilihIds->isNotEmpty()) {
                $deletedVotes += Voting::whereIn('pemilih_id', $pemilihIds)
                    ->where('election_id', $election->id)
                    ->delete();

                $resetPemilihs = Pemilih::whereIn('id', $pemilihIds)
                    ->where('status', 'sudah_memilih')
                    ->update([
                        'status' => 'belum_memilih',
                        'waktu_memilih' => null,
                        'ip_address' => null,
                        'user_agent' => null,
                    ]);
            }

            return [$deletedVotes, $resetSiswas, $resetPemilihs];
        });

        [$deletedVotes, $resetSiswas, $resetPemilihs] = $result;

        // Invalidate cached dashboard stats (sama seperti setelah processVote)
        cache()->forget('osis_dashboard_stats');

        $this->newLine();
        $this->info("✅ Reset vote selesai — Election ID {$election->id} ({$election->title})");
        $this->table(
            ['Item', 'Nilai'],
            [
                ['Target', $siswa
                    ? "User ID {$userId} (Siswa ID {$siswa->id}: {$siswa->nama_lengkap})"
                    : "User ID {$userId} (via Pemilih: {$pemilihs->pluck('nama')->implode(', ')})"],
                ['Baris votings dihapus', (string) $deletedVotes],
                ['Flag siswas.has_voted_osis di-reset', (string) $resetSiswas],
                ['Pemilihs.status → belum_memilih', (string) $resetPemilihs],
            ]
        );
        $this->line('   Election, calons, dan pemilihs TIDAK dihapus. User bisa voting test lagi.');

        return self::SUCCESS;
    }

    /**
     * Reset vote untuk 1 siswa (via Siswa ID) di election tertentu.
     */
    private function resetVotesBySiswa(OsisElection $election, int $siswaId): int
    {
        if ($siswaId <= 0) {
            $this->error('❌ Opsi --siswa harus berisi ID siswa yang valid (angka > 0).');

            return self::FAILURE;
        }

        $siswa = Siswa::find($siswaId);
        if (! $siswa) {
            $this->error("❌ Siswa dengan ID {$siswaId} tidak ditemukan.");

            return self::FAILURE;
        }

        $result = DB::transaction(function () use ($election, $siswa): array {
            $deletedVotes = Voting::where('siswa_id', $siswa->id)
                ->where('election_id', $election->id)
                ->delete();

            $resetSiswas = 0;
            if ($siswa->hasVotedOsis()) {
                $siswa->resetVotingStatus();
                $resetSiswas = 1;
            }

            // Sinkronkan dual-tracking pemilihs (Marker 2 di processVote)
            $resetPemilihs = 0;
            if ($siswa->user_id) {
                $resetPemilihs = Pemilih::where('user_id', $siswa->user_id)
                    ->where(function ($query) {
                        $query->where('user_type', 'siswa')->orWhereNull('user_type');
                    })
                    ->where('status', 'sudah_memilih')
                    ->update([
                        'status' => 'belum_memilih',
                        'waktu_memilih' => null,
                        'ip_address' => null,
                        'user_agent' => null,
                    ]);
            }

            return [$deletedVotes, $resetSiswas, $resetPemilihs];
        });

        [$deletedVotes, $resetSiswas, $resetPemilihs] = $result;

        cache()->forget('osis_dashboard_stats');

        $this->newLine();
        $this->info("✅ Reset vote selesai — Election ID {$election->id} ({$election->title})");
        $this->table(
            ['Item', 'Nilai'],
            [
                ['Target', "Siswa ID {$siswa->id} ({$siswa->nama_lengkap}, user ID {$siswa->user_id})"],
                ['Baris votings dihapus', (string) $deletedVotes],
                ['Flag siswas.has_voted_osis di-reset', (string) $resetSiswas],
                ['Pemilihs.status → belum_memilih', (string) $resetPemilihs],
            ]
        );
        $this->line('   Election, calons, dan pemilihs TIDAK dihapus. Siswa bisa voting test lagi.');

        return self::SUCCESS;
    }

    /**
     * Reset SEMUA vote di election tertentu (testing penuh).
     *
     * Hapus semua baris `votings` di election + reset flag one-vote yang
     * masih aktif. Flag (siswas.has_voted_osis, pemilihs.status) bersifat
     * global — bukan per-election — sehingga ikut di-reset agar semua user
     * bisa voting test lagi. Election, calons, dan pemilihs TIDAK dihapus.
     */
    private function resetAllVotes(OsisElection $election): int
    {
        if (! $this->confirm("Hapus SEMUA vote di election ID {$election->id} ({$election->title}) dan reset flag terkait? (testing penuh)", true)) {
            $this->info('❌ Dibatalkan. Tidak ada vote yang direset.');

            return self::FAILURE;
        }

        $result = DB::transaction(function () use ($election): array {
            // Kumpulkan ID terpengaruh SEBELUM delete (untuk laporan)
            $affectedSiswaIds = Voting::where('election_id', $election->id)
                ->whereNotNull('siswa_id')
                ->pluck('siswa_id')
                ->unique();

            $affectedPemilihIds = Voting::where('election_id', $election->id)
                ->whereNotNull('pemilih_id')
                ->pluck('pemilih_id')
                ->unique();

            $deletedVotes = Voting::where('election_id', $election->id)->delete();

            // Flag bersifat global: reset siswas terpengaruh + semua yang masih true
            $resetSiswas = Siswa::query()
                ->where(function ($query) use ($affectedSiswaIds) {
                    $query->whereIn('id', $affectedSiswaIds)
                        ->orWhere('has_voted_osis', true);
                })
                ->update([
                    'has_voted_osis' => false,
                    'voted_at' => null,
                    'voting_ip' => null,
                    'voting_user_agent' => null,
                ]);

            // Idem untuk pemilihs.status (dual-tracking + jalur guru)
            $resetPemilihs = Pemilih::query()
                ->where(function ($query) use ($affectedPemilihIds) {
                    $query->whereIn('id', $affectedPemilihIds)
                        ->orWhere('status', 'sudah_memilih');
                })
                ->update([
                    'status' => 'belum_memilih',
                    'waktu_memilih' => null,
                    'ip_address' => null,
                    'user_agent' => null,
                ]);

            return [
                $deletedVotes,
                $resetSiswas,
                $resetPemilihs,
                $affectedSiswaIds->count(),
                $affectedPemilihIds->count(),
            ];
        });

        [$deletedVotes, $resetSiswas, $resetPemilihs, $affectedSiswas, $affectedPemilihs] = $result;

        cache()->forget('osis_dashboard_stats');

        $this->newLine();
        $this->info("✅ Reset vote TEST PENUH selesai — Election ID {$election->id} ({$election->title})");
        $this->table(
            ['Item', 'Nilai'],
            [
                ['Baris votings dihapus', (string) $deletedVotes],
                ['Siswa terpengaruh (punya vote di election)', (string) $affectedSiswas],
                ['Flag siswas.has_voted_osis di-reset', (string) $resetSiswas],
                ['Pemilih terpengaruh (punya vote di election)', (string) $affectedPemilihs],
                ['Pemilihs.status → belum_memilih', (string) $resetPemilihs],
            ]
        );
        $this->line('   Election, calons, dan pemilihs TIDAK dihapus. Semua user bisa voting test lagi.');

        return self::SUCCESS;
    }
}
