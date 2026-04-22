<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Page;
use Illuminate\Auth\Access\HandlesAuthorization;

class PagePolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->can('pages.view') || $user->hasRole(['superadmin', 'admin']);
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Page $page): bool
    {
        // Public can view published pages
        if ($page->status === 'published') {
            return true;
        }

        // Admin can view all pages
        return $user->can('pages.view') || $user->hasRole(['superadmin', 'admin']);
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->can('pages.create') || $user->hasRole(['superadmin', 'admin']);
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Page $page): bool
    {
        return $user->can('pages.edit') || $user->hasRole(['superadmin', 'admin']);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Page $page): bool
    {
        return $user->can('pages.delete') || $user->hasRole(['superadmin', 'admin']);
    }

    /**
     * Determine whether the user can publish the page.
     */
    public function publish(User $user, Page $page): bool
    {
        return $user->can('pages.publish') || $user->hasRole(['superadmin', 'admin']);
    }

    /**
     * Determine whether the user can unpublish the page.
     */
    public function unpublish(User $user, Page $page): bool
    {
        return $user->can('pages.unpublish') || $user->hasRole(['superadmin', 'admin']);
    }

    /**
     * Determine whether the user can duplicate the page.
     */
    public function duplicate(User $user, Page $page): bool
    {
        return $user->can('pages.create') || $user->hasRole(['superadmin', 'admin']);
    }

    /**
     * Determine whether the user can manage versions.
     */
    public function manageVersions(User $user, Page $page): bool
    {
        return $user->can('pages.edit') || $user->hasRole(['superadmin', 'admin']);
    }
}
