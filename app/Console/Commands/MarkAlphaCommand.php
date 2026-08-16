<?php

namespace App\Console\Commands;

use App\Models\Attendance;
use App\Models\AttendanceExcuse;
use App\Models\AttendanceIdentity;
use Carbon\Carbon;
use Illuminate\Console\Command;

/**
 * Command untuk menandai user yang tidak hadir sebagai "alpha"
 *
 * Dijalankan setiap malam (jam 23:00) via scheduler.
 * Logic: Untuk setiap AttendanceIdentity yang aktif, cek apakah ada
 * record attendances untuk hari ini. Jika tidak ada, cek apakah ada
 * attendance_excuses yang approved. Jika tidak ada, buat record dengan
 * status 'alpha'.
 *
 * Usage: php artisan attendance:mark-alpha
 * Options: --date=Y-m-D (default: hari ini)
 */
class MarkAlphaCommand extends Command
{
    protected $signature = 'attendance:mark-alpha
                            {--date= : Tanggal yang akan ditandai (format: Y-m-d, default: hari ini)}';

    protected $description = 'Tandai user yang tidak hadir sebagai alpha jika tidak ada izin/sakit yang disetujui';

    public function handle(): int
    {
        $date = $this->option('date')
            ? Carbon::parse($this->option('date'))->startOfDay()
            : Carbon::today();

        $this->info("📅 Memproses penandaan alpha untuk tanggal: {$date->format('d F Y')}");

        // Ambil semua identity yang aktif
        $identities = AttendanceIdentity::where('is_active', true)->get();
        $totalIdentities = $identities->count();

        $this->info("👥 Total identity aktif: {$totalIdentities}");

        if ($totalIdentities === 0) {
            $this->warn('⚠️ Tidak ada identity aktif. Proses selesai.');
            return Command::SUCCESS;
        }

        $markedAlpha = 0;
        $alreadyPresent = 0;
        $hasExcuse = 0;
        $skipped = 0;

        foreach ($identities as $identity) {
            // Cek apakah sudah ada record attendance untuk hari ini
            $existingAttendance = Attendance::where('attendance_identity_id', $identity->id)
                ->whereDate('date', $date)
                ->first();

            if ($existingAttendance) {
                $alreadyPresent++;
                continue;
            }

            // Cek apakah ada izin/sakit yang disetujui
            $approvedExcuse = AttendanceExcuse::where('attendance_identity_id', $identity->id)
                ->whereDate('date', $date)
                ->where('status', 'approved')
                ->first();

            if ($approvedExcuse) {
                // Buat record attendance dengan status 'excused'
                Attendance::create([
                    'attendance_identity_id' => $identity->id,
                    'date' => $date->toDateString(),
                    'status' => 'excused',
                ]);

                $hasExcuse++;
                $this->line("  ✅ {$this->getNama($identity)} — Disetujui ({$approvedExcuse->type})");
                continue;
            }

            // Tandai sebagai alpha
            Attendance::create([
                'attendance_identity_id' => $identity->id,
                'date' => $date->toDateString(),
                'status' => 'alpha',
            ]);

            $markedAlpha++;
            $this->line("  ❌ {$this->getNama($identity)} — Alpha");
        }

        // Ringkasan
        $this->newLine();
        $this->info('📊 Ringkasan:');
        $this->line("   Hadir/sudah ada  : {$alreadyPresent}");
        $this->line("   Izin/sakit disetujui: {$hasExcuse}");
        $this->line("   Alpha (baru)     : {$markedAlpha}");
        $this->line("   Total diproses   : {$totalIdentities}");

        if ($markedAlpha > 0) {
            $this->info("✅ {$markedAlpha} user berhasil ditandai sebagai alpha.");
        } else {
            $this->info('✅ Tidak ada user baru yang ditandai alpha.');
        }

        return Command::SUCCESS;
    }

    /**
     * Ambil nama dari identity
     */
    private function getNama(AttendanceIdentity $identity): string
    {
        return $identity->user?->name
            ?? $identity->guru?->nama_lengkap
            ?? $identity->siswa?->nama_lengkap
            ?? "PIN:{$identity->device_pin}";
    }
}
