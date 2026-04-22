<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Spatie\Permission\Models\Role;
use App\Helpers\RoleHelper;

class AssignRolesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * 
     * This seeder assigns core system roles to users based on their email.
     * Uses RoleHelper to get core roles dynamically.
     */
    public function run(): void
    {
        // Get core roles dynamically from RoleHelper
        $coreRoles = RoleHelper::getCoreRoles();

        // Assign roles to existing users based on their email
        // Format: email => role_name (must be a core role)
        $userRoles = [
            'superadmin@sekolah.com' => 'superadmin',
            'admin@sekolah.com' => 'admin',
            'guru@sekolah.com' => 'guru', 
            'siswa@sekolah.com' => 'siswa',
            'sarpras@sekolah.com' => 'sarpras',
        ];

        foreach ($userRoles as $email => $roleName) {
            // Validate that roleName is a core role
            if (!RoleHelper::isCoreRole($roleName)) {
                echo "Warning: Role '{$roleName}' is not a core role. Skipping...\n";
                continue;
            }

            $user = User::where('email', $email)->first();
            $role = Role::where('name', $roleName)->first();

            if ($user && $role) {
                $user->assignRole($role);

                // Roles are managed by Spatie Permission - no need to sync user_type

                echo "Assigned role '{$roleName}' to user '{$user->name}'\n";
            } elseif (!$user) {
                echo "Warning: User with email '{$email}' not found. Skipping...\n";
            } elseif (!$role) {
                echo "Warning: Role '{$roleName}' not found. Run RoleSeeder first.\n";
            }
        }
    }
}
