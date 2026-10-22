<?php

namespace Tests\Feature;

use App\Models\Attendance;
use App\Models\AttendanceIdentity;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

/**
 * Regresi untuk persist kolom `attendances.overtime_minutes`.
 *
 * Sebelum fix: hasil hitungan lembur di AttendanceSync hanya di-log
 * (Log::info "Overtime detected") dan DIBUANG — tidak pernah tersimpan.
 * Sesudah fix: nilai menit lembur (SATUAN MENIT, POSITIF) persist ke DB.
 */
class AttendanceOvertimePersistenceTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Ingest log absensi via endpoint iClock (sumber data attendance:sync),
     * pola sama dengan AttendanceIClockIngestTest.
     */
    private function ingestLogs(string $payload): void
    {
        config()->set('attendance.require_user_identity', false);

        $this->call('POST', '/iclock/cdata?SN=DEV-OT', [], [], [], [
            'CONTENT_TYPE' => 'text/plain',
        ], $payload)->assertOk();
    }

    private function createIdentityForPin(string $pin): void
    {
        $user = User::factory()->create();

        AttendanceIdentity::create([
            'kind' => 'user',
            'user_id' => $user->id,
            'device_pin' => $pin,
            'is_active' => true,
        ]);
    }

    public function test_attendances_table_has_overtime_minutes_column(): void
    {
        $this->assertTrue(Schema::hasColumn('attendances', 'overtime_minutes'));
    }

    public function test_overtime_minutes_persisted_when_overtime_detected(): void
    {
        config()->set('attendance.overtime_enabled', true);
        config()->set('attendance.overtime_start', '16:00');

        // Pulang 17:30, overtime_start 16:00 → 90 menit lembur.
        $this->ingestLogs("1\t2026-02-05 07:00:00\t0\t1\n1\t2026-02-05 17:30:00\t0\t1");
        $this->createIdentityForPin('1');

        $this->artisan('attendance:sync')->assertExitCode(0);

        $this->assertDatabaseCount('attendances', 1);

        $attendance = Attendance::firstOrFail();
        $this->assertSame('2026-02-05', $attendance->date->toDateString());
        $this->assertSame('07:00:00', $attendance->first_in_at->format('H:i:s'));
        $this->assertSame('17:30:00', $attendance->last_out_at->format('H:i:s'));
        $this->assertSame(90, $attendance->overtime_minutes);
    }

    public function test_overtime_minutes_uses_minutes_unit_and_is_positive(): void
    {
        config()->set('attendance.overtime_enabled', true);
        config()->set('attendance.overtime_start', '16:00');

        // Pulang 18:15, overtime_start 16:00 → 135 menit (2 jam 15 menit).
        $this->ingestLogs("1\t2026-02-05 08:00:00\t0\t1\n1\t2026-02-05 18:15:00\t0\t1");
        $this->createIdentityForPin('1');

        $this->artisan('attendance:sync')->assertExitCode(0);

        $attendance = Attendance::firstOrFail();
        // SATUAN MENIT (bukan jam) dan POSITIF (Carbon 3 diffInMinutes signed
        // → dinormalkan via abs() di AttendanceSync).
        $this->assertSame(135, $attendance->overtime_minutes);
    }

    public function test_overtime_minutes_is_zero_when_no_overtime(): void
    {
        config()->set('attendance.overtime_enabled', true);
        config()->set('attendance.overtime_start', '16:00');

        // Pulang 15:00 (sebelum 16:00) → tidak ada lembur.
        $this->ingestLogs("1\t2026-02-05 07:00:00\t0\t1\n1\t2026-02-05 15:00:00\t0\t1");
        $this->createIdentityForPin('1');

        $this->artisan('attendance:sync')->assertExitCode(0);

        $attendance = Attendance::firstOrFail();
        $this->assertSame(0, $attendance->overtime_minutes);
    }

    public function test_overtime_minutes_is_zero_when_overtime_disabled(): void
    {
        config()->set('attendance.overtime_enabled', false);
        config()->set('attendance.overtime_start', '16:00');

        // Pulang 17:30 TAPI overtime_enabled = false → tetap 0.
        $this->ingestLogs("1\t2026-02-05 07:00:00\t0\t1\n1\t2026-02-05 17:30:00\t0\t1");
        $this->createIdentityForPin('1');

        $this->artisan('attendance:sync')->assertExitCode(0);

        $attendance = Attendance::firstOrFail();
        $this->assertSame(0, $attendance->overtime_minutes);
        // last_out_at tetap tersimpan meski kalkulasi lembur nonaktif.
        $this->assertSame('17:30:00', $attendance->last_out_at->format('H:i:s'));
    }

    public function test_overtime_pay_accessor_estimates_nominal_from_rate(): void
    {
        config()->set('attendance.overtime_enabled', true);
        config()->set('attendance.overtime_start', '16:00');
        config()->set('attendance.overtime_rate_per_hour', 10000);

        // 90 menit = 1,5 jam * Rp10.000/jam = Rp15.000.
        $this->ingestLogs("1\t2026-02-05 07:00:00\t0\t1\n1\t2026-02-05 17:30:00\t0\t1");
        $this->createIdentityForPin('1');

        $this->artisan('attendance:sync')->assertExitCode(0);

        $attendance = Attendance::firstOrFail();
        $this->assertSame(90, $attendance->overtime_minutes);
        $this->assertSame(15000, $attendance->overtime_pay);
    }
}
