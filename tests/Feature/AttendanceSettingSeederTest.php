<?php

namespace Tests\Feature;

use App\Models\AttendanceSetting;
use Database\Seeders\AttendanceSettingSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Regresi akar masalah production (maudu-rejoso.sch.id):
 * `ATTENDANCE_OVERTIME_ENABLED=true` di .env tapi
 * `php artisan attendance:backfill-overtime --dry-run` melaporkan
 * "Overtime : NONAKTIF".
 *
 * Root cause: AttendanceSettingSeeder menulis `overtime_enabled = false`
 * (hardcoded) ke tabel `attendance_settings` dengan updateOrCreate. Karena
 * attendance_config() resolve DB → config/env, row seed men-override env —
 * dan setiap re-seed saat deploy mereset value yang sudah diset admin via UI.
 *
 * Yang dikunci di sini:
 * - Seed = MATERIALISASI DEFAULT (firstOrCreate): tidak menimpa row yang
 *   sudah ada (override admin via UI Attendance Settings dipertahankan saat
 *   deploy berikutnya).
 * - Value seeded group overtime SELALU konsisten dengan config('attendance.*')
 *   efektif (env-aware), bukan hardcoded yang menimpa env produksi.
 * - Pada default config (overtime_enabled=false), seed memang menghasilkan
 *   false — konsisten dengan default config, bukan kebetulan.
 */
class AttendanceSettingSeederTest extends TestCase
{
    use RefreshDatabase;

    /** Jumlah setting yang di-materialisasi seeder (dicek agar tidak berubah diam-diam). */
    private const EXPECTED_SEED_COUNT = 33;

    private function seedSettings(): void
    {
        $this->seed(AttendanceSettingSeeder::class);
    }

    public function test_seeder_materializes_effective_config_value_when_row_missing(): void
    {
        // Simulasikan production: env/config overtime AKTIF.
        config()->set('attendance.overtime_enabled', true);
        config()->set('attendance.overtime_start', '16:00');
        config()->set('attendance.overtime_rate_per_hour', 0);

        $this->seedSettings();

        // Seed harus mengikuti config efektif (env-aware), bukan hardcoded false
        // yang dulu men-override ATTENDANCE_OVERTIME_ENABLED=true di production.
        $this->assertTrue(AttendanceSetting::get('overtime_enabled'));
        $this->assertSame('16:00', AttendanceSetting::get('overtime_start'));

        // attendance_config() (DB → config) konsisten: overtime dianggap AKTIF.
        $this->assertTrue(attendance_config('overtime_enabled'));
    }

    public function test_seeder_does_not_overwrite_existing_admin_setting(): void
    {
        // Admin sudah mengaktifkan overtime lewat UI Attendance Settings.
        AttendanceSetting::updateOrCreate(
            ['key' => 'overtime_enabled'],
            ['value' => 'true', 'type' => 'boolean']
        );

        // Re-seed saat deploy — config efektif saat itu false (env tidak diset).
        config()->set('attendance.overtime_enabled', false);

        $this->seedSettings();

        // Override admin TIDAK boleh ditimpa oleh seeder (materialisasi default).
        $this->assertTrue(AttendanceSetting::get('overtime_enabled'));
        $this->assertTrue(attendance_config('overtime_enabled'));
    }

    public function test_seeder_matches_config_defaults_on_fresh_table(): void
    {
        // Env overtime tidak diset di testing → config default = false.
        $this->seedSettings();

        $this->assertFalse(config('attendance.overtime_enabled'));
        $this->assertFalse(AttendanceSetting::get('overtime_enabled'));

        // Seluruh materialisasi default ada (tidak kehilangan key saat refactor).
        $this->assertSame(self::EXPECTED_SEED_COUNT, AttendanceSetting::count());

        // Konsistensi seed ↔ config efektif untuk group overtime.
        $this->assertSame(
            (bool) config('attendance.overtime_enabled'),
            AttendanceSetting::get('overtime_enabled')
        );
        $this->assertSame(
            (string) config('attendance.overtime_start'),
            AttendanceSetting::get('overtime_start')
        );
        // Type DB 'integer' meng-cast nilai efektif ke int (env bisa string '0').
        $this->assertSame(
            (int) config('attendance.overtime_rate_per_hour'),
            AttendanceSetting::get('overtime_rate_per_hour')
        );
    }

    public function test_seeder_is_idempotent_across_multiple_runs(): void
    {
        $this->seedSettings();
        $firstCount = AttendanceSetting::count();

        $this->seedSettings();

        $this->assertSame($firstCount, AttendanceSetting::count());
        $this->assertSame(self::EXPECTED_SEED_COUNT, $firstCount);
    }
}
