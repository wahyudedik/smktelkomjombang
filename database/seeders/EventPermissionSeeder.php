<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Permission;
use Spatie\Permission\Models\Role;

class EventPermissionSeeder extends Seeder
{
    /**
     * Seed permissions untuk modul Events dan assign ke role admin & superadmin.
     */
    public function run(): void
    {
        $permissions = [
            [
                'name'         => 'events.view',
                'display_name' => 'Events - Lihat Data',
                'description'  => 'Permission untuk melihat daftar kegiatan sekolah',
                'module'       => 'events',
                'action'       => 'view',
                'guard_name'   => 'web',
            ],
            [
                'name'         => 'events.create',
                'display_name' => 'Events - Tambah Data',
                'description'  => 'Permission untuk menambah kegiatan sekolah baru',
                'module'       => 'events',
                'action'       => 'create',
                'guard_name'   => 'web',
            ],
            [
                'name'         => 'events.edit',
                'display_name' => 'Events - Edit Data',
                'description'  => 'Permission untuk mengedit data kegiatan sekolah',
                'module'       => 'events',
                'action'       => 'edit',
                'guard_name'   => 'web',
            ],
            [
                'name'         => 'events.delete',
                'display_name' => 'Events - Hapus Data',
                'description'  => 'Permission untuk menghapus kegiatan sekolah',
                'module'       => 'events',
                'action'       => 'delete',
                'guard_name'   => 'web',
            ],
        ];

        // Buat permission jika belum ada
        foreach ($permissions as $perm) {
            Permission::firstOrCreate(['name' => $perm['name'], 'guard_name' => 'web'], $perm);
        }

        $this->command->info('Events permissions created.');

        // Assign semua events permissions ke role admin dan superadmin
        $permissionNames = array_column($permissions, 'name');

        foreach (['admin', 'superadmin'] as $roleName) {
            $role = Role::findByName($roleName, 'web');
            if ($role) {
                $role->givePermissionTo($permissionNames);
                $this->command->info("Permissions assigned to role: {$roleName}");
            }
        }
    }
}
