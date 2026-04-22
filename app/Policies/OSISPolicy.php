<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Calon;
use Illuminate\Auth\Access\HandlesAuthorization;

class OSISPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->can('osis.view') || $user->can('osis.read') || $user->hasRole(['superadmin', 'admin', 'osis']);
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Calon $calon): bool
    {
        return $user->can('osis.view') || $user->can('osis.read') || $user->hasRole(['superadmin', 'admin', 'osis']);
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->can('osis.create') || $user->hasRole(['superadmin', 'admin', 'osis']);
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Calon $calon): bool
    {
        return $user->can('osis.edit') || $user->can('osis.update') || $user->hasRole(['superadmin', 'admin', 'osis']);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Calon $calon): bool
    {
        return $user->can('osis.delete') || $user->hasRole(['superadmin', 'admin', 'osis']);
    }

    /**
     * Determine whether the user can vote.
     */
    public function vote(User $user): bool
    {
        return $user->can('osis.vote') || $user->hasRole(['superadmin', 'admin', 'siswa']);
    }

    /**
     * Determine whether the user can view results.
     */
    public function viewResults(User $user): bool
    {
        return $user->can('osis.results') || $user->hasRole(['superadmin', 'admin', 'guru', 'siswa']);
    }

    /**
     * Determine whether the user can manage voters.
     */
    public function manageVoters(User $user): bool
    {
        return $user->can('osis.edit') || $user->can('osis.update') || $user->hasRole(['superadmin', 'admin', 'osis']);
    }

    /**
     * Determine whether the user can import candidates.
     */
    public function import(User $user): bool
    {
        return $user->can('osis.create') || $user->hasRole(['superadmin', 'admin', 'osis']);
    }

    /**
     * Determine whether the user can export data.
     */
    public function export(User $user): bool
    {
        return $user->can('osis.view') || $user->can('osis.read') || $user->hasRole(['superadmin', 'admin', 'osis']);
    }
}
