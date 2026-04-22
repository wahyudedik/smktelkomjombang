<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;
use App\Models\Permission;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create Roles using Spatie
        $superadminRole = Role::firstOrCreate(['name' => 'superadmin']);
        $adminRole = Role::firstOrCreate(['name' => 'admin']);
        $guruRole = Role::firstOrCreate(['name' => 'guru']);
        $siswaRole = Role::firstOrCreate(['name' => 'siswa']);
        $sarprasRole = Role::firstOrCreate(['name' => 'sarpras']);

        // Create Permissions using Spatie
        $permissions = [
            // User Management
            'users.create',
            'users.read',
            'users.update',
            'users.delete',

            // Instagram Module
            'instagram.create',
            'instagram.read',
            'instagram.update',
            'instagram.delete',

            // Pages Module
            'pages.create',
            'pages.read',
            'pages.update',
            'pages.delete',

            // Guru Module
            'guru.create',
            'guru.read',
            'guru.update',
            'guru.delete',

            // Siswa Module
            'siswa.create',
            'siswa.read',
            'siswa.update',
            'siswa.delete',

            // OSIS Module
            'osis.create',
            'osis.read',
            'osis.update',
            'osis.delete',

            // Lulus Module
            'lulus.create',
            'lulus.read',
            'lulus.update',
            'lulus.delete',

            // Sarpras Module
            'sarpras.create',
            'sarpras.read',
            'sarpras.update',
            'sarpras.delete',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // Assign permissions to roles using Spatie
        $allPermissions = Permission::all();
        $superadminRole->givePermissionTo($allPermissions);

        // Admin gets all permissions except user management
        $adminPermissions = Permission::whereNotIn('name', ['users.create', 'users.read', 'users.update', 'users.delete'])->get();
        $adminRole->givePermissionTo($adminPermissions);

        // Guru gets limited permissions
        $guruPermissions = Permission::whereIn('name', ['guru.read', 'guru.update', 'siswa.read', 'pages.read'])->get();
        $guruRole->givePermissionTo($guruPermissions);

        // Siswa gets very limited permissions
        $siswaPermissions = Permission::whereIn('name', ['siswa.read', 'pages.read'])->get();
        $siswaRole->givePermissionTo($siswaPermissions);

        // Sarpras gets sarpras module permissions
        $sarprasPermissions = Permission::whereIn('name', ['sarpras.create', 'sarpras.read', 'sarpras.update', 'sarpras.delete'])->get();
        $sarprasRole->givePermissionTo($sarprasPermissions);
    }
}
