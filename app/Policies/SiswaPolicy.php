<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Siswa;
use Illuminate\Auth\Access\HandlesAuthorization;

class SiswaPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->can('siswa.view') || $user->hasRole(['superadmin', 'admin', 'guru']);
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Siswa $siswa): bool
    {
        return $user->can('siswa.view') || $user->hasRole(['superadmin', 'admin', 'guru']);
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->can('siswa.create') || $user->hasRole(['superadmin', 'admin']);
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Siswa $siswa): bool
    {
        return $user->can('siswa.edit') || $user->can('siswa.update') || $user->hasRole(['superadmin', 'admin', 'guru']);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Siswa $siswa): bool
    {
        return $user->can('siswa.delete') || $user->hasRole(['superadmin', 'admin']);
    }

    /**
     * Determine whether the user can import data.
     */
    public function import(User $user): bool
    {
        return $user->can('siswa.import') || $user->hasRole(['superadmin', 'admin']);
    }

    /**
     * Determine whether the user can export data.
     */
    public function export(User $user): bool
    {
        return $user->can('siswa.export') || $user->hasRole(['superadmin', 'admin', 'guru']);
    }
}
