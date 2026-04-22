<?php

use App\Helpers\NotificationHelper;
use App\Helpers\RoleHelper;
use App\Models\User;

if (!function_exists('notify')) {
    /**
     * Send notification to user(s)
     * 
     * @param User|array $users
     * @param string $title
     * @param string $message
     * @param string $type
     * @return void
     */
    function notify($users, string $title, string $message, string $type = 'info', array $metadata = [])
    {
        NotificationHelper::send($users, $title, $message, $type, $metadata);
    }
}

if (!function_exists('get_core_roles')) {
    /**
     * Get list of core system roles
     */
    function get_core_roles(): array
    {
        return RoleHelper::getCoreRoles();
    }
}

if (!function_exists('is_core_role')) {
    /**
     * Check if a role is a core system role
     */
    function is_core_role(string $roleName): bool
    {
        return RoleHelper::isCoreRole($roleName);
    }
}

if (!function_exists('get_role_badge_color')) {
    /**
     * Get role badge color (supports custom roles)
     */
    function get_role_badge_color(string $roleName): string
    {
        return RoleHelper::getRoleBadgeColor($roleName);
    }
}

if (!function_exists('get_role_display_name')) {
    /**
     * Get role display name with fallback
     */
    function get_role_display_name($role): string
    {
        return RoleHelper::getRoleDisplayName($role);
    }
}
