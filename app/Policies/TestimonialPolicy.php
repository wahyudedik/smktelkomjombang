<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Testimonial;
use Illuminate\Auth\Access\HandlesAuthorization;

class TestimonialPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->can('testimonials.view') || $user->hasRole(['superadmin', 'admin']);
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Testimonial $testimonial): bool
    {
        return $user->can('testimonials.view') || $user->hasRole(['superadmin', 'admin']);
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->can('testimonials.create') || $user->hasRole(['superadmin', 'admin']);
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Testimonial $testimonial): bool
    {
        return $user->can('testimonials.edit') || $user->hasRole(['superadmin', 'admin']);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Testimonial $testimonial): bool
    {
        return $user->can('testimonials.delete') || $user->hasRole(['superadmin', 'admin']);
    }

    /**
     * Determine whether the user can approve testimonials.
     */
    public function approve(User $user, Testimonial $testimonial): bool
    {
        return $user->can('testimonials.edit') || $user->hasRole(['superadmin', 'admin']);
    }

    /**
     * Determine whether the user can reject testimonials.
     */
    public function reject(User $user, Testimonial $testimonial): bool
    {
        return $user->can('testimonials.edit') || $user->hasRole(['superadmin', 'admin']);
    }

    /**
     * Determine whether the user can toggle featured status.
     */
    public function toggleFeatured(User $user, Testimonial $testimonial): bool
    {
        return $user->can('testimonials.edit') || $user->hasRole(['superadmin', 'admin']);
    }
}
