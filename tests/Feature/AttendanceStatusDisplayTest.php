<?php

namespace Tests\Feature;

use App\Http\Controllers\PublicAttendanceController;
use App\Models\Attendance;
use App\Models\AttendanceIdentity;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Regression test F1: konsistensi tampilan status absensi.
 *
 * Nilai status di DB adalah ENGLISH (present/late/excused/alpha) — hanya
 * ditulis oleh AttendanceSync (present/late) dan MarkAlphaCommand
 * (excused/alpha). View WAJIB membandingkan nilai English tersebut dan
 * menampilkan label Indonesia via helper AttendanceHelper
 * (attendance_status_label / attendance_status_color / dst).
 *
 * Test ini mengunci:
 * - counter ringkasan 30 hari di /absensi/saya menghitung nilai DB English
 * - tabel menampilkan label Indonesia, BUKAN nilai mentah English
 * - report admin (daily/weekly/user-detail) menampilkan label yang benar
 *   untuk SEMUA status (late/excused tidak lagi salah jadi "Tidak Hadir")
 */
class AttendanceStatusDisplayTest extends TestCase
{
    use RefreshDatabase;

    /** Nilai status canonical di DB (English). */
    private const STATUSES = ['present', 'late', 'excused', 'alpha'];

    /** PIN unik deterministik agar tidak tabrakan antar identity dalam satu test. */
    private int $pinSeq = 770000;

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

    private function createAttendance(AttendanceIdentity $identity, string $status, string $date): Attendance
    {
        $hasTimes = in_array($status, ['present', 'late'], true);

        return Attendance::create([
            'attendance_identity_id' => $identity->id,
            'date' => $date,
            'first_in_at' => $hasTimes ? $date . ' 07:00:00' : null,
            'last_out_at' => $hasTimes ? $date . ' 15:00:00' : null,
            'status' => $status,
        ]);
    }

    /**
     * Satu identity dengan 4 attendance (masing-masing status) pada 4 hari terakhir.
     *
     * @return AttendanceIdentity
     */
    private function seedFourStatusesForOneIdentity(): AttendanceIdentity
    {
        $identity = $this->createIdentity();

        foreach (self::STATUSES as $i => $status) {
            $this->createAttendance($identity, $status, now()->subDays($i + 1)->toDateString());
        }

        return $identity;
    }

    // ------------------------------------------------------------------
    // Halaman publik /absensi/saya
    // ------------------------------------------------------------------

    public function test_public_summary_counters_match_english_db_values(): void
    {
        $identity = $this->seedFourStatusesForOneIdentity();

        $response = $this->withSession([PublicAttendanceController::SESSION_KEY => $identity->id])
            ->get('/absensi/saya');

        $response->assertOk();

        $content = $response->getContent();

        // Counter summary harus menghitung nilai DB English:
        // 1 hadir (present), 1 terlambat (late), 1 izin/sakit (excused),
        // 1 tidak hadir (alpha), total 4 hari.
        $this->assertMatchesRegularExpression('/<p[^>]*>Hadir<\/p>\s*<p[^>]*>1<\/p>/', $content);
        $this->assertMatchesRegularExpression('/<p[^>]*>Terlambat<\/p>\s*<p[^>]*>1<\/p>/', $content);
        $this->assertMatchesRegularExpression('/<p[^>]*>Izin\/Sakit<\/p>\s*<p[^>]*>1<\/p>/', $content);
        $this->assertMatchesRegularExpression('/<p[^>]*>Tidak Hadir<\/p>\s*<p[^>]*>1<\/p>/', $content);
        $this->assertMatchesRegularExpression('/<p[^>]*>Total Hari<\/p>\s*<p[^>]*>4<\/p>/', $content);
    }

    public function test_public_table_shows_indonesian_labels_not_raw_english_values(): void
    {
        $identity = $this->seedFourStatusesForOneIdentity();

        $response = $this->withSession([PublicAttendanceController::SESSION_KEY => $identity->id])
            ->get('/absensi/saya');

        $response->assertOk();

        // Label Indonesia tampil untuk keempat status.
        $response->assertSee('Hadir');
        $response->assertSee('Terlambat');
        $response->assertSee('Izin/Sakit');
        $response->assertSee('Tidak Hadir');

        // Nilai mentah English tidak boleh tampil sebagai isi elemen badge/teks.
        $this->assertDoesNotMatchRegularExpression(
            '/>\s*(present|late|excused|alpha)\s*</i',
            $response->getContent()
        );
    }

    // ------------------------------------------------------------------
    // Report admin
    // ------------------------------------------------------------------

    public function test_admin_daily_report_shows_correct_label_for_every_status(): void
    {
        $admin = User::factory()->create();
        $admin->assignRole($this->getOrCreateRole('admin'));

        $today = now()->toDateString();
        foreach (self::STATUSES as $status) {
            $identity = $this->createIdentity();
            $this->createAttendance($identity, $status, $today);
        }

        $response = $this->actingAs($admin)->get(route('admin.absensi.report.daily', [
            'date' => $today,
        ]));

        $response->assertOk();

        // late/excused tidak lagi jatuh ke badge "Tidak Hadir" (bug lama biner present/else).
        $response->assertSee('Hadir');
        $response->assertSee('Terlambat');
        $response->assertSee('Izin/Sakit');
        $response->assertSee('Tidak Hadir');

        $this->assertDoesNotMatchRegularExpression(
            '/>\s*(present|late|excused|alpha)\s*</i',
            $response->getContent()
        );
    }

    public function test_admin_weekly_report_shows_correct_label_for_every_status(): void
    {
        $admin = User::factory()->create();
        $admin->assignRole($this->getOrCreateRole('admin'));

        foreach (self::STATUSES as $i => $status) {
            $identity = $this->createIdentity();
            $this->createAttendance($identity, $status, now()->subDays($i + 1)->toDateString());
        }

        $response = $this->actingAs($admin)->get(route('admin.absensi.report.weekly', [
            'start_date' => now()->subDays(6)->toDateString(),
            'end_date' => now()->toDateString(),
        ]));

        $response->assertOk();

        $response->assertSee('Hadir');
        $response->assertSee('Terlambat');
        $response->assertSee('Izin/Sakit');
        $response->assertSee('Tidak Hadir');

        $this->assertDoesNotMatchRegularExpression(
            '/>\s*(present|late|excused|alpha)\s*</i',
            $response->getContent()
        );
    }

    public function test_admin_user_detail_report_shows_correct_label_for_every_status(): void
    {
        $admin = User::factory()->create();
        $admin->assignRole($this->getOrCreateRole('admin'));

        $identity = $this->seedFourStatusesForOneIdentity();

        $response = $this->actingAs($admin)->get(route('admin.absensi.report.user-detail', [
            'identity' => $identity->id,
            'start_date' => now()->subDays(6)->toDateString(),
            'end_date' => now()->toDateString(),
        ]));

        $response->assertOk();

        $response->assertSee('Hadir');
        $response->assertSee('Terlambat');
        $response->assertSee('Izin/Sakit');
        $response->assertSee('Tidak Hadir');

        $this->assertDoesNotMatchRegularExpression(
            '/>\s*(present|late|excused|alpha)\s*</i',
            $response->getContent()
        );
    }
}
