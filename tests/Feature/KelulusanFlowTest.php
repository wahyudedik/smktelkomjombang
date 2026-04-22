<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Kelulusan;
use App\Models\Siswa;
use App\Models\Role;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Illuminate\Support\Facades\Hash;

class KelulusanFlowTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Create admin user
        $this->admin = User::factory()->create([
            'email' => 'admin@test.com',
            'password' => Hash::make('password'),
        ]);
        $adminRole = $this->getOrCreateRole('admin');
        $this->admin->syncRoles([$adminRole]);
        $this->admin->updateQuietly(['user_type' => 'admin']);

        // Create siswa data
        $this->siswa = Siswa::factory()->create([
            'nama_lengkap' => 'Test Siswa',
            'nis' => '12345',
            'nisn' => '9876543210',
            'kelas' => 'XII IPA 1',
            'jurusan' => 'IPA',
            'status' => 'aktif',
        ]);
    }

    /** @test */
    public function admin_can_create_kelulusan()
    {
        $kelulusanData = [
            'siswa_id' => $this->siswa->id,
            'nama' => $this->siswa->nama_lengkap,
            'nisn' => $this->siswa->nisn,
            'nis' => $this->siswa->nis,
            'jurusan' => $this->siswa->jurusan,
            'tahun_ajaran' => 2024,
            'status' => 'lulus',
        ];

        $response = $this->actingAs($this->admin)
            ->post(route('admin.lulus.store'), $kelulusanData);

        $response->assertRedirect();
        $this->assertDatabaseHas('kelulusans', [
            'nisn' => $this->siswa->nisn,
            'status' => 'lulus',
        ]);
    }

    /** @test */
    public function kelulusan_can_be_created_with_siswa_id_dropdown()
    {
        $kelulusanData = [
            'siswa_id' => $this->siswa->id,
            'nama' => $this->siswa->nama_lengkap,
            'nisn' => $this->siswa->nisn,
            'nis' => $this->siswa->nis,
            'jurusan' => $this->siswa->jurusan,
            'tahun_ajaran' => 2024,
            'status' => 'lulus',
        ];

        $response = $this->actingAs($this->admin)
            ->post(route('admin.lulus.store'), $kelulusanData);

        $response->assertRedirect();

        $kelulusan = Kelulusan::where('nisn', $this->siswa->nisn)->first();
        $this->assertNotNull($kelulusan);
        $this->assertEquals($this->siswa->nama_lengkap, $kelulusan->nama);
    }

    /** @test */
    public function admin_can_check_kelulusan_status()
    {
        Kelulusan::factory()->create([
            'nisn' => $this->siswa->nisn,
            'nama' => $this->siswa->nama_lengkap,
            'status' => 'lulus',
        ]);

        $response = $this->actingAs($this->admin)
            ->get(route('admin.lulus.check'));

        $response->assertStatus(200);
        $response->assertViewIs('lulus.check');
    }

    /** @test */
    public function admin_can_process_kelulusan_check()
    {
        $kelulusan = Kelulusan::factory()->create([
            'nisn' => $this->siswa->nisn,
            'nama' => $this->siswa->nama_lengkap,
            'status' => 'lulus',
        ]);

        $checkData = [
            'nisn' => $this->siswa->nisn,
        ];

        $response = $this->actingAs($this->admin)
            ->post(route('admin.lulus.check.process'), $checkData);

        $response->assertStatus(200);
        $response->assertViewHas('kelulusan', function ($foundKelulusan) use ($kelulusan) {
            return $foundKelulusan->id === $kelulusan->id;
        });
    }

    /** @test */
    public function public_can_check_kelulusan_status()
    {
        Kelulusan::factory()->create([
            'nisn' => $this->siswa->nisn,
            'nama' => $this->siswa->nama_lengkap,
            'status' => 'lulus',
        ]);

        $response = $this->get(route('public.graduation.check'));

        $response->assertStatus(200);
    }

    /** @test */
    public function public_can_process_kelulusan_check()
    {
        $kelulusan = Kelulusan::factory()->create([
            'nisn' => $this->siswa->nisn,
            'nama' => $this->siswa->nama_lengkap,
            'status' => 'lulus',
        ]);

        $checkData = [
            'nisn' => $this->siswa->nisn,
        ];

        $response = $this->post(route('public.graduation.check.process'), $checkData);

        $response->assertStatus(200);
        $response->assertViewHas('kelulusan', function ($foundKelulusan) use ($kelulusan) {
            return $foundKelulusan->id === $kelulusan->id;
        });
    }

    /** @test */
    public function kelulusan_check_returns_error_for_invalid_nisn()
    {
        $checkData = [
            'nisn' => '0000000000',
        ];

        $response = $this->post(route('admin.lulus.check.process'), $checkData);

        $response->assertSessionHasErrors('nisn');
    }

    /** @test */
    public function admin_can_update_kelulusan()
    {
        $kelulusan = Kelulusan::factory()->create([
            'nisn' => $this->siswa->nisn,
            'status' => 'lulus',
        ]);

        $updateData = [
            'nama' => $kelulusan->nama,
            'nisn' => $kelulusan->nisn,
            'nis' => $kelulusan->nis,
            'jurusan' => $kelulusan->jurusan,
            'tahun_ajaran' => $kelulusan->tahun_ajaran,
            'status' => 'tidak_lulus',
        ];

        $response = $this->actingAs($this->admin)
            ->put(route('admin.lulus.update', $kelulusan), $updateData);

        $response->assertRedirect();
        $this->assertDatabaseHas('kelulusans', [
            'id' => $kelulusan->id,
            'status' => 'tidak_lulus',
        ]);
    }

    /** @test */
    public function admin_can_delete_kelulusan()
    {
        $kelulusan = Kelulusan::factory()->create();

        $response = $this->actingAs($this->admin)
            ->delete(route('admin.lulus.destroy', $kelulusan));

        $response->assertRedirect();
        $this->assertDatabaseMissing('kelulusans', ['id' => $kelulusan->id]);
    }
}
