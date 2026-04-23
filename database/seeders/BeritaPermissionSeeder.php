<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Permission;
use Spatie\Permission\Models\Role;

class BeritaPermissionSeeder extends Seeder
{
    public function run(): void
    {
        $permissions = [
            ['name' => 'berita.view',   'display_name' => 'Berita - Lihat Data',   'description' => 'Melihat daftar berita',    'module' => 'berita', 'action' => 'view',   'guard_name' => 'web'],
            ['name' => 'berita.create', 'display_name' => 'Berita - Tambah Data',  'description' => 'Menambah berita baru',     'module' => 'berita', 'action' => 'create', 'guard_name' => 'web'],
            ['name' => 'berita.edit',   'display_name' => 'Berita - Edit Data',    'description' => 'Mengedit data berita',     'module' => 'berita', 'action' => 'edit',   'guard_name' => 'web'],
            ['name' => 'berita.delete', 'display_name' => 'Berita - Hapus Data',   'description' => 'Menghapus berita',         'module' => 'berita', 'action' => 'delete', 'guard_name' => 'web'],
        ];

        foreach ($permissions as $perm) {
            Permission::firstOrCreate(['name' => $perm['name'], 'guard_name' => 'web'], $perm);
        }

        $this->command->info('Berita permissions created.');

        $permissionNames = array_column($permissions, 'name');

        foreach (['admin', 'superadmin'] as $roleName) {
            $role = Role::findByName($roleName, 'web');
            if ($role) {
                $role->givePermissionTo($permissionNames);
                $this->command->info("Berita permissions assigned to: {$roleName}");
            }
        }
    }
}
