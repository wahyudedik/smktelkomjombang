<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Barang;
use Illuminate\Auth\Access\HandlesAuthorization;

class SarprasPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->can('sarpras.view') || $user->can('sarpras.read') || $user->hasRole(['superadmin', 'admin', 'sarpras']);
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Barang $barang): bool
    {
        return $user->can('sarpras.view') || $user->can('sarpras.read') || $user->hasRole(['superadmin', 'admin', 'sarpras']);
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->can('sarpras.create') || $user->hasRole(['superadmin', 'admin', 'sarpras']);
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Barang $barang): bool
    {
        return $user->can('sarpras.edit') || $user->can('sarpras.update') || $user->hasRole(['superadmin', 'admin', 'sarpras']);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Barang $barang): bool
    {
        return $user->can('sarpras.delete') || $user->hasRole(['superadmin', 'admin', 'sarpras']);
    }

    /**
     * Determine whether the user can import data.
     */
    public function import(User $user): bool
    {
        return $user->can('sarpras.import') || $user->hasRole(['superadmin', 'admin', 'sarpras']);
    }

    /**
     * Determine whether the user can export data.
     */
    public function export(User $user): bool
    {
        return $user->can('sarpras.export') || $user->hasRole(['superadmin', 'admin', 'sarpras']);
    }

    /**
     * Determine whether the user can generate barcode.
     */
    public function generateBarcode(User $user): bool
    {
        return $user->can('sarpras.barcode') || $user->hasRole(['superadmin', 'admin', 'sarpras']);
    }

    /**
     * Determine whether the user can scan barcode.
     */
    public function scanBarcode(User $user): bool
    {
        return $user->can('sarpras.barcode') || $user->hasRole(['superadmin', 'admin', 'sarpras']);
    }

    /**
     * Determine whether the user can print barcode.
     */
    public function printBarcode(User $user): bool
    {
        return $user->can('sarpras.barcode') || $user->hasRole(['superadmin', 'admin', 'sarpras']);
    }

    /**
     * Determine whether the user can manage maintenance.
     */
    public function manageMaintenance(User $user): bool
    {
        return $user->can('sarpras.maintenance') || $user->hasRole(['superadmin', 'admin', 'sarpras']);
    }

    /**
     * Determine whether the user can view maintenance.
     */
    public function viewMaintenance(User $user): bool
    {
        return $user->can('sarpras.view') || $user->can('sarpras.maintenance') || $user->hasRole(['superadmin', 'admin', 'sarpras']);
    }
}
