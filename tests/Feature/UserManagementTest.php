<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Illuminate\Support\Facades\Hash;

class UserManagementTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Create superadmin user
        $this->superadmin = User::factory()->create([
            'email' => 'superadmin@test.com',
            'password' => Hash::make('password'),
        ]);

        $superadminRole = $this->getOrCreateRole('superadmin');
        $this->superadmin->syncRoles([$superadminRole]);
        $this->superadmin->updateQuietly(['user_type' => 'superadmin']);
    }

    /** @test */
    public function superadmin_can_view_users_list()
    {
        $response = $this->actingAs($this->superadmin)
            ->get(route('admin.superadmin.users'));

        $response->assertStatus(200);
        $response->assertViewIs('superadmin.users.index');
    }

    /** @test */
    public function superadmin_can_create_user()
    {
        $role = $this->getOrCreateRole('admin');

        $userData = [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'roles' => [$role->id],
        ];

        $response = $this->actingAs($this->superadmin)
            ->post(route('admin.superadmin.users.store'), $userData);

        $response->assertRedirect();
        $this->assertDatabaseHas('users', [
            'email' => 'test@example.com',
            'name' => 'Test User',
        ]);

        $user = User::where('email', 'test@example.com')->first();
        $this->assertTrue($user->hasRole('admin'));
        $this->assertEquals('admin', $user->user_type);
    }

    /** @test */
    public function superadmin_can_update_user()
    {
        $user = User::factory()->create([
            'email' => 'update@example.com',
            'name' => 'Old Name',
        ]);

        $role = $this->getOrCreateRole('guru');
        $user->syncRoles([$role]);

        $updateData = [
            'name' => 'New Name',
            'email' => 'update@example.com',
            'roles' => [$role->id],
        ];

        $response = $this->actingAs($this->superadmin)
            ->put(route('admin.superadmin.users.update', $user), $updateData);

        $response->assertRedirect();
        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'name' => 'New Name',
        ]);

        $user->refresh();
        $this->assertTrue($user->hasRole('guru'));
    }

    /** @test */
    public function updating_user_roles_does_not_remove_existing_roles()
    {
        $user = User::factory()->create();
        $role = $this->getOrCreateRole('admin');
        $user->syncRoles([$role]);

        // Update user without sending roles array (should preserve existing roles)
        $updateData = [
            'name' => 'Updated Name',
            'email' => $user->email,
            // Don't include roles key to test that existing roles are preserved
        ];

        $response = $this->actingAs($this->superadmin)
            ->put(route('admin.superadmin.users.update', $user), $updateData);

        $response->assertRedirect();
        $user->refresh();
        $this->assertTrue($user->hasRole('admin'), 'Role should be preserved when roles array is not in request');
    }

    /** @test */
    public function superadmin_can_delete_user()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($this->superadmin)
            ->delete(route('admin.superadmin.users.destroy', $user));

        $response->assertRedirect();
        $this->assertDatabaseMissing('users', ['id' => $user->id]);
    }

    /** @test */
    public function user_type_is_synced_with_role_after_creation()
    {
        $role = $this->getOrCreateRole('osis');

        $userData = [
            'name' => 'OSIS User',
            'email' => 'osis@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'roles' => [$role->id],
        ];

        $response = $this->actingAs($this->superadmin)
            ->post(route('admin.superadmin.users.store'), $userData);

        $response->assertRedirect();

        $user = User::where('email', 'osis@example.com')->first();
        $this->assertNotNull($user, 'User should be created');
        $this->assertEquals('osis', $user->user_type);
        $this->assertTrue($user->hasRole('osis'));
    }

    /** @test */
    public function user_type_is_synced_when_role_is_updated()
    {
        $user = User::factory()->create();
        $oldRole = $this->getOrCreateRole('admin');
        $newRole = $this->getOrCreateRole('guru');

        $user->syncRoles([$oldRole]);
        $user->updateQuietly(['user_type' => 'admin']);

        $this->assertEquals('admin', $user->user_type);

        $updateData = [
            'name' => $user->name,
            'email' => $user->email,
            'roles' => [$newRole->id],
        ];

        $this->actingAs($this->superadmin)
            ->put(route('admin.superadmin.users.update', $user), $updateData);

        $user->refresh();
        $this->assertEquals('guru', $user->user_type);
        $this->assertTrue($user->hasRole('guru'));
    }

    /** @test */
    public function user_cannot_access_user_management_without_permission()
    {
        $user = User::factory()->create();
        $role = $this->getOrCreateRole('siswa');
        $user->syncRoles([$role]);

        $response = $this->actingAs($user)
            ->get(route('admin.superadmin.users'));

        $response->assertStatus(403);
    }

    /** @test */
    public function user_with_permission_can_access_user_management()
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
}
