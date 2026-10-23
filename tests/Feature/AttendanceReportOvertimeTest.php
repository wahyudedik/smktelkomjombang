<?php

namespace Tests\Feature;

use App\Models\Attendance;
use App\Models\AttendanceIdentity;
use App\Models\User;
use App\Services\AttendanceExportService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Regression test F2: kolom lembur (overtime_minutes) tampil di
 * export summary, view PDF, dan web report absensi — plus perbaikan
 * dead key stats['absent'] → 'alpha'.
 *
 * Yang dikunci:
 * - exportSummary() membawa kolom "Total Lembur (menit)" per user
 * - view PDF (summary/daily/period) merender kolom lembur tanpa error
 * - web report (daily/weekly/latecomers/user-detail) menampilkan kolom
 *   "Lembur (menit)" per baris + kartu "Total Lembur (menit)"
 * - kartu statistik "Tidak Hadir" menghitung status 'alpha' (+ alias
 *   legacy 'absent'), bukan key 'absent' yang tidak pernah ditulis (selalu 0)
 * - sel status di view PDF memakai attendance_status_label() (label Indonesia)
 */
class AttendanceReportOvertimeTest extends TestCase
{
    use RefreshDatabase;

    /** PIN unik deterministik agar tidak tabrakan antar identity dalam satu test. */
    private int $pinSeq = 880000;

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

    private function createAttendance(
        AttendanceIdentity $identity,
        string $status,
        string $date,
        int $overtimeMinutes = 0,
        string $firstInTime = '07:00:00'
    ): Attendance {
        $hasTimes = in_array($status, ['present', 'late'], true);

        return Attendance::create([
            'attendance_identity_id' => $identity->id,
            'date' => $date,
            'first_in_at' => $hasTimes ? $date . ' ' . $firstInTime : null,
            'last_out_at' => $hasTimes ? $date . ' 15:00:00' : null,
            'status' => $status,
            'overtime_minutes' => $overtimeMinutes,
        ]);
    }

    private function createAdmin(): User
    {
        $admin = User::factory()->create();
        $admin->assignRole($this->getOrCreateRole('admin'));

        return $admin;
    }

    // ------------------------------------------------------------------
    // Export summary (Excel + sumber data PDF summary)
    // ------------------------------------------------------------------

    public function test_summary_export_has_total_overtime_column_per_user(): void
    {
        $identity = $this->createIdentity();
        $this->createAttendance($identity, 'present', now()->toDateString(), 90);

        $data = app(AttendanceExportService::class)->exportSummary(
            now()->subDays(6)->toDateString(),
            now()->toDateString()
        );

        // Header membawa kolom "Total Lembur (menit)" di akhir.
        $this->assertContains('Total Lembur (menit)', $data[0]);

        // Baris user membawa total lembur (90 menit) pada index kolom terakhir.
        $this->assertCount(10, $data[1]);
        $this->assertSame(90, $data[1][9]);
    }

    // ------------------------------------------------------------------
    // Web report
    // ------------------------------------------------------------------

    public function test_daily_web_report_shows_overtime_column_and_total_card(): void
    {
        $identity = $this->createIdentity();
        $this->createAttendance($identity, 'present', now()->toDateString(), 90);

        $response = $this->actingAs($this->createAdmin())->get(route('admin.absensi.report.daily', [
            'date' => now()->toDateString(),
        ]));

        $response->assertOk();

        // Kolom per baris + kartu total lembur.
        $response->assertSee('Lembur (menit)');
        $response->assertSee('90');
        $this->assertMatchesRegularExpression(
            '/<p[^>]*>Total Lembur \(menit\)<\/p>\s*<p[^>]*>90<\/p>/',
            $response->getContent()
        );
    }

    public function test_daily_web_report_absent_card_counts_alpha_and_legacy_absent_rows(): void
    {
        // 'alpha' = nilai canonical; 'absent' = alias legacy yang tidak pernah
        // ditulis penulis status mana pun — keduanya harus terhitung.
        $this->createAttendance($this->createIdentity(), 'alpha', now()->toDateString());
        $this->createAttendance($this->createIdentity(), 'absent', now()->toDateString());

        $response = $this->actingAs($this->createAdmin())->get(route('admin.absensi.report.daily', [
            'date' => now()->toDateString(),
        ]));

        $response->assertOk();

        // Kartu "Tidak Hadir" kini menghitung alpha (+alias absent) = 2,
        // bukan key dead 'absent' yang selalu 0.
        $this->assertMatchesRegularExpression(
            '/<p[^>]*>Tidak Hadir<\/p>\s*<p[^>]*>2<\/p>/',
            $response->getContent()
        );
        $this->assertDoesNotMatchRegularExpression(
            '/<p[^>]*>Tidak Hadir<\/p>\s*<p[^>]*>0<\/p>/',
            $response->getContent()
        );
    }

    public function test_weekly_web_report_shows_overtime_column_and_total_card(): void
    {
        $identity = $this->createIdentity();
        $this->createAttendance($identity, 'late', now()->subDays(1)->toDateString(), 90);

        $response = $this->actingAs($this->createAdmin())->get(route('admin.absensi.report.weekly', [
            'start_date' => now()->subDays(6)->toDateString(),
            'end_date' => now()->toDateString(),
        ]));

        $response->assertOk();

        $response->assertSee('Lembur (menit)');
        $response->assertSee('90');
        $this->assertMatchesRegularExpression(
            '/<p[^>]*>Total Lembur \(menit\)<\/p>\s*<p[^>]*>90<\/p>/',
            $response->getContent()
        );
    }

