<?php

namespace Tests\Feature;

use App\Models\Attendance;
use App\Models\AttendanceCommand;
use App\Models\AttendanceDevice;
use App\Models\AttendanceIdentity;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AttendanceIClockIngestTest extends TestCase
{
    use RefreshDatabase;

    public function test_iclock_ingest_creates_device_and_logs(): void
    {
        $payload = "1\t2026-02-05 07:00:00\t0\t1\n2\t2026-02-05 07:05:00\t0\t1";

        $response = $this->call('POST', '/iclock/cdata?SN=DEV123', [], [], [], [
            'CONTENT_TYPE' => 'text/plain',
        ], $payload);

        $response->assertOk();

        $this->assertDatabaseHas('attendance_devices', [
            'serial_number' => 'DEV123',
        ]);

        $this->assertDatabaseCount('attendance_logs', 2);

        $this->call('POST', '/iclock/cdata?SN=DEV123', [], [], [], [
            'CONTENT_TYPE' => 'text/plain',
        ], $payload)->assertOk();

        $this->assertDatabaseCount('attendance_logs', 2);
    }

    public function test_attendance_sync_builds_daily_attendance(): void
    {
        $payload = "1\t2026-02-05 07:00:00\t0\t1\n1\t2026-02-05 16:00:00\t0\t1";
        $this->call('POST', '/iclock/cdata?SN=DEV123', [], [], [], [
            'CONTENT_TYPE' => 'text/plain',
        ], $payload)->assertOk();

        $user = User::factory()->create();

        AttendanceIdentity::create([
            'kind' => 'user',
            'user_id' => $user->id,
            'device_pin' => '1',
            'is_active' => true,
        ]);

        $this->artisan('attendance:sync')->assertExitCode(0);

        $this->assertDatabaseCount('attendances', 1);

        $attendance = Attendance::firstOrFail();
        $this->assertSame('2026-02-05', $attendance->date->toDateString());
        $this->assertSame('07:00:00', $attendance->first_in_at->format('H:i:s'));
        $this->assertSame('16:00:00', $attendance->last_out_at->format('H:i:s'));
    }

    public function test_require_user_identity_rejects_unmapped_pins(): void
    {
        config()->set('attendance.require_user_identity', true);
        config()->set('attendance.require_user_verified', false);

        $payload = "1\t2026-02-05 07:00:00\t0\t1\n2\t2026-02-05 07:05:00\t0\t1";

        $this->call('POST', '/iclock/cdata?SN=DEV123', [], [], [], [
            'CONTENT_TYPE' => 'text/plain',
        ], $payload)->assertOk();

        $this->assertDatabaseCount('attendance_logs', 0);

        $user = User::factory()->create(['is_verified_by_admin' => true]);
        AttendanceIdentity::create([
            'kind' => 'user',
            'user_id' => $user->id,
            'device_pin' => '1',
            'is_active' => true,
        ]);

        $this->call('POST', '/iclock/cdata?SN=DEV123', [], [], [], [
            'CONTENT_TYPE' => 'text/plain',
        ], $payload)->assertOk();

        $this->assertDatabaseCount('attendance_logs', 1);
        $this->assertDatabaseHas('attendance_logs', [
            'device_pin' => '1',
        ]);
    }

    public function test_iclock_getrequest_returns_pending_commands_and_marks_sent(): void
    {
        $this->call('GET', '/iclock/getrequest?SN=DEV123')->assertOk();

        $device = AttendanceDevice::query()->where('serial_number', 'DEV123')->firstOrFail();

        $command = AttendanceCommand::create([
            'attendance_device_id' => $device->id,
            'kind' => 'delete_user',
            'device_pin' => '10',
            'command' => 'DATA DELETE USERINFO PIN=10',
            'status' => 'pending',
        ]);

        $response = $this->call('GET', '/iclock/getrequest?SN=DEV123');
        $response->assertOk();
        $response->assertSee("C:{$command->id}:DATA DELETE USERINFO PIN=10", false);

        $command->refresh();
        $this->assertSame('sent', $command->status);
        $this->assertNotNull($command->sent_at);
    }

    public function test_iclock_devicecmd_records_result(): void
    {
        $this->call('GET', '/iclock/getrequest?SN=DEV123')->assertOk();
        $device = AttendanceDevice::query()->where('serial_number', 'DEV123')->firstOrFail();

        $command = AttendanceCommand::create([
            'attendance_device_id' => $device->id,
            'kind' => 'delete_user',
            'device_pin' => '10',
            'command' => 'DATA DELETE USERINFO PIN=10',
            'status' => 'sent',
            'sent_at' => now(),
        ]);

        $this->call('POST', "/iclock/devicecmd?SN=DEV123&ID={$command->id}&Return=0")->assertOk();

        $command->refresh();
        $this->assertSame('done', $command->status);
        $this->assertNotNull($command->executed_at);
        $this->assertSame('0', $command->result_code);
    }

    public function test_disabling_identity_enqueues_delete_user_command(): void
    {
        $device = AttendanceDevice::create([
            'serial_number' => 'DEV123',
            'name' => 'DEV123',
            'ip_address' => null,
            'port' => null,
            'comm_key' => null,
            'is_active' => true,
        ]);

        $user = User::factory()->create();
        $identity = AttendanceIdentity::create([
            'kind' => 'user',
            'user_id' => $user->id,
            'device_pin' => '99',
            'is_active' => true,
        ]);

        $identity->update(['is_active' => false]);

        $this->assertDatabaseHas('attendance_commands', [
            'attendance_device_id' => $device->id,
            'kind' => 'delete_user',
            'device_pin' => '99',
            'status' => 'pending',
        ]);
    }
}
