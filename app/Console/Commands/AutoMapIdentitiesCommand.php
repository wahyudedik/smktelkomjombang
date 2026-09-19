<?php

namespace App\Console\Commands;

use App\Models\AttendanceIdentity;
use App\Models\Guru;
use App\Models\Siswa;
use Illuminate\Console\Command;

class AutoMapIdentitiesCommand extends Command
{
    protected $signature = 'attendance:auto-map-identities {--dry-run : Preview tanpa insert}';

    protected $description = 'Auto-map semua siswa dan guru yang belum punya AttendanceIdentity';

    public function handle(): int
    {
        $dryRun = $this->option('dry-run');

        // Query siswa yang sudah punya identity
        $existingSiswaIds = AttendanceIdentity::where('kind', 'siswa')
            ->whereNotNull('siswa_id')
            ->pluck('siswa_id')
            ->toArray();

        // Query guru yang sudah punya identity
        $existingGuruIds = AttendanceIdentity::where('kind', 'guru')
            ->whereNotNull('guru_id')
            ->pluck('guru_id')
            ->toArray();

        $siswas = Siswa::whereNotIn('id', $existingSiswaIds)->orderBy('id')->get();
        $gurus = Guru::whereNotIn('id', $existingGuruIds)->orderBy('id')->get();

        $totalExisting = count($existingSiswaIds) + count($existingGuruIds);
        $siswaCount = 0;
        $guruCount = 0;
        $skipped = 0;

        $this->info('=== Auto-Map Attendance Identities ===');
        $this->newLine();

        if ($dryRun) {
            $this->warn('[DRY-RUN] Mode preview — tidak ada data yang di-insert');
            $this->newLine();
        }

        // Summary sebelum mulai
        $this->info("Siswa sudah punya identity : " . count($existingSiswaIds));
        $this->info("Guru sudah punya identity  : " . count($existingGuruIds));
        $this->info("Siswa belum punya identity: " . $siswas->count());
        $this->info("Guru belum punya identity : " . $gurus->count());
        $this->newLine();

        // Auto-increment counters
        $siswaPinCounter = 1001;
        $guruPinCounter = 9001;

        // Ambil semua PIN yang sudah ada untuk cek duplikat
        $existingPins = AttendanceIdentity::pluck('device_pin')->map(fn($pin) => (string) $pin)->toArray();

        // --- Proses Siswa ---
        $this->info('--- Mapping Siswa ---');

        foreach ($siswas as $siswa) {
            // Generate PIN: NIS > NISN > auto-increment
            $pin = $this->generateSiswaPin($siswa, $existingPins, $siswaPinCounter);

            if ($pin === null) {
                $skipped++;
                $this->line("  SKIP  : {$siswa->nama_lengkap} (gagal generate PIN unique)");
                continue;
            }

            // Update counter jika auto-increment
            if (! $siswa->nis && ! $siswa->nisn) {
                // PIN generated from counter, advance counter
                // Find next available pin after this one
                while (in_array((string) $siswaPinCounter, $existingPins)) {
                    $siswaPinCounter++;
                }
            }

            if (! $dryRun) {
                AttendanceIdentity::create([
                    'kind' => 'siswa',
                    'siswa_id' => $siswa->id,
                    'guru_id' => null,
                    'user_id' => null,
                    'device_pin' => $pin,
                    'is_active' => true,
                ]);
            }

            $siswaCount++;
            $this->line("  MAP   : {$siswa->nama_lengkap} → PIN {$pin}");
        }

        // --- Proses Guru ---
        $this->newLine();
        $this->info('--- Mapping Guru ---');

        foreach ($gurus as $guru) {
            // Generate PIN: NIP > auto-increment
            $pin = $this->generateGuruPin($guru, $existingPins, $guruPinCounter);

            if ($pin === null) {
                $skipped++;
                $this->line("  SKIP  : {$guru->nama_lengkap} (gagal generate PIN unique)");
                continue;
            }

            // Update counter jika auto-increment
            if (! $guru->nip) {
                while (in_array((string) $guruPinCounter, $existingPins)) {
                    $guruPinCounter++;
                }
            }

            if (! $dryRun) {
                AttendanceIdentity::create([
                    'kind' => 'guru',
                    'guru_id' => $guru->id,
                    'siswa_id' => null,
                    'user_id' => null,
                    'device_pin' => $pin,
                    'is_active' => true,
                ]);
            }

            $guruCount++;
            $this->line("  MAP   : {$guru->nama_lengkap} → PIN {$pin}");
        }

        // --- Summary ---
        $this->newLine();
        $this->info('=== Ringkasan ===');
        $this->info("Siswa baru di-map : {$siswaCount}");
        $this->info("Guru baru di-map  : {$guruCount}");
        $this->info("Total baru        : " . ($siswaCount + $guruCount));
        $this->info("Sudah ada sebelumnya: {$totalExisting}");
        $this->info("Skip (duplikat)  : {$skipped}");

        if ($dryRun) {
            $this->newLine();
            $this->warn('[DRY-RUN] Tidak ada data yang di-insert. Jalankan tanpa --dry-run untuk commit.');
        }

        return Command::SUCCESS;
    }

    /**
     * Generate PIN untuk siswa: NIS > NISN > auto-increment
     */
    private function generateSiswaPin(Siswa $siswa, array &$existingPins, int &$counter): ?string
    {
        // Coba NIS dulu
        if (! empty($siswa->nis)) {
            $pin = (string) $siswa->nis;
            if (! in_array($pin, $existingPins)) {
                $existingPins[] = $pin;
                return $pin;
            }
        }

        // Coba NISN
        if (! empty($siswa->nisn)) {
            $pin = (string) $siswa->nisn;
            if (! in_array($pin, $existingPins)) {
                $existingPins[] = $pin;
                return $pin;
            }
        }

        // Auto-increment dari counter
        while (in_array((string) $counter, $existingPins)) {
            $counter++;
        }

        $pin = (string) $counter;
        $existingPins[] = $pin;
        $counter++;

        return $pin;
    }

    /**
     * Generate PIN untuk guru: NIP > auto-increment
     */
    private function generateGuruPin(Guru $guru, array &$existingPins, int &$counter): ?string
    {
        // Coba NIP
        if (! empty($guru->nip)) {
            $pin = (string) $guru->nip;
            if (! in_array($pin, $existingPins)) {
                $existingPins[] = $pin;
                return $pin;
            }
        }

        // Auto-increment dari counter
        while (in_array((string) $counter, $existingPins)) {
            $counter++;
        }

        $pin = (string) $counter;
        $existingPins[] = $pin;
        $counter++;

        return $pin;
    }
}
