<?php

namespace Tests\Feature;

use App\Models\AttendanceDevice;
use App\Models\AttendanceIdentity;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Regresi untuk pensiunnya jalur socket legacy port 4370 di modul ZKTeco biometric.
 *
 * - Enroll fingerprint/face/RFID harus tetap enqueue ke attendance_commands
 *   TANPA membutuhkan koneksi socket ke device. Command dieksekusi device
 *   via polling HTTP iClock (/iclock/getrequest).
 * - testConnection() kini health-check berbasis last_seen_at (aktivitas iClock),
 *   bukan fsockopen port 4370.
 *
 * TIDAK ada koneksi jaringan nyata yang dibuat selama test ini: device sengaja
 * memakai IP dokumentasi RFC 5737 (TEST-NET-3) yang tidak routable untuk
 * membuktikan enqueue tidak bergantung pada konektivitas socket.
 */
class BiometricEnrollmentWithoutSocketTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::factory()->create([
            'email_verified_at' => now(),
        ]);
        $this->admin->assignRole($this->getOrCreateRole('admin'));
    }

    /**
     * Device dengan IP tidak routable (203.0.113.0/24 = TEST-NET-3, RFC 5737).
     */
    private function makeUnreachableDevice(array $overrides = []): AttendanceDevice
    {
        return AttendanceDevice::create(array_merge([
            'serial_number' => 'ZK-LEGACY-001',
            'name' => 'Device Uji Tanpa Socket',
            'ip_address' => '203.0.113.99',
            'port' => 4370,
            'is_active' => true,
        ], $overrides));
    }

    private function makeIdentity(): AttendanceIdentity
    {
        return AttendanceIdentity::create([
            'kind' => 'user',
            'user_id' => $this->admin->id,
            'device_pin' => '1001',
            'is_active' => true,
        ]);
    }

    public function test_enroll_fingerprint_tanpa_socket_tetap_enqueue_command(): void
    {
        $device = $this->makeUnreachableDevice();
        $identity = $this->makeIdentity();

        $response = $this->actingAs($this->admin)->post(
            route('admin.absensi.biometric.fingerprint.store', $identity),
            ['device_id' => $device->id, 'finger_index' => 2]
        );

        $response->assertRedirect(route('admin.absensi.biometric.index'));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('attendance_commands', [
            'attendance_device_id' => $device->id,
            'kind' => 'enroll_fingerprint',
            'device_pin' => '1001',
            'status' => 'pending',
        ]);

        $command = \App\Models\AttendanceCommand::query()
            ->where('kind', 'enroll_fingerprint')
            ->firstOrFail();
        $this->assertStringContainsString('FingerIdx=2', $command->command);
        $this->assertStringContainsString('EnrollFP=1', $command->command);
    }

    public function test_enroll_face_tanpa_socket_tetap_enqueue_command(): void
    {
        $device = $this->makeUnreachableDevice();
        $identity = $this->makeIdentity();

        $response = $this->actingAs($this->admin)->post(
            route('admin.absensi.biometric.face.store', $identity),
            ['device_id' => $device->id]
        );

        $response->assertRedirect(route('admin.absensi.biometric.index'));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('attendance_commands', [
            'attendance_device_id' => $device->id,
            'kind' => 'enroll_face',
            'device_pin' => '1001',
            'status' => 'pending',
        ]);

        $command = \App\Models\AttendanceCommand::query()
            ->where('kind', 'enroll_face')
            ->firstOrFail();
        $this->assertStringContainsString('EnrollFace=1', $command->command);
    }

    public function test_enroll_rfid_tanpa_socket_tetap_enqueue_command(): void
    {
        $device = $this->makeUnreachableDevice();
        $identity = $this->makeIdentity();

        $response = $this->actingAs($this->admin)->post(
            route('admin.absensi.biometric.rfid.store', $identity),
            ['device_id' => $device->id, 'card_number' => '1234567890']
        );

        $response->assertRedirect(route('admin.absensi.biometric.index'));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('attendance_commands', [
            'attendance_device_id' => $device->id,
            'kind' => 'enroll_rfid',
            'device_pin' => '1001',
            'status' => 'pending',
        ]);

        $command = \App\Models\AttendanceCommand::query()
            ->where('kind', 'enroll_rfid')
            ->firstOrFail();
        $this->assertStringContainsString('Card=1234567890', $command->command);
    }

    public function test_test_connection_online_ketika_last_seen_baru(): void
    {
        $device = $this->makeUnreachableDevice([
            'last_seen_at' => now()->subMinute(),
        ]);

        $response = $this->actingAs($this->admin)->postJson(
            route('admin.absensi.biometric.test-connection'),
            ['device_id' => $device->id]
        );

        $response->assertOk();
        $response->assertJson([
            'success' => true,
            'status' => 'online',
            'method' => 'iclock',
        ]);
        $this->assertNotNull($response->json('last_seen_at'));
    }

    public function test_test_connection_offline_ketika_last_seen_lama(): void
    {
        $device = $this->makeUnreachableDevice([
            'last_seen_at' => now()->subMinutes(30),
        ]);

        $response = $this->actingAs($this->admin)->postJson(
            route('admin.absensi.biometric.test-connection'),
            ['device_id' => $device->id]
        );

        $response->assertOk();
        $response->assertJson([
            'success' => false,
            'status' => 'offline',
            'method' => 'iclock',
        ]);
    }

    public function test_test_connection_offline_ketika_belum_pernah_terhubung(): void
    {
        $device = $this->makeUnreachableDevice([
            'last_seen_at' => null,
        ]);

        $response = $this->actingAs($this->admin)->postJson(
            route('admin.absensi.biometric.test-connection'),
            ['device_id' => $device->id]
        );

        $response->assertOk();
        $response->assertJson([
            'success' => false,
            'status' => 'offline',
            'method' => 'iclock',
        ]);
        $this->assertNull($response->json('last_seen_at'));
    }

    public function test_halaman_index_menampilkan_status_iclock_based(): void
    {
        $this->makeUnreachableDevice(['last_seen_at' => now()]);
        $this->makeIdentity();

        $response = $this->actingAs($this->admin)
            ->get(route('admin.absensi.biometric.index'));

        $response->assertOk();
        $response->assertSee('Terakhir terlihat (iClock)');
        $response->assertSee('Online');
    }

    public function test_tidak_ada_sisa_pemanggil_socket_legacy(): void
    {
        $sources = [
            file_get_contents(app_path('Services/ZKTeco/BiometricEnrollmentService.php')),
            file_get_contents(app_path('Http/Controllers/BiometricEnrollmentController.php')),
        ];

        foreach ($sources as $source) {
            $this->assertStringNotContainsString('fsockopen', $source);
            $this->assertStringNotContainsString('->connect(', $source);
            $this->assertStringNotContainsString('->disconnect(', $source);
            $this->assertStringNotContainsString('getUsers(', $source);
            $this->assertStringNotContainsString('buildCommand', $source);
            $this->assertStringNotContainsString('parseUserList', $source);
        }
    }
}
