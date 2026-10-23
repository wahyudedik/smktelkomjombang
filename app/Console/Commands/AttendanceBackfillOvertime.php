<?php

namespace App\Console\Commands;

use App\Models\Attendance;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;

/**
 * Backfill kolom attendances.overtime_minutes untuk data absensi HISTORIS.
 *
 * Kolom overtime_minutes dibuat migrasi 2026_10_22_000001 (unsignedInteger,
 * default 0). Hanya baris yang di-sync SETELAH migrasi yang mendapat nilai
 * lembur, sehingga semua data lama bernilai 0 dan tampilan lembur di
 * report/export (PDF/web/summary) ikut kosong. Perintah ini mengisi ulang
 * nilai tersebut dengan rumus YANG SAMA persis dengan AttendanceSync
 * (lewat helper attendance_overtime_minutes()) sehingga hasilnya deterministik
 * dan konsisten dengan data hasil sync baru.
 *
 * Perilaku (idempoten & aman dijalankan berulang):
 * - Baris dengan overtime_minutes > 0 TIDAK di-overwrite tanpa --force
 *   (run kedua setelah backfill sukses = 0 diupdate, tidak ada perubahan).
 * - Jika overtime NONAKTIF (attendance_config('overtime_enabled') = false),
 *   target semua baris = 0 (konsisten dengan AttendanceSync yang menulis 0
 *   saat disabled); baris bernilai > 0 tetap di-skip kecuali diberi --force.
 * - Hanya kolom overtime_minutes yang ditulis — status, first_in_at,
 *   last_out_at, dan kolom lain TIDAK disentuh.
 *
 * Usage (production):
 *   php artisan attendance:backfill-overtime --dry-run
 *   php artisan attendance:backfill-overtime
 *   php artisan attendance:backfill-overtime --since=2026-01-01 --until=2026-06-30
 *   php artisan attendance:backfill-overtime --force
 */
class AttendanceBackfillOvertime extends Command
{
    protected $signature = 'attendance:backfill-overtime
        {--since= : Batas awal tanggal absensi (YYYY-MM-DD; default: semua data historis)}
        {--until= : Batas akhir tanggal absensi (YYYY-MM-DD; default: tanpa batas)}
        {--dry-run : Hitung dan tampilkan apa yang AKAN diubah tanpa menyimpan ke DB}
        {--force : Izinkan overwrite baris yang sudah overtime_minutes > 0 (default: lewati baris > 0)}';

    protected $description = 'Backfill overtime_minutes untuk data absensi historis (jika overtime nonaktif → semua target 0)';

    public function handle(): int
    {
        try {
            $since = $this->parseDateOption('since');
            $until = $this->parseDateOption('until');
        } catch (\Throwable) {
            $this->error('Format --since/--until harus YYYY-MM-DD (contoh: 2026-01-01).');

            return self::FAILURE;
        }

        if ($since && $until && $since->gt($until)) {
            $this->error('--since tidak boleh lebih akhir dari --until.');

            return self::FAILURE;
        }

        $overtimeEnabled = (bool) attendance_config('overtime_enabled', false);
        $overtimeStart = (string) attendance_config('overtime_start', '16:00');
        $dryRun = (bool) $this->option('dry-run');
        $force = (bool) $this->option('force');

        $this->info('📋 Backfill overtime_minutes' . ($dryRun ? ' — DRY RUN (tidak ada perubahan disimpan)' : ''));
        $this->line('   Rentang   : ' . ($since?->toDateString() ?? 'semua') . ' s.d. ' . ($until?->toDateString() ?? 'semua'));
        $this->line('   Overtime  : ' . ($overtimeEnabled ? "AKTIF (mulai jam {$overtimeStart})" : 'NONAKTIF → semua target = 0'));
        $this->line('   Overwrite : ' . ($force ? 'ya (--force)' : 'tidak (baris > 0 dilewati)'));
        $this->newLine();

        $query = Attendance::query()->orderBy('id');

        if ($since) {
            $query->where('date', '>=', $since->toDateString());
        }

        if ($until) {
            $query->where('date', '<=', $until->toDateString());
        }

        $checked = 0;
        $updated = 0;
        $skipped = 0;
        $unchanged = 0;

        // lazyById → chunk hemat memori untuk dataset produksi besar; pembaruan
        // di tempat tidak memengaruhi paging karena kolom id tidak pernah berubah.
        $query->lazyById(500)->each(function (Attendance $attendance) use (
            &$checked,
            &$updated,
            &$skipped,
            &$unchanged,
            $dryRun,
            $force
        ): void {
            $checked++;

            $target = attendance_overtime_minutes($attendance->date, $attendance->last_out_at);
            $current = (int) $attendance->overtime_minutes;

            // Sudah sesuai rumus → tidak ada yang perlu ditulis (idempoten).
            if ($current === $target) {
                $unchanged++;

                return;
            }

            // Baris yang sudah pernah terisi (> 0) dilindungi tanpa --force.
            if ($current > 0 && !$force) {
                $skipped++;

                return;
            }

            $updated++;

            if (!$dryRun) {
                // HANYA kolom overtime_minutes yang disentuh.
                $attendance->forceFill(['overtime_minutes' => $target])->save();
            }
        });

        $this->info('📊 Ringkasan' . ($dryRun ? ' (dry-run — nilai berikut yang AKAN diubah)' : '') . ':');
        $this->line("   Dicek: {$checked}");
        $this->line("   Diupdate: {$updated}");
        $this->line("   Dilewati (sudah > 0): {$skipped}");
        $this->line("   Tidak berubah: {$unchanged}");

        if ($dryRun) {
            $this->newLine();
            $this->warn('🔎 Dry run — tidak ada data yang disimpan. Jalankan ulang tanpa --dry-run untuk menerapkan.');
        } elseif ($updated > 0) {
            $this->info("✅ {$updated} baris berhasil diupdate.");
        } else {
            $this->info('✅ Tidak ada baris yang perlu diupdate.');
        }

        return self::SUCCESS;
    }

    /**
     * Parse opsi tanggal (YYYY-MM-DD) → Carbon; null bila kosong.
     *
     * @throws \Throwable bila format tidak valid
     */
    private function parseDateOption(string $name): ?Carbon
    {
        $value = $this->option($name);

        if (!is_string($value) || $value === '') {
            return null;
        }

        return Carbon::createFromFormat('Y-m-d', $value)->startOfDay();
    }
}
