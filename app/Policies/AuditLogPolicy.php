<?php

namespace App\Policies;

use App\Models\User;
use App\Models\AuditLog;
use Illuminate\Auth\Access\HandlesAuthorization;

class AuditLogPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        // Only superadmin can view audit logs
        return $user->hasRole('superadmin');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, AuditLog $auditLog): bool
    {
        // Only superadmin can view audit logs
        return $user->hasRole('superadmin');
    }

    /**
     * Nobody can create/update/delete audit logs
     * (they are created automatically by the system)
     */
    public function create(User $user): bool
    {
        return false;
    }

    public function update(User $user, AuditLog $auditLog): bool
    {
        return false;
    }

    public function delete(User $user, AuditLog $auditLog): bool
    {
        return false;
    }
}
