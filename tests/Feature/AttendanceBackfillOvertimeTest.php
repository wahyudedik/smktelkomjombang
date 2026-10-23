<?php

namespace Tests\Feature;

use App\Models\Attendance;
use App\Models\AttendanceIdentity;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Regresi F3: perintah `attendance:backfill-overtime` untuk data absensi
 * historis (baris yang dihasilkan SEBELUM migrasi overtime_minutes, sehingga
 * nilainya selalu 0 dan tampilan lembur di report/export kosong).
 *
 * Yang dikunci:
 * - baris historis terisi sesuai rumus AttendanceSync (abs + round, menit)
 * - baris tanpa last_out_at tetap 0
 * - idempoten: run kedua tidak mengubah apa pun (0 diupdate)
 * - --dry-run tidak menyimpan apa pun ke DB
 * - overtime nonaktif → target 0 (konsisten dengan AttendanceSync)
 * - --since/--until membatasi baris yang disentuh
 * - baris bernilai non-zero TIDAK di-overwrite tanpa --force,
 *   dan di-overwrite dengan --force
 * - HANYA kolom overtime_minutes yang boleh berubah
 */
class AttendanceBackfillOvertimeTest extends TestCase
{
    use RefreshDatabase;

    /** PIN unik deterministik agar tidak tabrakan antar identity dalam satu test. */
    private int $pinSeq = 970000;

    private function createIdentity(): AttendanceIdentity
    {
        $user = User::factory()->create();

        return AttendanceIdentity::create([
            'kind' => 'user',
            'user_id' => $user->id,
            'device_pin' => (string) (++$this->pinSeq),
            'is_active' => true,
        ]);
    }

    /**
     * Buat baris absensi "historis": tanpa proses sync, overtime_minutes
     * default 0 (kecuali $overtimeMinutes diisi eksplisit).
     */
    private function createAttendance(
        AttendanceIdentity $identity,
        string $date,
        ?string $lastOutTime = '17:30:00',
        int $overtimeMinutes = 0,
        string $status = 'present'
    ): Attendance {
        return Attendance::create([
            'attendance_identity_id' => $identity->id,
            'date' => $date,
            'first_in_at' => $date . ' 07:00:00',
            'last_out_at' => $lastOutTime ? $date . ' ' . $lastOutTime : null,
            'status' => $status,
            'overtime_minutes' => $overtimeMinutes,
        ]);
    }

    public function test_backfill_fills_historical_row_using_sync_formula(): void
    {
        config()->set('attendance.overtime_enabled', true);
        config()->set('attendance.overtime_start', '16:00');

        $identity = $this->createIdentity();
        // Pulang 17:30, overtime_start 16:00 → 90 menit (rumus abs + round).
        $attendance = $this->createAttendance($identity, '2026-02-05');

        $this->artisan('attendance:backfill-overtime')
            ->expectsOutputToContain('Diupdate: 1')
            ->assertExitCode(0);

        $attendance->refresh();
        $this->assertSame(90, $attendance->overtime_minutes);

        // Konsistensi dengan rumus AttendanceSync (data hasil sync = 90 juga).
        $this->assertSame(90, attendance_overtime_minutes($attendance->date, $attendance->last_out_at));

        // HANYA overtime_minutes yang boleh berubah.
        $this->assertSame('present', $attendance->status);
        $this->assertSame('07:00:00', $attendance->first_in_at->format('H:i:s'));
        $this->assertSame('17:30:00', $attendance->last_out_at->format('H:i:s'));
        $this->assertSame('2026-02-05', $attendance->date->toDateString());
    }

    public function test_backfill_keeps_zero_when_no_last_out_at(): void
    {
        config()->set('attendance.overtime_enabled', true);
        config()->set('attendance.overtime_start', '16:00');

        $identity = $this->createIdentity();
        $attendance = $this->createAttendance($identity, '2026-02-06', lastOutTime: null);

        $this->artisan('attendance:backfill-overtime')
            ->expectsOutputToContain('Diupdate: 0')
            ->assertExitCode(0);

        $this->assertSame(0, $attendance->refresh()->overtime_minutes);
    }

    public function test_backfill_is_idempotent_on_second_run(): void
    {
        config()->set('attendance.overtime_enabled', true);
        config()->set('attendance.overtime_start', '16:00');

        $identity = $this->createIdentity();
        $this->createAttendance($identity, '2026-02-05');

        // Run pertama: baris 0 → 90.
        $this->artisan('attendance:backfill-overtime')
            ->expectsOutputToContain('Diupdate: 1')
            ->assertExitCode(0);

        $this->assertSame(90, Attendance::firstOrFail()->overtime_minutes);

        // Run kedua: sudah sesuai rumus → 0 diupdate, nilai tidak berubah.
        $this->artisan('attendance:backfill-overtime')
            ->expectsOutputToContain('Diupdate: 0')
            ->expectsOutputToContain('Tidak berubah: 1')
            ->assertExitCode(0);

        $this->assertSame(90, Attendance::firstOrFail()->overtime_minutes);
    }