    public function test_user_detail_web_report_shows_overtime_column_and_total_card(): void
    {
        $identity = $this->createIdentity();
        $this->createAttendance($identity, 'present', now()->toDateString(), 90);

        $response = $this->actingAs($this->createAdmin())->get(route('admin.absensi.report.user-detail', [
            'identity' => $identity->id,
            'start_date' => now()->subDays(6)->toDateString(),
            'end_date' => now()->toDateString(),
        ]));

        $response->assertOk();

        $response->assertSee('Lembur (menit)');
        $response->assertSee('90');
        $this->assertMatchesRegularExpression(
            '/<p[^>]*>Total Lembur \(menit\)<\/p>\s*<p[^>]*>90<\/p>/',
            $response->getContent()
        );
    }

    public function test_latecomers_web_report_shows_overtime_column_and_total_card(): void
    {
        $identity = $this->createIdentity();
        // Masuk 08:30 — lewat threshold 07:30 agar tercatat sebagai keterlambatan.
        $this->createAttendance($identity, 'late', now()->toDateString(), 90, '08:30:00');

        $response = $this->actingAs($this->createAdmin())->get(route('admin.absensi.report.latecomers', [
            'start_date' => now()->subDays(6)->toDateString(),
            'end_date' => now()->toDateString(),
            'threshold_time' => '07:30',
        ]));

        $response->assertOk();

        $response->assertSee('Lembur (menit)');
        $response->assertSee('90');
        $this->assertMatchesRegularExpression(
            '/<p[^>]*>Total Lembur \(menit\)<\/p>\s*<p[^>]*>90<\/p>/',
            $response->getContent()
        );
    }

    // ------------------------------------------------------------------
    // View PDF (render tanpa error + kolom lembur + label status)
    // ------------------------------------------------------------------

    public function test_pdf_daily_view_renders_overtime_column_and_status_label(): void
    {
        $identity = $this->createIdentity();
        $this->createAttendance($identity, 'late', now()->toDateString(), 90);

        $attendances = Attendance::query()
            ->with(['identity.guru', 'identity.siswa', 'identity.user'])
            ->get();

        $html = view('attendance.pdf.daily', [
            'attendances' => $attendances,
            'date' => now()->startOfDay(),
            'stats' => [
                'total' => 1,
                'present' => 0,
                'alpha' => 0,
                'total_overtime_minutes' => 90,
            ],
        ])->render();

        $this->assertStringContainsString('Lembur (menit)', $html);
        $this->assertStringContainsString('90', $html);
        $this->assertStringContainsString('Total Lembur (menit)', $html);

        // Status memakai label Indonesia (attendance_status_label), bukan raw English.
        $this->assertStringContainsString('Terlambat', $html);
        $this->assertStringNotContainsString('>Late<', $html);
    }

    public function test_pdf_period_view_renders_overtime_column_and_status_label(): void
    {
        $identity = $this->createIdentity();
        $this->createAttendance($identity, 'present', now()->toDateString(), 90);

        $attendances = Attendance::query()
            ->with(['identity.guru', 'identity.siswa', 'identity.user'])
            ->get();

        $html = view('attendance.pdf.period', [
            'attendances' => $attendances,
            'startDate' => now()->subDays(6)->toDateString(),
            'endDate' => now()->toDateString(),
        ])->render();

        $this->assertStringContainsString('Lembur (menit)', $html);
        $this->assertStringContainsString('90', $html);

        $this->assertStringContainsString('Hadir', $html);
        $this->assertStringNotContainsString('>Present<', $html);
    }

    public function test_pdf_summary_view_renders_total_overtime_column(): void
    {
        $identity = $this->createIdentity();
        $this->createAttendance($identity, 'present', now()->toDateString(), 90);

        $data = app(AttendanceExportService::class)->exportSummary(
            now()->subDays(6)->toDateString(),
            now()->toDateString()
        );

        $html = view('attendance.pdf.summary', [
            'data' => $data,
            'startDate' => now()->subDays(6)->toDateString(),
            'endDate' => now()->toDateString(),
        ])->render();

        $this->assertStringContainsString('Total Lembur (menit)', $html);
        $this->assertStringContainsString('90', $html);
    }

    /**
     * Smoke test route export PDF: ketiga route harus merespons 200
     * dengan content-type PDF (generate dompdf tidak error).
     */
    public function test_pdf_export_routes_return_pdf_response(): void
    {
        $identity = $this->createIdentity();
        $this->createAttendance($identity, 'present', now()->toDateString(), 90);

        $admin = $this->createAdmin();

        $requests = [
            'admin.absensi.export.pdf.daily' => ['date' => now()->toDateString()],
            'admin.absensi.export.pdf.period' => [
                'start_date' => now()->subDays(6)->toDateString(),
                'end_date' => now()->toDateString(),
                'group_by' => 'daily',
            ],
            'admin.absensi.export.pdf.summary' => [
                'start_date' => now()->subDays(6)->toDateString(),
                'end_date' => now()->toDateString(),
            ],
        ];

        foreach ($requests as $routeName => $payload) {
            $response = $this->actingAs($admin)->post(route($routeName), $payload);

            $response->assertOk();
            $this->assertStringContainsString(
                'pdf',
                (string) $response->headers->get('Content-Type'),
                "Route {$routeName} harus mengembalikan content-type PDF."
            );
        }
    }
}
