<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Maintenance;
use Illuminate\Auth\Access\HandlesAuthorization;

class MaintenancePolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->can('sarpras.view') || $user->can('sarpras.read') || $user->can('sarpras.maintenance') || $user->hasRole(['superadmin', 'admin', 'sarpras']);
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Maintenance $maintenance): bool
    {
        return $user->can('sarpras.view') || $user->can('sarpras.read') || $user->can('sarpras.maintenance') || $user->hasRole(['superadmin', 'admin', 'sarpras']);
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->can('sarpras.maintenance') || $user->hasRole(['superadmin', 'admin', 'sarpras']);
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Maintenance $maintenance): bool
    {
        return $user->can('sarpras.maintenance') || $user->hasRole(['superadmin', 'admin', 'sarpras']);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Maintenance $maintenance): bool
    {
        return $user->can('sarpras.maintenance') || $user->hasRole(['superadmin', 'admin', 'sarpras']);
    }
}
