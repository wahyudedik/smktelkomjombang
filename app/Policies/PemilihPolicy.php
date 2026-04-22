<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Pemilih;
use Illuminate\Auth\Access\HandlesAuthorization;

class PemilihPolicy
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
    public function view(User $user, Pemilih $pemilih): bool
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
    public function update(User $user, Pemilih $pemilih): bool
    {
        return $user->can('osis.edit') || $user->can('osis.update') || $user->hasRole(['superadmin', 'admin', 'osis']);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Pemilih $pemilih): bool
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
}