    public function test_dry_run_does_not_change_database(): void
    {
        config()->set('attendance.overtime_enabled', true);
        config()->set('attendance.overtime_start', '16:00');

        $identity = $this->createIdentity();
        $attendance = $this->createAttendance($identity, '2026-02-05');

        $this->artisan('attendance:backfill-overtime', ['--dry-run' => true])
            ->expectsOutputToContain('DRY RUN')
            ->expectsOutputToContain('Diupdate: 1')
            ->assertExitCode(0);

        // Tidak ada perubahan tersimpan di DB.
        $this->assertSame(0, $attendance->refresh()->overtime_minutes);
    }

    public function test_overtime_disabled_targets_zero_and_writes_nothing(): void
    {
        config()->set('attendance.overtime_enabled', false);
        config()->set('attendance.overtime_start', '16:00');

        $identity = $this->createIdentity();
        // Pulang 17:30 tapi overtime nonaktif → target tetap 0 (sama seperti
        // AttendanceSync yang menulis 0 saat disabled), tidak ada baris ditulis.
        $attendance = $this->createAttendance($identity, '2026-02-05');

        $this->artisan('attendance:backfill-overtime')
            ->expectsOutputToContain('NONAKTIF')
            ->expectsOutputToContain('Diupdate: 0')
            ->assertExitCode(0);

        $this->assertSame(0, $attendance->refresh()->overtime_minutes);
    }

    public function test_since_and_until_limit_touched_rows(): void
    {
        config()->set('attendance.overtime_enabled', true);
        config()->set('attendance.overtime_start', '16:00');

        $identity = $this->createIdentity();
        $before = $this->createAttendance($identity, '2026-01-10');
        $inside = $this->createAttendance($identity, '2026-01-15');
        $after = $this->createAttendance($identity, '2026-01-20');

        $this->artisan('attendance:backfill-overtime', [
            '--since' => '2026-01-12',
            '--until' => '2026-01-18',
        ])->assertExitCode(0);

        $this->assertSame(0, $before->refresh()->overtime_minutes);
        $this->assertSame(90, $inside->refresh()->overtime_minutes);
        $this->assertSame(0, $after->refresh()->overtime_minutes);
    }

    public function test_existing_non_zero_value_not_overwritten_without_force(): void
    {
        config()->set('attendance.overtime_enabled', true);
        config()->set('attendance.overtime_start', '16:00');

        $identity = $this->createIdentity();
        // Nilai lama 45 (bukan hasil rumus 90) → dilindungi tanpa --force.
        $attendance = $this->createAttendance($identity, '2026-02-05', overtimeMinutes: 45);

        $this->artisan('attendance:backfill-overtime')
            ->expectsOutputToContain('Dilewati (sudah > 0): 1')
            ->expectsOutputToContain('Diupdate: 0')
            ->assertExitCode(0);

        $this->assertSame(45, $attendance->refresh()->overtime_minutes);
    }

    public function test_force_overwrites_existing_non_zero_value(): void
    {
        config()->set('attendance.overtime_enabled', true);
        config()->set('attendance.overtime_start', '16:00');

        $identity = $this->createIdentity();
        $attendance = $this->createAttendance($identity, '2026-02-05', overtimeMinutes: 45);

        $this->artisan('attendance:backfill-overtime', ['--force' => true])
            ->expectsOutputToContain('Diupdate: 1')
            ->assertExitCode(0);

        // Ter-overwrite ke hasil rumus (90), bukan skema lama (45).
        $this->assertSame(90, $attendance->refresh()->overtime_minutes);
    }

    public function test_force_with_overtime_disabled_resets_value_to_zero(): void
    {
        config()->set('attendance.overtime_enabled', false);

        $identity = $this->createIdentity();
        $attendance = $this->createAttendance($identity, '2026-02-05', overtimeMinutes: 45);

        // Tanpa --force → dilindungi.
        $this->artisan('attendance:backfill-overtime')->assertExitCode(0);
        $this->assertSame(45, $attendance->refresh()->overtime_minutes);

        // Dengan --force → target 0 (konsisten dengan AttendanceSync disabled).
        $this->artisan('attendance:backfill-overtime', ['--force' => true])
            ->expectsOutputToContain('Diupdate: 1')
            ->assertExitCode(0);

        $this->assertSame(0, $attendance->refresh()->overtime_minutes);
    }

    public function test_invalid_date_option_fails_gracefully(): void
    {
        $this->artisan('attendance:backfill-overtime', ['--since' => 'bukan-tanggal'])
            ->expectsOutputToContain('YYYY-MM-DD')
            ->assertExitCode(1);
    }
}
