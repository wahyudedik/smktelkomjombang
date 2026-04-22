<?php

namespace App\Policies;

use App\Models\User;
use App\Models\TestimonialLink;
use Illuminate\Auth\Access\HandlesAuthorization;

class TestimonialLinkPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->can('testimonial-links.view') || $user->hasRole(['superadmin', 'admin']);
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, TestimonialLink $testimonialLink): bool
    {
        return $user->can('testimonial-links.view') || $user->hasRole(['superadmin', 'admin']);
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->can('testimonial-links.create') || $user->hasRole(['superadmin', 'admin']);
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, TestimonialLink $testimonialLink): bool
    {
        return $user->can('testimonial-links.edit') || $user->hasRole(['superadmin', 'admin']);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, TestimonialLink $testimonialLink): bool
    {
        return $user->can('testimonial-links.delete') || $user->hasRole(['superadmin', 'admin']);
    }

    /**
     * Determine whether the user can toggle active status.
     */
    public function toggleActive(User $user, TestimonialLink $testimonialLink): bool
    {
        return $user->can('testimonial-links.edit') || $user->hasRole(['superadmin', 'admin']);
    }

    /**
     * Determine whether the user can access public testimonial submission.
     */
    public function submitTestimonial(User $user = null, TestimonialLink $testimonialLink): bool
    {
        // Public access - no authentication required
        return $testimonialLink->isCurrentlyActive();
    }
}
