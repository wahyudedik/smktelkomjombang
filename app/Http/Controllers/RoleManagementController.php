<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;
use App\Helpers\RoleHelper;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Auth;

class RoleManagementController extends Controller
{
    public function index()
    {
        // Authorization check - double layer: route middleware + Gate
        Gate::authorize('manageRolesAndPermissions');

        $roles = Role::with('permissions')->withCount('users')->get();
        return view('role-management.index', compact('roles'));
    }

    public function create()
    {
        // Authorization check
        Gate::authorize('manageRolesAndPermissions');

        // Group permissions by module (extract from permission name: module.action)
        $permissions = Permission::all()->groupBy(function ($permission) {
            return explode('.', $permission->name)[0] ?? 'other';
        });
        return view('role-management.create', compact('permissions'));
    }

    public function store(Request $request)
    {
        // Authorization check
        Gate::authorize('manageRolesAndPermissions');

        try {
            // Normalize role name: lowercase, no spaces, only alphanumeric and hyphens
            $roleName = strtolower(str_replace(' ', '', $request->name));
            $roleName = preg_replace('/[^a-z0-9-]/', '', $roleName);

            $request->merge(['name' => $roleName]);

            $request->validate([
                'name' => 'required|string|unique:roles,name|max:255|regex:/^[a-z0-9-]+$/',
                'display_name' => 'required|string|max:255',
                'description' => 'nullable|string|max:1000',
                'permissions' => 'array',
            ]);

            $role = Role::create([
                'name' => $roleName,
                'display_name' => $request->display_name,
                'description' => $request->description,
                'guard_name' => 'web',
            ]);

            if ($request->permissions) {
                $role->syncPermissions($request->permissions);
            }

            // Return JSON for AJAX requests
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Role created successfully',
                    'data' => $role
                ]);
            }

            return redirect()->route('admin.roles.index')
                ->with('success', 'Role created successfully');
        } catch (\Exception $e) {
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error creating role: ' . $e->getMessage()
                ], 422);
            }

            return redirect()->back()->with('error', 'Error creating role: ' . $e->getMessage());
        }
    }

    public function edit(Role $role)
    {
        // Authorization check
        Gate::authorize('manageRolesAndPermissions');

        // Group permissions by module (extract from permission name: module.action)
        $permissions = Permission::all()->groupBy(function ($permission) {
            return explode('.', $permission->name)[0] ?? 'other';
        });
        $rolePermissions = $role->permissions->pluck('name')->toArray();

        return view('role-management.edit', compact('role', 'permissions', 'rolePermissions'));
    }

    public function update(Request $request, Role $role)
    {
        // Authorization check
        Gate::authorize('manageRolesAndPermissions');

        try {
            // Check if it's a core role
            $isCoreRole = RoleHelper::isCoreRole($role->name);

            // Normalize role name: lowercase, no spaces, only alphanumeric and hyphens
            $roleName = strtolower(str_replace(' ', '', $request->name));
            $roleName = preg_replace('/[^a-z0-9-]/', '', $roleName);

            // Prevent changing core roles name (but allow updating permissions and other fields)
            if ($isCoreRole && $roleName !== $role->name) {
                if ($request->expectsJson() || $request->ajax()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Cannot change name of core system role. You can only update display name, description, and permissions.'
                    ], 403);
                }
                return redirect()->back()->with('error', 'Cannot change name of core system role');
            }

            $request->merge(['name' => $roleName]);

            $request->validate([
                'name' => 'required|string|unique:roles,name,' . $role->id . '|max:255|regex:/^[a-z0-9-]+$/',
                'display_name' => 'nullable|string|max:255',
                'description' => 'nullable|string|max:1000',
                'permissions' => 'array',
            ]);

            $role->update([
                'name' => $roleName,
                'display_name' => $request->display_name ?? $role->display_name,
                'description' => $request->description ?? $role->description,
            ]);

            $role->syncPermissions($request->permissions ?? []);

            // Return JSON for AJAX requests
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Role updated successfully',
                    'data' => $role
                ]);
            }

            return redirect()->route('admin.roles.index')
                ->with('success', 'Role updated successfully');
        } catch (\Exception $e) {
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error updating role: ' . $e->getMessage()
                ], 422);
            }

            return redirect()->back()->with('error', 'Error updating role: ' . $e->getMessage());
        }
    }

    public function destroy(Role $role)
    {
        // Authorization check
        Gate::authorize('manageRolesAndPermissions');

        // Prevent deletion of core roles
        if (RoleHelper::isCoreRole($role->name)) {
            return back()->with('error', 'Cannot delete core system role');
        }

        $role->delete();

        return redirect()->route('admin.roles.index')
            ->with('success', 'Role deleted successfully');
    }

    public function assignUsers(Role $role)
    {
        // Authorization check
        Gate::authorize('manageRolesAndPermissions');

        // Exclude superadmin users when assigning to non-superadmin roles
        // Superadmin already has all permissions, doesn't need role assignment
        $usersQuery = User::with('roles');

        // If assigning to non-superadmin role, exclude superadmin users
        // Superadmin users have all permissions by default, don't need additional roles
        if (strtolower($role->name) !== 'superadmin') {
            $usersQuery->whereDoesntHave('roles', function ($query) {
                $query->where('name', 'superadmin');
            });
        }

        $users = $usersQuery->get();
        $roleUsers = $role->users->pluck('id')->toArray();

        return view('role-management.assign-users', compact('role', 'users', 'roleUsers'));
    }

    public function syncUsers(Request $request, Role $role)
    {
        // Authorization check
        Gate::authorize('manageRolesAndPermissions');

        try {
            $request->validate([
                'user_ids' => 'array',
            ]);

            // IMPORTANT: Ensure one user has only ONE role (not multiple roles)
            // Strategy: Remove user from ALL other roles before assigning to this role

            $selectedUserIds = $request->user_ids ?? [];

            // Get previous users before sync
            $previousUserIds = $role->users->pluck('id')->toArray();

            // Get all affected user IDs (both added and removed)
            $affectedUserIds = array_unique(array_merge($selectedUserIds, $previousUserIds));

            // BEFORE syncing: Remove all users from this role first (clean slate)
            // This prevents users from having multiple roles
            foreach ($affectedUserIds as $userId) {
                $user = User::find($userId);
                if ($user) {
                    // Remove this user from ALL roles first
                    $user->roles()->detach();
                }
            }

            // NOW sync: Assign selected users to THIS role only (one role per user)
            $role->users()->sync($selectedUserIds);

            // Refresh role to get updated users
            $role->refresh();

            // Roles are managed by Spatie Permission - no need to sync user_type

            // Return JSON for AJAX requests
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Users assigned successfully',
                    'data' => [
                        'role' => $role,
                        'user_count' => $role->users()->count()
                    ]
                ]);
            }

            return redirect()->route('admin.roles.index')
                ->with('success', 'Users assigned successfully');
        } catch (\Exception $e) {
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error assigning users: ' . $e->getMessage()
                ], 422);
            }

            return redirect()->back()->with('error', 'Error assigning users: ' . $e->getMessage());
        }
    }
}
