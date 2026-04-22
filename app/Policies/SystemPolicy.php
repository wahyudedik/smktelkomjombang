<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class SystemPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view analytics.
     */
    public function viewAnalytics(User $user): bool
    {
        return $user->can('system.analytics');
    }

    /**
     * Determine whether the user can view system health.
     */
    public function viewSystemHealth(User $user): bool
    {
        return $user->can('system.health');
    }

    /**
     * Determine whether the user can manage notifications.
     */
    public function manageNotifications(User $user): bool
    {
        return $user->can('system.notifications');
    }

    /**
     * Determine whether the user can view notifications.
     */
    public function viewNotifications(User $user): bool
    {
        return $user->can('system.notifications');
    }

    /**
     * Determine whether the user can send notifications.
     */
    public function sendNotifications(User $user): bool
    {
        return $user->can('system.notifications');
    }

    /**
     * Determine whether the user can manage system settings.
     */
    public function manageSettings(User $user): bool
    {
        return $user->can('system.settings');
    }

    /**
     * Determine whether the user can view system settings.
     */
    public function viewSettings(User $user): bool
    {
        return $user->can('system.settings');
    }

    /**
     * Determine whether the user can access admin panel.
     */
    public function accessAdminPanel(User $user): bool
    {
        return $user->hasAnyRole(['superadmin', 'admin']);
    }

    /**
     * Determine whether the user can access superadmin features.
     */
    public function accessSuperadminFeatures(User $user): bool
    {
        return $user->hasRole('superadmin');
    }
}