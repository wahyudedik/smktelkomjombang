<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Calon;
use App\Models\Pemilih;
use App\Models\Voting;
use App\Models\Siswa;
use App\Models\Role;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Illuminate\Support\Facades\Hash;

class OSISVotingFlowTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Create superadmin
        $this->superadmin = User::factory()->create([
            'email' => 'admin@test.com',
            'password' => Hash::make('password'),
        ]);
        $adminRole = $this->getOrCreateRole('admin');
        $this->superadmin->syncRoles([$adminRole]);
        $this->superadmin->updateQuietly(['user_type' => 'admin']);

        // Create siswa user
        $this->siswa = User::factory()->create([
            'email' => 'siswa@test.com',
            'password' => Hash::make('password'),
        ]);
        $siswaRole = $this->getOrCreateRole('siswa');
        $this->siswa->syncRoles([$siswaRole]);
        $this->siswa->updateQuietly(['user_type' => 'siswa']);

        // Create siswa data
        $this->siswaData = Siswa::factory()->create([
            'nama_lengkap' => 'Test Siswa',
            'nis' => '12345',
            'kelas' => 'X IPA 1',
            'status' => 'aktif',
        ]);
    }

    /** @test */
    public function admin_can_create_calon()
    {
        $calonData = [
            'nama_ketua' => 'Ketua Test',
            'kelas_ketua' => 'X IPA 1',
            'nama_wakil' => 'Wakil Test',
            'kelas_wakil' => 'X IPA 2',
            'visi' => 'Visi test',
            'misi' => 'Misi test',
            'jenis_pencalonan' => 'pasangan',
            'is_active' => true,
        ];

        $response = $this->actingAs($this->superadmin)
            ->post(route('admin.osis.calon.store'), $calonData);

        $response->assertRedirect();
        $this->assertDatabaseHas('calons', [
            'nama_ketua' => 'Ketua Test',
            'nama_wakil' => 'Wakil Test',
        ]);
    }

    /** @test */
    public function admin_can_create_pemilih()
    {
        $pemilihData = [
            'nama' => 'Test Siswa',
            'nis' => '12345',
            'kelas' => 'X IPA 1',
            'email' => 'siswa@test.com',
            'is_active' => true,
        ];

        $response = $this->actingAs($this->superadmin)
            ->post(route('admin.osis.pemilih.store'), $pemilihData);

        $response->assertRedirect();
        $this->assertDatabaseHas('pemilihs', [
            'nama' => 'Test Siswa',
            'nis' => '12345',
        ]);
    }

    /** @test */
    public function siswa_can_access_voting_page()
    {
        $response = $this->actingAs($this->siswa)
            ->get(route('admin.osis.voting'));

        $response->assertStatus(200);
    }

    /** @test */
    public function siswa_can_vote()
    {
        // Create calon
        $calon = Calon::factory()->create([
            'nama_ketua' => 'Ketua Test',
            'nama_wakil' => 'Wakil Test',
            'is_active' => true,
        ]);

        // Create pemilih
        $pemilih = Pemilih::factory()->create([
            'nama' => 'Test Siswa',
            'nis' => '12345',
            'email' => $this->siswa->email,
            'is_active' => true,
        ]);

        $voteData = [
            'calon_id' => $calon->id,
            'pemilih_id' => $pemilih->id,
        ];

        $response = $this->actingAs($this->siswa)
            ->post(route('admin.osis.vote'), $voteData);

        $response->assertRedirect();
        $this->assertDatabaseHas('votings', [
            'calon_id' => $calon->id,
            'pemilih_id' => $pemilih->id,
        ]);
    }

    /** @test */
    public function siswa_cannot_vote_twice()
    {
        $calon = Calon::factory()->create(['is_active' => true]);
        $pemilih = Pemilih::factory()->create([
            'email' => $this->siswa->email,
            'is_active' => true,
        ]);

        // First vote
        $this->actingAs($this->siswa)
            ->post(route('admin.osis.vote'), [
                'calon_id' => $calon->id,
                'pemilih_id' => $pemilih->id,
            ]);

        // Second vote attempt
        $response = $this->actingAs($this->siswa)
            ->post(route('admin.osis.vote'), [
                'calon_id' => $calon->id,
                'pemilih_id' => $pemilih->id,
            ]);

        $response->assertSessionHasErrors();

        $voteCount = Voting::where('pemilih_id', $pemilih->id)->count();
        $this->assertEquals(1, $voteCount);
    }

    /** @test */
    public function admin_can_view_voting_results()
    {
        $calon = Calon::factory()->create(['is_active' => true]);
        $pemilih = Pemilih::factory()->create(['is_active' => true]);

        Voting::factory()->create([
            'calon_id' => $calon->id,
            'pemilih_id' => $pemilih->id,
        ]);

        $response = $this->actingAs($this->superadmin)
            ->get(route('admin.osis.results'));

        $response->assertStatus(200);
        $response->assertViewIs('osis.results');
    }

    /** @test */
    public function only_active_calon_can_receive_votes()
    {
        $inactiveCalon = Calon::factory()->create([
            'is_active' => false,
        ]);

        $pemilih = Pemilih::factory()->create([
            'email' => $this->siswa->email,
            'is_active' => true,
        ]);

        $response = $this->actingAs($this->siswa)
            ->post(route('admin.osis.vote'), [
                'calon_id' => $inactiveCalon->id,
                'pemilih_id' => $pemilih->id,
            ]);

        $response->assertSessionHasErrors();
        $this->assertDatabaseMissing('votings', [
            'calon_id' => $inactiveCalon->id,
        ]);
    }

    /** @test */
    public function only_active_pemilih_can_vote()
    {
        $calon = Calon::factory()->create(['is_active' => true]);
        $inactivePemilih = Pemilih::factory()->create([
            'email' => $this->siswa->email,
            'is_active' => false,
        ]);

        $response = $this->actingAs($this->siswa)
            ->post(route('admin.osis.vote'), [
                'calon_id' => $calon->id,
                'pemilih_id' => $inactivePemilih->id,
            ]);

        $response->assertSessionHasErrors();
    }
}
