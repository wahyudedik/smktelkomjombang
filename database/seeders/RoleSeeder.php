<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Helpers\RoleHelper;
use Illuminate\Support\Facades\DB;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * 
     * This seeder creates CORE system roles only.
     * Custom roles can be created via admin interface at /admin/roles or /admin/role-permissions
     */
    public function run(): void
    {
        // Use transaction to ensure data consistency
        DB::transaction(function () {
            // Create core system roles (these cannot be deleted or renamed)
            $coreRoles = RoleHelper::getCoreRoles();

            foreach ($coreRoles as $roleName) {
                // Use updateOrCreate to ensure display_name is always set, even for existing roles
                Role::updateOrCreate([
                    'name' => $roleName,
                    'guard_name' => 'web'
                ], [
                    'display_name' => ucfirst($roleName),
                    'description' => 'Core system role - cannot be deleted or renamed'
                ]);
            }

            // Create permissions for each module
            $modules = [
                'instagram',
                'pages',
                'guru',
                'siswa',
                'osis',
                'lulus',
                'sarpras'
            ];

            $actions = ['create', 'read', 'update', 'delete'];

            foreach ($modules as $module) {
                foreach ($actions as $action) {
                    Permission::firstOrCreate([
                        'name' => "{$module}.{$action}",
                        'guard_name' => 'web'
                    ]);
                }
            }

            // Assign all permissions to superadmin
            $superadminRole = Role::where('name', 'superadmin')->first();
            if ($superadminRole) {
                $superadminRole->givePermissionTo(Permission::all());
            }
        });
    }
}
