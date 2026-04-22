<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class FixUserRoles extends Command
{
    protected $signature = 'fix:user-roles';
    protected $description = 'Fix user roles and assign proper permissions';

    public function handle()
    {
        $this->info('Creating roles and permissions...');

        // Create roles
        $superadminRole = Role::firstOrCreate(['name' => 'superadmin']);
        $adminRole = Role::firstOrCreate(['name' => 'admin']);
        $guruRole = Role::firstOrCreate(['name' => 'guru']);
        $siswaRole = Role::firstOrCreate(['name' => 'siswa']);
        $sarprasRole = Role::firstOrCreate(['name' => 'sarpras']);

        // Create permissions for each module
        $permissions = [
            // User Management
            'users.view',
            'users.create',
            'users.edit',
            'users.delete',

            // Role & Permission Management
            'roles.view',
            'roles.create',
            'roles.edit',
            'roles.delete',
            'permissions.view',
            'permissions.create',
            'permissions.edit',
            'permissions.delete',

            // Academic Management
            'guru.view',
            'guru.create',
            'guru.edit',
            'guru.delete',
            'guru.import',
            'guru.export',
            'siswa.view',
            'siswa.create',
            'siswa.edit',
            'siswa.delete',
            'siswa.import',
            'siswa.export',

            // E-OSIS
            'osis.view',
            'osis.create',
            'osis.edit',
            'osis.delete',
            'osis.vote',
            'osis.results',

            // E-Lulus
            'lulus.view',
            'lulus.create',
            'lulus.edit',
            'lulus.delete',
            'lulus.import',
            'lulus.export',

            // Sarpras
            'sarpras.view',
            'sarpras.create',
            'sarpras.edit',
            'sarpras.delete',
            'sarpras.import',
            'sarpras.export',
            'sarpras.barcode',
            'sarpras.maintenance',

            // Instagram
            'instagram.view',
            'instagram.manage',
            'instagram.analytics',

            // Pages
            'pages.view',
            'pages.create',
            'pages.edit',
            'pages.delete',
            'pages.publish',

            // System
            'system.analytics',
            'system.health',
            'system.notifications',
            'system.settings',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // Assign permissions to roles
        $this->assignPermissionsToRoles($superadminRole, $adminRole, $guruRole, $siswaRole, $sarprasRole);

        // Fix existing users
        $this->fixExistingUsers();

        $this->info('User roles and permissions fixed successfully!');
    }

    private function assignPermissionsToRoles($superadminRole, $adminRole, $guruRole, $siswaRole, $sarprasRole)
    {
        // Superadmin gets all permissions (only superadmin can access dashboard)
        $superadminRole->givePermissionTo(Permission::all());

        // Default roles are just examples - superadmin will create custom roles
        // These are just for reference, actual roles will be created dynamically
    }

    private function fixExistingUsers()
    {
        // Fix superadmin user
        $superadmin = User::where('email', 'superadmin@sekolah.com')->first();
        if ($superadmin) {
            $superadmin->assignRole('superadmin');
            $this->info('Superadmin role assigned to superadmin@sekolah.com');
        }

        // Fix admin user
        $admin = User::where('email', 'admin@sekolah.com')->first();
        if ($admin) {
            $admin->assignRole('admin');
            $this->info('Admin role assigned to admin@sekolah.com');
        }
    }
}
