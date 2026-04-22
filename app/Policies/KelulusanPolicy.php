<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Kelulusan;
use Illuminate\Auth\Access\HandlesAuthorization;

class KelulusanPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->can('kelulusan.view') || $user->can('lulus.read') || $user->hasRole(['superadmin', 'admin', 'guru', 'siswa']);
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Kelulusan $kelulusan): bool
    {
        return $user->can('kelulusan.view') || $user->can('lulus.read') || $user->hasRole(['superadmin', 'admin', 'guru', 'siswa']);
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->can('kelulusan.create') || $user->hasRole(['superadmin', 'admin']);
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Kelulusan $kelulusan): bool
    {
        return $user->can('kelulusan.edit') || $user->hasRole(['superadmin', 'admin']);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Kelulusan $kelulusan): bool
    {
        return $user->can('kelulusan.delete') || $user->hasRole(['superadmin', 'admin']);
    }

    /**
     * Determine whether the user can import data.
     */
    public function import(User $user): bool
    {
        return $user->can('kelulusan.import') || $user->hasRole(['superadmin', 'admin']);
    }

    /**
     * Determine whether the user can export data.
     */
    public function export(User $user): bool
    {
        return $user->can('kelulusan.export') || $user->hasRole(['superadmin', 'admin', 'guru']);
    }

    /**
     * Determine whether the user can generate certificate.
     */
    public function generateCertificate(User $user): bool
    {
        return $user->can('kelulusan.certificate') || $user->hasRole(['superadmin', 'admin']);
    }
}
