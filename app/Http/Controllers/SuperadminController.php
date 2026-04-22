<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Role;
use App\Models\Permission;
use App\Models\ModuleAccess;
use App\Models\AuditLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\UserImport;
use App\Exports\UserExport;

class SuperadminController extends Controller
{
    /**
     * Display superadmin dashboard.
     */
    public function dashboard()
    {
        // Cache dashboard stats for 5 minutes to improve performance
        $stats = cache()->remember('superadmin_dashboard_stats', 300, function () {
            return [
                'total_users' => User::count(),
                'total_roles' => Role::count(),
                'total_permissions' => Permission::count(),
                'total_siswas' => \App\Models\Siswa::count(),
                'total_gurus' => \App\Models\Guru::count(),
                'total_pages' => \App\Models\Page::count(),
                'total_instagram_posts' => \App\Models\InstagramSetting::count(),
            ];
        });

        // Don't cache recent activities - needs to be fresh
        $stats['recent_activities'] = AuditLog::with('user')
            ->latest()
            ->limit(10)
            ->get();

        return view('dashboards.admin', compact('stats'));
    }

    /**
     * Display user management.
     */
    public function users(Request $request)
    {
        $query = User::query()->with('roles', 'moduleAccess');

        // Filter by role if provided (handle array input from parameter pollution)
        if ($request->filled('role')) {
            $roleName = $request->role;
            // Handle if role is array (parameter pollution)
            if (is_array($roleName)) {
                $roleName = !empty($roleName) ? $roleName[0] : null;
            }

            if ($roleName) {
                $query->whereHas('roles', function ($q) use ($roleName) {
                    $q->where('name', $roleName);
                });
            }
        }

        // Search functionality (handle array input from parameter pollution)
        if ($request->filled('search')) {
            $search = $request->search;
            // Handle if search is array (parameter pollution)
            if (is_array($search)) {
                $search = !empty($search) ? trim($search[0]) : '';
            } else {
                $search = trim($search);
            }

            if ($search !== '') {
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', '%' . $search . '%')
                        ->orWhere('email', 'like', '%' . $search . '%');
                });
            }
        }

        $users = $query->select('id', 'name', 'email', 'email_verified_at', 'is_verified_by_admin', 'created_at', 'updated_at')
            ->orderBy('created_at', 'desc')
            ->paginate(15)
            ->withQueryString();

        return view('superadmin.users.index', compact('users'));
    }

    /**
     * Show user details.
     */
    public function showUser(User $user)
    {
        $user->load('roles', 'moduleAccess', 'auditLogs');
        return view('superadmin.users.show', compact('user'));
    }

    /**
     * Show create user form.
     */
    public function createUser()
    {
        $roles = Role::where('is_active', true)->get();
        return view('superadmin.users.create', compact('roles'));
    }

    /**
     * Store new user.
     */
    public function storeUser(Request $request)
    {
        // Different validation for AJAX requests (from guru/create modal)
        $isAjax = $request->wantsJson() || $request->ajax();

        $validationRules = [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => $isAjax ? 'required|string|min:8' : 'required|string|min:8|confirmed',
            'roles' => 'array',
            'roles.*' => 'exists:roles,id',
        ];

        $validated = $request->validate($validationRules);

        // Use transaction for user creation with roles and audit log
        $user = DB::transaction(function () use ($request) {
            // Get roles for validation
            if ($request->has('roles') && !empty($request->roles)) {
                $roleIds = $request->roles;
                $roleNames = Role::whereIn('id', $roleIds)->pluck('name')->toArray();
                
                // Validate that all roles exist
                if (count($roleIds) !== count($roleNames)) {
                    throw new \InvalidArgumentException('One or more selected roles not found.');
                }
            }

            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'email_verified_at' => now(), // Auto verify when created by superadmin
                'is_verified_by_admin' => true, // Mark as verified by admin
            ]);

            if ($request->has('roles') && !empty($request->roles)) {
                $roleIds = $request->roles;
                $roleNames = Role::whereIn('id', $roleIds)->pluck('name')->toArray();

                // Validate that all roles exist
                if (count($roleIds) !== count($roleNames)) {
                    throw new \InvalidArgumentException('One or more selected roles not found.');
                }

                // IMPORTANT: Use syncRoles() to ensure user has ONLY the selected roles
                // If only one role provided, user will have only that one role
                // If multiple roles provided, user will have all of them (but typically only one)
                $user->syncRoles($roleNames);
            }

            // Log the action
            AuditLog::createLog(
                'user_created',
                Auth::id(),
                'User',
                $user->id,
                null,
                $user->toArray(),
                $request->ip(),
                $request->userAgent()
            );

            // Clear dashboard cache
            cache()->forget('superadmin_dashboard_stats');
            cache()->forget('dashboard_stats_' . Auth::id());
            cache()->forget('module_usage_counts');

            return $user;
        });

        // Return JSON for AJAX requests
        if ($isAjax) {
            return response()->json([
                'success' => true,
                'message' => 'User created successfully.',
                'data' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'roles' => $user->roles->pluck('name')->toArray(),
                ]
            ]);
        }

        return redirect()->route('admin.superadmin.users')
            ->with('success', 'User created successfully.');
    }

    /**
     * Show edit user form.
     */
    public function editUser(User $user)
    {
        $roles = Role::where('is_active', true)->get();
        $user->load('roles');
        return view('superadmin.users.edit', compact('user', 'roles'));
    }

    /**
     * Update user.
     */
    public function updateUser(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'password' => 'nullable|string|min:8|confirmed',
            'roles' => 'array',
            'roles.*' => 'exists:roles,id',
        ]);

        $oldValues = $user->toArray();

        // Use transaction for user update with roles and audit log
        DB::transaction(function () use ($request, $user, $oldValues) {
            // Build update array
            $updateData = [
                'name' => $request->name,
                'email' => $request->email,
            ];

            $user->update($updateData);

            if ($request->filled('password')) {
                $user->update(['password' => Hash::make($request->password)]);
            }

            // Only update roles if roles array is provided and not empty
            // If roles array is empty, it means user wants to remove all roles, which is valid
            // But we need to check: if roles key exists in request, update it (even if empty)
            if ($request->has('roles')) {
                $roleIds = $request->roles ?? [];
                if (!empty($roleIds)) {
                    $roleNames = Role::whereIn('id', $roleIds)->pluck('name')->toArray();

                    // Validate that all roles exist
                    if (count($roleIds) !== count($roleNames)) {
                        throw new \InvalidArgumentException('One or more selected roles not found.');
                    }

                    $user->syncRoles($roleNames);
                } else {
                    // If roles array is empty, remove all roles
                    $user->syncRoles([]);
                }
            }
            // If roles key doesn't exist in request, don't update roles (keep existing roles)

            // Roles are managed by Spatie Permission - no need to sync user_type

            // Log the action
            AuditLog::createLog(
                'user_updated',
                Auth::id(),
                'User',
                $user->id,
                $oldValues,
                $user->fresh()->toArray(),
                $request->ip(),
                $request->userAgent()
            );

            // Clear dashboard cache
            cache()->forget('superadmin_dashboard_stats');
            cache()->forget('dashboard_stats_' . Auth::id());
            cache()->forget('module_usage_counts');
        });

        return redirect()->route('admin.superadmin.users')
            ->with('success', 'User updated successfully.');
    }

    /**
     * Delete user.
     */
    public function destroyUser(User $user)
    {
        // Prevent deleting superadmin
        if ($user->hasRole('superadmin')) {
            return redirect()->back()
                ->with('error', 'Cannot delete superadmin user.');
        }

        $oldValues = $user->toArray();
        $user->delete();

        // Log the action
        AuditLog::createLog(
            'user_deleted',
            Auth::id(),
            'User',
            $user->id,
            $oldValues,
            null,
            request()->ip(),
            request()->userAgent()
        );

        // Clear dashboard cache
        cache()->forget('superadmin_dashboard_stats');
        cache()->forget('dashboard_stats_' . Auth::id());
        cache()->forget('module_usage_counts');
        cache()->forget('count_siswa');
        cache()->forget('count_guru');

        return redirect()->route('admin.superadmin.users')
            ->with('success', 'User deleted successfully.');
    }

    /**
     * Manage user module access.
     */
    public function moduleAccess(User $user)
    {
        $modules = ['instagram', 'pages', 'guru', 'siswa', 'osis', 'lulus', 'sarpras', 'settings'];
        $user->load('moduleAccess');

        return view('superadmin.users.module-access', compact('user', 'modules'));
    }

    /**
     * Update user module access.
     */
    public function updateModuleAccess(Request $request, User $user)
    {
        $request->validate([
            'modules' => 'required|array',
            'modules.*.module_name' => 'required|string',
            'modules.*.can_access' => 'boolean',
            'modules.*.can_create' => 'boolean',
            'modules.*.can_read' => 'boolean',
            'modules.*.can_update' => 'boolean',
            'modules.*.can_delete' => 'boolean',
        ]);

        foreach ($request->modules as $moduleData) {
            ModuleAccess::updateOrCreate(
                [
                    'user_id' => $user->id,
                    'module_name' => $moduleData['module_name'],
                ],
                [
                    'can_access' => $moduleData['can_access'] ?? false,
                    'can_create' => $moduleData['can_create'] ?? false,
                    'can_read' => $moduleData['can_read'] ?? false,
                    'can_update' => $moduleData['can_update'] ?? false,
                    'can_delete' => $moduleData['can_delete'] ?? false,
                ]
            );
        }

        // Log the action
        AuditLog::createLog(
            'module_access_updated',
            Auth::id(),
            'User',
            $user->id,
            null,
            $request->modules,
            $request->ip(),
            $request->userAgent()
        );

        return redirect()->route('admin.superadmin.users.module-access', $user)
            ->with('success', 'Module access updated successfully.');
    }

    /**
     * Show import form for users.
     */
    public function importUsers()
    {
        return view('superadmin.users.import');
    }

    /**
     * Download template Excel for user import.
     */
    public function downloadUserTemplate()
    {
        // Create sample data for template
        $sampleData = [
            [
                'name' => 'Admin Sekolah',
                'email' => 'admin@sekolah.com',
                'role' => 'admin',
                'password' => 'password123',
                'email_verified_at' => '2024-01-01 00:00:00',
                'is_verified_by_admin' => 'yes'
            ],
            [
                'name' => 'Guru Matematika',
                'email' => 'guru@sekolah.com',
                'role' => 'guru',
                'password' => 'password123',
                'email_verified_at' => '2024-01-01 00:00:00',
                'is_verified_by_admin' => 'yes'
            ],
            [
                'name' => 'Siswa Contoh',
                'email' => 'siswa@sekolah.com',
                'role' => 'siswa',
                'password' => 'password123',
                'email_verified_at' => '',
                'is_verified_by_admin' => 'no'
            ]
        ];

        // Create a new export class for template
        $templateExport = new class($sampleData) implements \Maatwebsite\Excel\Concerns\FromArray, \Maatwebsite\Excel\Concerns\WithHeadings, \Maatwebsite\Excel\Concerns\WithStyles, \Maatwebsite\Excel\Concerns\WithColumnWidths {
            protected $data;

            public function __construct($data)
            {
                $this->data = $data;
            }

            public function array(): array
            {
                return $this->data;
            }

            public function headings(): array
            {
                return [
                    'name',
                    'email',
                    'role',
                    'password',
                    'email_verified_at',
                    'is_verified_by_admin'
                ];
            }

            public function styles(\PhpOffice\PhpSpreadsheet\Worksheet\Worksheet $sheet)
            {
                return [
                    1 => ['font' => ['bold' => true]],
                ];
            }

            public function columnWidths(): array
            {
                return [
                    'A' => 25,
                    'B' => 30,
                    'C' => 15,
                    'D' => 20,
                    'E' => 20,
                    'F' => 20,
                ];
            }
        };

        return Excel::download($templateExport, 'template-import-users.xlsx');
    }

    /**
     * Process user import.
     */
    public function processUserImport(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv|max:2048',
        ]);

        try {
            // Get file info for logging
            $file = $request->file('file');
            $fileName = $file->getClientOriginalName();
            $fileSize = $file->getSize();

            Log::info("Starting user import process", [
                'file_name' => $fileName,
                'file_size' => $fileSize,
                'user_id' => Auth::id()
            ]);

            // Create import instance
            $import = new UserImport();

            // Import the file
            Excel::import($import, $file);

            // Get import results
            $importedCount = $import->getRowCount() ?? 0;
            $errors = $import->errors();
            $failures = $import->failures();

            Log::info("User import completed", [
                'imported_count' => $importedCount,
                'errors_count' => count($errors),
                'failures_count' => count($failures)
            ]);

            // Prepare success message with details
            $message = "Data user berhasil diimpor!";
            $details = [];

            if ($importedCount > 0) {
                $details[] = "Berhasil mengimpor {$importedCount} user";
            }

            if (count($failures) > 0) {
                $details[] = count($failures) . " user gagal diimpor (cek log untuk detail)";
            }

            if (count($errors) > 0) {
                $details[] = count($errors) . " user memiliki error validasi";
            }

            if (!empty($details)) {
                $message .= " (" . implode(', ', $details) . ")";
            }

            return redirect()->route('admin.superadmin.users')
                ->with('success', $message);
        } catch (\Exception $e) {
            Log::error("User import failed", [
                'error' => $e->getMessage(),
                'file' => $request->file('file')->getClientOriginalName(),
                'trace' => $e->getTraceAsString()
            ]);

            return redirect()->back()
                ->with('error', 'Terjadi kesalahan saat mengimpor data: ' . $e->getMessage());
        }
    }

    /**
     * Export users data.
     */
    public function exportUsers(Request $request)
    {
        $query = User::query();

        // Apply filters
        if ($request->has('role') && $request->role !== '') {
            $query->whereHas('roles', function ($q) use ($request) {
                $q->where('name', $request->role);
            });
        }

        if ($request->has('is_verified_by_admin') && $request->is_verified_by_admin !== '') {
            $query->where('is_verified_by_admin', $request->is_verified_by_admin);
        }

        $users = $query->get();

        return Excel::download(new UserExport($users), 'users-' . date('Y-m-d') . '.xlsx');
    }
}
