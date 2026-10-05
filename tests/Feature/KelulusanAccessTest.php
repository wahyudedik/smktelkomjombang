<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Access control tests for the E-Graduation (Kelulusan) module.
 *
 * Bug context: a duplicate `role:siswa` route group used to register
 * `admin.lulus.index` (GET /admin/lulus) WITHOUT the `permission:kelulusan.*`
 * middleware, overriding the protected admin route (last registered wins).
 * After the fix, /admin/lulus* is only reachable by admin/superadmin/guru
 * holding the corresponding kelulusan permissions. Siswa check their own
 * graduation status via the PUBLIC route /check-graduation.
 */
class KelulusanAccessTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;

    protected User $siswa;

    protected function setUp(): void
    {
        parent::setUp();

        // CheckRole's fallback (rolePermissionMap) calls hasPermissionTo() for
        // every permission mapped to the roles required by the route group
        // (admin, guru). Spatie throws PermissionDoesNotExist when a permission
        // is missing from the DB, which would surface as a 500 instead of a
        // clean 403. Create all mapped permissions so the middleware can
        // evaluate them (creating ≠ assigning; siswa still gets no permissions).
        $mappedPermissions = [
            // admin map
            'users.view', 'users.create', 'users.edit', 'users.delete',
            'pages.view', 'pages.create', 'pages.edit', 'pages.delete',
            'events.view', 'events.create', 'events.edit', 'events.delete',
            'berita.view', 'berita.create', 'berita.edit', 'berita.delete',
            'osis.view', 'osis.read', 'osis.create', 'osis.edit', 'osis.delete',
            'kelulusan.view', 'kelulusan.create', 'kelulusan.edit', 'kelulusan.delete',
            'surat.view',
            'settings.view', 'settings.manage',
            'testimonials.view', 'testimonial-links.view',
            // guru map
            'guru.view', 'guru.read', 'guru.create', 'guru.edit', 'guru.delete',
            'siswa.view', 'siswa.read',
            'jadwal.view', 'jadwal.read',
            'attendance.view',
            'lulus.view', 'lulus.read',
        ];

        foreach ($mappedPermissions as $permission) {
            $this->getOrCreatePermission($permission);
        }

        // Admin: role admin + kelulusan.view (required by permission:kelulusan.view on index)
        $this->admin = User::factory()->create();
        $this->admin->syncRoles([$this->getOrCreateRole('admin')]);
        $this->admin->givePermissionTo('kelulusan.view');
        $this->admin->updateQuietly(['user_type' => 'admin']);

        // Siswa: role siswa WITHOUT any kelulusan permission
        $this->siswa = User::factory()->create();
        $this->siswa->syncRoles([$this->getOrCreateRole('siswa')]);
        $this->siswa->updateQuietly(['user_type' => 'siswa']);
    }

    public function test_siswa_cannot_access_kelulusan_index(): void
    {
        $this->actingAs($this->siswa)
            ->get(route('admin.lulus.index'))
            ->assertForbidden();
    }

    public function test_siswa_cannot_access_kelulusan_create(): void
    {
        $this->actingAs($this->siswa)
            ->get(route('admin.lulus.create'))
            ->assertForbidden();
    }

    public function test_siswa_cannot_access_kelulusan_check(): void
    {
        $this->actingAs($this->siswa)
            ->get(route('admin.lulus.check'))
            ->assertForbidden();
    }

    public function test_siswa_cannot_store_kelulusan(): void
    {
        $this->actingAs($this->siswa)
            ->post(route('admin.lulus.store'), [
                'nama' => 'Hacked',
                'nisn' => '0000000000',
            ])
            ->assertForbidden();
    }

    public function test_admin_can_access_kelulusan_index(): void
    {
        $this->actingAs($this->admin)
            ->get(route('admin.lulus.index'))
            ->assertOk();
    }

    public function test_guest_can_access_public_graduation_check(): void
    {
        $this->get(route('public.graduation.check'))
            ->assertOk();
    }
}
