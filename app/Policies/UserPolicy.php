<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        // Superadmin dan admin bisa view all users
        if ($user->hasRole('superadmin') || $user->hasRole('admin')) {
            return true;
        }
        return $user->can('users.view');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, User $model): bool
    {
        return $user->can('users.view') || $user->id === $model->id;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        // Superadmin dan admin bisa create users
        if ($user->hasRole('superadmin') || $user->hasRole('admin')) {
            return true;
        }
        return $user->can('users.create');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, User $model): bool
    {
        return $user->can('users.edit') || $user->id === $model->id;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, User $model): bool
    {
        return $user->can('users.delete') && $user->id !== $model->id;
    }

    /**
     * Determine whether the user can bulk delete.
     */
    public function deleteAny(User $user): bool
    {
        return $user->can('users.delete');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, User $model): bool
    {
        return $user->can('users.delete') && $user->id !== $model->id;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, User $model): bool
    {
        return $user->can('users.edit');
    }

    /**
     * Determine whether the user can assign roles.
     */
    public function assignRole(User $user): bool
    {
        return $user->can('roles.edit');
    }

    /**
     * Determine whether the user can manage permissions.
     */
    public function managePermissions(User $user): bool
    {
        return $user->can('permissions.edit');
    }
}
