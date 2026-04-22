<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Str;

class PermissionController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'verified']);
        $this->middleware('role:superadmin|admin')->except(['index', 'show']);
    }

    /**
     * Display a listing of permissions
     */
    public function index(Request $request)
    {
        $query = Permission::query();

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('guard_name', 'like', "%{$search}%");
            });
        }

        // Filter by guard
        if ($request->filled('guard')) {
            $query->where('guard_name', $request->guard);
        }

        // Sort
        $sortBy = $request->get('sort_by', 'name');
        $sortDirection = $request->get('sort_direction', 'asc');
        $query->orderBy($sortBy, $sortDirection);

        $permissions = $query->paginate(20);
        $roles = Role::all();

        return view('permissions.index', compact('permissions', 'roles'));
    }

    /**
     * Show the form for creating a new permission
     */
    public function create()
    {
        $modules = $this->getAvailableModules();
        $actions = $this->getAvailableActions();

        return view('permissions.create', compact('modules', 'actions'));
    }

    /**
     * Store a newly created permission
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:permissions',
            'display_name' => 'required|string|max:255',
            'description' => 'nullable|string|max:500',
            'module' => 'required|string|max:100',
            'action' => 'required|string|max:100',
            'guard_name' => 'required|string|in:web,api',
            'roles' => 'nullable|array',
            'roles.*' => 'exists:roles,id'
        ]);

        // Create permission
        $permission = Permission::create([
            'name' => $request->name,
            'guard_name' => $request->guard_name,
            'display_name' => $request->display_name,
            'description' => $request->description,
            'module' => $request->module,
            'action' => $request->action,
        ]);

        // Assign to roles if specified
        if ($request->filled('roles')) {
            $roles = Role::whereIn('id', $request->roles)->get();
            $permission->syncRoles($roles);
        }

        return redirect()->route('admin.permissions.index')
            ->with('success', 'Permission berhasil dibuat.');
    }

    /**
     * Display the specified permission
     */
    public function show(Permission $permission)
    {
        $permission->load('roles');
        $users = $permission->users()->paginate(20);

        return view('permissions.show', compact('permission', 'users'));
    }

    /**
     * Show the form for editing the specified permission
     */
    public function edit(Permission $permission)
    {
        $modules = $this->getAvailableModules();
        $actions = $this->getAvailableActions();
        $roles = Role::all();
        $permissionRoles = $permission->roles->pluck('id')->toArray();

        return view('permissions.edit', compact('permission', 'modules', 'actions', 'roles', 'permissionRoles'));
    }

    /**
     * Update the specified permission
     */
    public function update(Request $request, Permission $permission)
    {
        $request->validate([
            'display_name' => 'required|string|max:255',
            'description' => 'nullable|string|max:500',
            'module' => 'required|string|max:100',
            'action' => 'required|string|max:100',
            'guard_name' => 'required|string|in:web,api',
            'roles' => 'nullable|array',
            'roles.*' => 'exists:roles,id'
        ]);

        // Update permission
        $permission->update([
            'display_name' => $request->display_name,
            'description' => $request->description,
            'module' => $request->module,
            'action' => $request->action,
            'guard_name' => $request->guard_name,
        ]);

        // Sync roles
        if ($request->filled('roles')) {
            $roles = Role::whereIn('id', $request->roles)->get();
            $permission->syncRoles($roles);
        } else {
            $permission->syncRoles([]);
        }

        return redirect()->route('admin.permissions.index')
            ->with('success', 'Permission berhasil diperbarui.');
    }

    /**
     * Remove the specified permission
     */
    public function destroy(Permission $permission)
    {
        // Check if permission is being used
        if ($permission->users()->count() > 0) {
            return redirect()->route('admin.permissions.index')
                ->with('error', 'Permission tidak dapat dihapus karena masih digunakan oleh user.');
        }

        $permission->delete();

        return redirect()->route('admin.permissions.index')
            ->with('success', 'Permission berhasil dihapus.');
    }

    /**
     * Bulk create permissions for a module
     */
    public function bulkCreate(Request $request)
    {
        $request->validate([
            'module' => 'required|string|max:100',
            'module_display_name' => 'required|string|max:255',
            'actions' => 'required|array|min:1',
            'actions.*' => 'string|max:100',
            'guard_name' => 'required|string|in:web,api',
            'assign_to_roles' => 'nullable|array',
            'assign_to_roles.*' => 'exists:roles,id'
        ]);

        $created = 0;
        $errors = [];

        foreach ($request->actions as $action) {
            $permissionName = $request->module . '.' . $action;

            // Check if permission already exists
            if (Permission::where('name', $permissionName)->exists()) {
                $errors[] = "Permission '{$permissionName}' sudah ada.";
                continue;
            }

            try {
                $permission = Permission::create([
                    'name' => $permissionName,
                    'display_name' => $request->module_display_name . ' - ' . ucfirst($action),
                    'description' => "Permission untuk {$action} pada module {$request->module_display_name}",
                    'module' => $request->module,
                    'action' => $action,
                    'guard_name' => $request->guard_name,
                ]);

                // Assign to roles if specified
                if ($request->filled('assign_to_roles')) {
                    $roles = Role::whereIn('id', $request->assign_to_roles)->get();
                    $permission->syncRoles($roles);
                }

                $created++;
            } catch (\Exception $e) {
                $errors[] = "Gagal membuat permission '{$permissionName}': " . $e->getMessage();
            }
        }

        $message = "Berhasil membuat {$created} permission.";
        if (!empty($errors)) {
            $message .= " Error: " . implode(', ', $errors);
        }

        return redirect()->route('admin.permissions.index')
            ->with('success', $message);
    }

    /**
     * Get available modules
     */
    private function getAvailableModules(): array
    {
        return [
            'guru' => 'Manajemen Guru',
            'siswa' => 'Manajemen Siswa',
            'osis' => 'Sistem OSIS',
            'lulus' => 'Sistem Kelulusan',
            'sarpras' => 'Sarana Prasarana',
            'pages' => 'Manajemen Halaman',
            'instagram' => 'Integrasi Instagram',
            'users' => 'Manajemen User',
            'roles' => 'Manajemen Role',
            'permissions' => 'Manajemen Permission',
            'settings' => 'Pengaturan Sistem',
            'reports' => 'Laporan',
            'audit' => 'Audit Log',
            'dashboard' => 'Dashboard',
            'profile' => 'Profil User'
        ];
    }

    /**
     * Get available actions
     */
    private function getAvailableActions(): array
    {
        return [
            'view' => 'Lihat Data',
            'create' => 'Tambah Data',
            'edit' => 'Edit Data',
            'delete' => 'Hapus Data',
            'export' => 'Export Data',
            'import' => 'Import Data',
            'manage' => 'Kelola Penuh',
            'approve' => 'Setujui',
            'reject' => 'Tolak',
            'publish' => 'Publish',
            'unpublish' => 'Unpublish',
            'archive' => 'Arsip',
            'restore' => 'Restore',
            'force_delete' => 'Hapus Permanent'
        ];
    }
}
