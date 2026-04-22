<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Guru;
use Illuminate\Auth\Access\HandlesAuthorization;

class GuruPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->can('guru.view') || $user->can('guru.read') || $user->hasRole(['superadmin', 'admin', 'guru']);
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Guru $guru): bool
    {
        return $user->can('guru.view') || $user->can('guru.read') || $user->hasRole(['superadmin', 'admin', 'guru']);
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->can('guru.create') || $user->hasRole(['superadmin', 'admin']);
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Guru $guru): bool
    {
        return $user->can('guru.edit') || $user->can('guru.update') || $user->hasRole(['superadmin', 'admin']);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Guru $guru): bool
    {
        return $user->can('guru.delete') || $user->hasRole(['superadmin', 'admin']);
    }

    /**
     * Determine whether the user can import data.
     */
    public function import(User $user): bool
    {
        return $user->can('guru.import') || $user->hasRole(['superadmin', 'admin']);
    }

    /**
     * Determine whether the user can export data.
     */
    public function export(User $user): bool
    {
        return $user->can('guru.export') || $user->hasRole(['superadmin', 'admin']);
    }
}
