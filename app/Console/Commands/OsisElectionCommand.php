<?php

namespace App\Console\Commands;

use App\Models\OsisElection;
use Illuminate\Console\Command;

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
 */
class OsisElectionCommand extends Command
{
    protected $signature = 'osis:manage
                            {action : Aksi yang akan dilakukan (create|close|status)}
                            {--title= : Judul election (wajib untuk create)}
                            {--description= : Deskripsi election}
                            {--days=7 : Durasi election dalam hari (create)}
                            {--classes= : Kelas yang diizinkan vote, pisahkan koma (opsional, contoh: "X-1,X-2")}
                            {--max-votes=1 : Maksimal vote per siswa}
                            {--id= : ID election tertentu (opsional, untuk close)}';

    protected $description = 'Buat dan kelola election OSIS (create|close|status) — tanpa UI CRUD';

    public function handle(): int
    {
        $action = strtolower(trim((string) $this->argument('action')));

        return match ($action) {
            'create' => $this->handleCreate(),
            'close' => $this->handleClose(),
            'status' => $this->handleStatus(),
            default => $this->handleInvalidAction($action),
        };
    }

    /**
     * Handle action yang tidak dikenal.
     */
    private function handleInvalidAction(string $action): int
    {
        $this->error("❌ Action tidak dikenal: \"{$action}\"");
        $this->line('   Action yang tersedia: create | close | status');
        $this->line('   Contoh: php artisan osis:manage create --title="Pemilihan OSIS 2026"');
        $this->line('           php artisan osis:manage status');
        $this->line('           php artisan osis:manage close');

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
}
