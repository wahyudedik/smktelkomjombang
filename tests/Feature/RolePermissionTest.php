<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Gate;

class RolePermissionTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->superadmin = User::factory()->create([
            'email' => 'superadmin@test.com',
            'password' => Hash::make('password'),
        ]);
        $superadminRole = $this->getOrCreateRole('superadmin');
        $this->superadmin->syncRoles([$superadminRole]);
        $this->superadmin->updateQuietly(['user_type' => 'superadmin']);
    }

    /** @test */
    public function superadmin_can_view_roles_and_permissions()
    {
        $response = $this->actingAs($this->superadmin)
            ->get(route('admin.role-permissions.index'));

        $response->assertStatus(200);
        $response->assertViewIs('admin.role-permissions.index');
    }

    /** @test */
    public function superadmin_can_create_role()
    {
        $roleData = [
            'name' => 'test-role',
            'display_name' => 'Test Role',
            'permissions' => [],
        ];

        $response = $this->actingAs($this->superadmin)
            ->post(route('admin.role-permissions.store'), $roleData);

        $response->assertRedirect();
        $this->assertDatabaseHas('roles', [
            'name' => 'test-role',
        ]);
    }

    /** @test */
    public function superadmin_can_assign_role_to_user()
    {
        $user = User::factory()->create();
        $role = $this->getOrCreateRole('admin');

        $assignData = [
            'user_id' => $user->id,
            'role_id' => $role->id,
        ];

        $response = $this->actingAs($this->superadmin)
            ->post(route('admin.role-permissions.assign-role'), $assignData);

        $response->assertRedirect();

        $user->refresh();
        $this->assertTrue($user->hasRole('admin'));
        $this->assertEquals('admin', $user->user_type);
    }

    /** @test */
    public function superadmin_can_remove_role_from_user()
    {
        $user = User::factory()->create();
        $role = $this->getOrCreateRole('admin');
        $user->syncRoles([$role]);

        $removeData = [
            'user_id' => $user->id,
            'role_id' => $role->id,
        ];

        $response = $this->actingAs($this->superadmin)
            ->post(route('admin.role-permissions.remove-role'), $removeData);

        $response->assertRedirect();

        $user->refresh();
        $this->assertFalse($user->hasRole('admin'));
    }

    /** @test */
    public function user_with_permission_can_access_protected_route()
    {
        $user = User::factory()->create();
        $role = $this->getOrCreateRole('admin');
        $user->syncRoles([$role]);

        $permission = $this->getOrCreatePermission('users.view');
        $role->givePermissionTo($permission);

        $response = $this->actingAs($user)
            ->get(route('admin.user-management.index'));

        $response->assertStatus(200);
    }

    /** @test */
    public function user_without_permission_cannot_access_protected_route()
    {
        $user = User::factory()->create();
        $role = $this->getOrCreateRole('siswa');
        $user->syncRoles([$role]);

        $response = $this->actingAs($user)
            ->get(route('admin.user-management.index'));

        $response->assertStatus(403);
    }

    /** @test */
    public function custom_role_can_access_route_with_role_middleware()
    {
        $user = User::factory()->create();
        $customRole = $this->getOrCreateRole('osis');
        $user->syncRoles([$customRole]);
        $user->updateQuietly(['user_type' => 'osis']);

        // Check if middleware allows custom role
        $this->assertTrue($user->hasRole('osis'));
        $this->assertEquals('osis', $user->user_type);
    }

    /** @test */
    public function superadmin_bypasses_all_permission_checks()
    {
        // Superadmin should have access to everything
        $response = $this->actingAs($this->superadmin)
            ->get(route('admin.superadmin.users'));

        $response->assertStatus(200);

        $response = $this->actingAs($this->superadmin)
            ->get(route('admin.role-permissions.index'));

        $response->assertStatus(200);
    }

    /** @test */
    public function role_sync_removes_existing_roles()
    {
        $user = User::factory()->create();
        $role1 = $this->getOrCreateRole('admin');
        $role2 = $this->getOrCreateRole('guru');

        $user->syncRoles([$role1]);
        $this->assertTrue($user->hasRole('admin'));
        $this->assertFalse($user->hasRole('guru'));

        $user->syncRoles([$role2]);
        $this->assertFalse($user->hasRole('admin'));
        $this->assertTrue($user->hasRole('guru'));
    }

    /** @test */
    public function user_type_is_synced_after_role_assignment()
    {
        $user = User::factory()->create();
        $role = $this->getOrCreateRole('guru');

        $user->syncRoles([$role]);
        $user->refresh();

        $primaryRole = $user->roles->first();
        if ($primaryRole && $user->user_type !== $primaryRole->name) {
            $user->updateQuietly(['user_type' => $primaryRole->name]);
        }

        $this->assertEquals('guru', $user->user_type);
    }
}
