<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;
use App\Helpers\RoleHelper;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Auth;

class RolePermissionController extends Controller
{
    public function index()
    {
        // Authorization check - double layer: route middleware + Gate
        Gate::authorize('manageRolesAndPermissions');

        $roles = Role::with('permissions')->withCount('users')->get();
        $permissions = Permission::all()->groupBy(function ($permission) {
            return explode('.', $permission->name)[0];
        });

        // Predefined roles for dropdown
        $predefinedRoles = [
            'admin' => 'Admin',
            'guru' => 'Guru',
            'siswa' => 'Siswa',
            'sarpras' => 'Sarpras',
            'osis' => 'OSIS',
            'bendahara' => 'Bendahara',
            'tatausaha' => 'Tata Usaha',
            'kepalasekolah' => 'Kepala Sekolah',
            'wakil' => 'Wakil Kepala Sekolah',
            'kepalabidang' => 'Kepala Bidang',
        ];

        // Get existing role names
        $existingRoleNames = $roles->pluck('name')->toArray();

        return view('admin.role-permissions.index', compact('roles', 'permissions', 'predefinedRoles', 'existingRoleNames'));
    }


    public function createRole(Request $request)
    {
        // Authorization check
        Gate::authorize('manageRolesAndPermissions');

        try {
            // Normalize role name: lowercase, no spaces, only alphanumeric and hyphens
            $roleName = strtolower(str_replace(' ', '', $request->name));
            $roleName = preg_replace('/[^a-z0-9-]/', '', $roleName);

            $request->merge(['name' => $roleName]);

            $request->validate([
                'name' => 'required|string|max:255|unique:roles,name|regex:/^[a-z0-9-]+$/',
                'permissions' => 'array'
            ]);

            $role = Role::create([
                'name' => $roleName,
                'guard_name' => 'web'
            ]);

            if ($request->has('permissions')) {
                $role->givePermissionTo($request->permissions);
            }

            // Return JSON for AJAX requests
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Role created successfully',
                    'data' => $role
                ]);
            }

            return redirect()->back()->with('success', 'Role created successfully.');
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

    public function updateRole(Request $request, Role $role)
    {
        // Authorization check
        Gate::authorize('manageRolesAndPermissions');

        try {
            // Check if it's a core role
            $isCoreRole = RoleHelper::isCoreRole($role->name);

            // Normalize role name: lowercase, no spaces, only alphanumeric and hyphens
            $roleName = strtolower(str_replace(' ', '', $request->name));
            $roleName = preg_replace('/[^a-z0-9-]/', '', $roleName);

            // Prevent changing core roles name (but allow updating permissions)
            if ($isCoreRole && $roleName !== $role->name) {
                if ($request->expectsJson() || $request->ajax()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Cannot change name of core system role. You can only update permissions.'
                    ], 403);
                }
                return redirect()->back()->with('error', 'Cannot change name of core system role');
            }

            // Only validate and update name if it's changed or not a core role
            if (!$isCoreRole || $roleName !== $role->name) {
                $request->merge(['name' => $roleName]);

                $request->validate([
                    'name' => 'required|string|max:255|unique:roles,name,' . $role->id . '|regex:/^[a-z0-9-]+$/',
                    'permissions' => 'array'
                ]);

                $role->update(['name' => $roleName]);
            } else {
                // For core roles, only validate permissions
                $request->validate([
                    'permissions' => 'array'
                ]);
            }

            // Always allow updating permissions (even for core roles)
            $role->syncPermissions($request->permissions ?? []);

            // Return JSON for AJAX requests
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Role updated successfully',
                    'data' => $role
                ]);
            }

            return redirect()->back()->with('success', 'Role updated successfully.');
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

    public function deleteRole(Role $role)
    {
        // Authorization check
        Gate::authorize('manageRolesAndPermissions');

        try {
            // Prevent deletion of core roles
            if (RoleHelper::isCoreRole($role->name)) {
                if (request()->expectsJson() || request()->ajax()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Cannot delete core system role'
                    ], 403);
                }
                return redirect()->back()->with('error', 'Cannot delete core system role');
            }

            if ($role->users()->count() > 0) {
                if (request()->expectsJson() || request()->ajax()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Cannot delete role that has users assigned'
                    ], 403);
                }
                return redirect()->back()->with('error', 'Cannot delete role that has users assigned.');
            }

            $role->delete();

            // Return JSON for AJAX requests
            if (request()->expectsJson() || request()->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Role deleted successfully'
                ]);
            }

            return redirect()->back()->with('success', 'Role deleted successfully.');
        } catch (\Exception $e) {
            if (request()->expectsJson() || request()->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error deleting role: ' . $e->getMessage()
                ], 500);
            }

            return redirect()->back()->with('error', 'Error deleting role: ' . $e->getMessage());
        }
    }

    public function assignRoleToUser(Request $request)
    {
        // Authorization check
        Gate::authorize('manageRolesAndPermissions');

        $request->validate([
            'user_id' => 'required|exists:users,id',
            'role_id' => 'required|exists:roles,id'
        ]);

        $user = User::findOrFail($request->user_id);
        $role = Role::findOrFail($request->role_id);

        // IMPORTANT: Use syncRoles([$role]) to ensure user has ONLY ONE role
        // assignRole() would ADD role (allowing multiple), syncRoles() REPLACES all roles
        $user->syncRoles([$role]);

        return redirect()->back()->with('success', 'Role assigned to user successfully.');
    }

    public function removeRoleFromUser(Request $request)
    {
        // Authorization check
        Gate::authorize('manageRolesAndPermissions');

        $request->validate([
            'user_id' => 'required|exists:users,id',
            'role_id' => 'required|exists:roles,id'
        ]);

        $user = User::findOrFail($request->user_id);
        $role = Role::findOrFail($request->role_id);

        $user->removeRole($role);

        return redirect()->back()->with('success', 'Role removed from user successfully.');
    }

    public function getRolePermissions(Role $role)
    {
        // Authorization check
        Gate::authorize('manageRolesAndPermissions');

        try {
            $permissions = $role->permissions->pluck('name')->toArray();

            return response()->json([
                'success' => true,
                'role_name' => $role->name,
                'permissions' => $permissions
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error loading role permissions: ' . $e->getMessage()
            ], 500);
        }
    }

    public function getUsersWithRoles()
    {
        // Authorization check
        Gate::authorize('manageRolesAndPermissions');

        // SECURITY: Limit data exposure - only return essential fields
        $users = User::with('roles')
            ->select('id', 'name', 'email', 'created_at')
            ->paginate(50); // Add pagination instead of returning all

        return response()->json([
            'success' => true,
            'data' => $users->items(),
            'pagination' => [
                'current_page' => $users->currentPage(),
                'total' => $users->total(),
                'per_page' => $users->perPage(),
                'last_page' => $users->lastPage(),
            ]
        ]);
    }
}
