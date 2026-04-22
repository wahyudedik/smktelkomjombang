<?php

namespace App\Policies;

use App\Models\User;
use App\Models\JadwalPelajaran;
use Illuminate\Auth\Access\HandlesAuthorization;

class JadwalPelajaranPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->can('jadwal.view') || $user->can('jadwal.read') || $user->hasRole(['superadmin', 'admin', 'guru', 'siswa']);
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, JadwalPelajaran $jadwalPelajaran): bool
    {
        return $user->can('jadwal.view') || $user->can('jadwal.read') || $user->hasRole(['superadmin', 'admin', 'guru', 'siswa']);
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->can('jadwal.create') || $user->hasRole(['superadmin', 'admin']);
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, JadwalPelajaran $jadwalPelajaran): bool
    {
        return $user->can('jadwal.edit') || $user->hasRole(['superadmin', 'admin']);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, JadwalPelajaran $jadwalPelajaran): bool
    {
        return $user->can('jadwal.delete') || $user->hasRole(['superadmin', 'admin']);
    }

    /**
     * Determine whether the user can import data.
     */
    public function import(User $user): bool
    {
        return $user->can('jadwal.import') || $user->hasRole(['superadmin', 'admin']);
    }

    /**
     * Determine whether the user can export data.
     */
    public function export(User $user): bool
    {
        return $user->can('jadwal.export') || $user->hasRole(['superadmin', 'admin']);
    }
}
