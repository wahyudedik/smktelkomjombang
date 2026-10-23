<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;

/**
 * Unit test untuk mapping status absensi di AttendanceHelper.
 *
 * Nilai canonical di DB adalah English: present, late, excused, alpha
 * (+ alias legacy absent). Helper adalah single source of truth untuk
 * label Indonesia yang ditampilkan ke user.
 */
class AttendanceStatusHelperTest extends TestCase
{
    public function test_maps_each_english_db_status_to_indonesian_label(): void
    {
        $this->assertSame('Hadir', attendance_status_label('present'));
        $this->assertSame('Terlambat', attendance_status_label('late'));
        $this->assertSame('Izin/Sakit', attendance_status_label('excused'));
        $this->assertSame('Tidak Hadir', attendance_status_label('alpha'));
    }

    public function test_legacy_absent_alias_maps_to_tidak_hadir(): void
    {
        $this->assertSame('Tidak Hadir', attendance_status_label('absent'));
    }

    public function test_unknown_or_null_status_falls_back_to_raw_value(): void
    {
        $this->assertSame('sakit', attendance_status_label('sakit'));
        $this->assertSame('izin', attendance_status_label('izin'));
        $this->assertSame('', attendance_status_label(null));
    }

    public function test_status_colors_and_backgrounds_are_provided(): void
    {
        $this->assertSame('#2e7d32', attendance_status_color('present'));
        $this->assertSame('#e65100', attendance_status_color('late'));
        $this->assertSame('#1565c0', attendance_status_color('excused'));
        $this->assertSame('#c62828', attendance_status_color('alpha'));

        $this->assertSame('#e8f5e9', attendance_status_bg('present'));
        $this->assertSame('#fff3e0', attendance_status_bg('late'));
        $this->assertSame('#e3f2fd', attendance_status_bg('excused'));
        $this->assertSame('#ffebee', attendance_status_bg('alpha'));
    }

    public function test_badge_classes_are_provided_for_all_statuses(): void
    {
        $this->assertNotEmpty(attendance_status_badge_class('present'));
        $this->assertNotEmpty(attendance_status_badge_class('late'));
        $this->assertNotEmpty(attendance_status_badge_class('excused'));
        $this->assertNotEmpty(attendance_status_badge_class('alpha'));
        $this->assertNotEmpty(attendance_status_badge_class('unknown-status'));
    }

    public function test_map_returns_full_array_with_label_color_bg_and_badge_class(): void
    {
        $map = attendance_status_map('present');

        $this->assertSame('Hadir', $map['label']);
        $this->assertArrayHasKey('color', $map);
        $this->assertArrayHasKey('bg', $map);
        $this->assertArrayHasKey('badge_class', $map);
    }
}
