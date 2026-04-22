<?php

namespace App\Helpers;

class RoleHelper
{
    /**
     * Get list of core system roles that cannot be deleted or renamed
     */
    public static function getCoreRoles(): array
    {
        return ['superadmin', 'admin', 'guru', 'siswa', 'sarpras'];
    }

    /**
     * Check if a role is a core system role
     */
    public static function isCoreRole(string $roleName): bool
    {
        return in_array(strtolower($roleName), self::getCoreRoles());
    }

    /**
     * Check if user is superadmin (has all permissions by default)
     */
    public static function isSuperadmin($user): bool
    {
        if ($user instanceof \App\Models\User) {
            return $user->hasRole('superadmin');
        }

        // If it's just a string/ID, check differently
        if (is_string($user)) {
            $userModel = \App\Models\User::where('email', $user)
                ->orWhere('id', $user)
                ->first();
            return $userModel ? $userModel->hasRole('superadmin') : false;
        }

        return false;
    }

    /**
     * Get role badge color based on role name (supports custom roles)
     */
    public static function getRoleBadgeColor(string $roleName): string
    {
        $colors = [
            'superadmin' => 'bg-red-100 text-red-800',
            'admin' => 'bg-blue-100 text-blue-800',
            'guru' => 'bg-green-100 text-green-800',
            'siswa' => 'bg-purple-100 text-purple-800',
            'sarpras' => 'bg-yellow-100 text-yellow-800',
        ];

        $roleLower = strtolower($roleName);
        return $colors[$roleLower] ?? 'bg-gray-100 text-gray-800';
    }

    /**
     * Get role display name (with fallback to capitalized role name)
     */
    public static function getRoleDisplayName($role): string
    {
        if (is_string($role)) {
            $roleModel = \Spatie\Permission\Models\Role::where('name', $role)->first();
            return $roleModel ? ($roleModel->display_name ?? ucfirst($role)) : ucfirst($role);
        }

        if ($role instanceof \Spatie\Permission\Models\Role) {
            return $role->display_name ?? ucfirst($role->name);
        }

        return ucfirst((string) $role);
    }
}
