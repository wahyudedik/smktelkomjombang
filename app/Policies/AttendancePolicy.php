<?php

namespace App\Policies;

use App\Models\User;

class AttendancePolicy
{
    /**
     * Check if user can manage attendance (admin or superadmin)
     */
    public function manage(User $user): bool
    {
        return $user->hasRole('superadmin') || $user->hasRole('admin');
    }

    /**
     * Check if user can view attendance (includes guru role)
     */
    public function viewAny(User $user): bool
    {
        return $user->hasAnyRole(['superadmin', 'admin', 'guru']);
    }

    /**
     * Check if user can approve/reject excuses
     */
    public function approveExcuse(User $user): bool
    {
        return $user->hasRole('superadmin') ||
               ($user->hasRole('admin') && $user->can('excuses.approve'));
    }
}
