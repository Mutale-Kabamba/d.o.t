<?php

namespace App\Policies;

use App\Models\Project;
use App\Models\User;

class ProjectPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Project $project): bool
    {
        return $user->canAccessProject($project);
    }

    /**
     * Determine whether the user can create models.
     * Restricted to Super Admins & MEAL Officers.
     */
    public function create(User $user): bool
    {
        return $user->hasAdminAccess();
    }

    /**
     * Determine whether the user can update the model.
     * Restricted to Super Admins & MEAL Officers.
     */
    public function update(User $user, Project $project): bool
    {
        return $user->hasAdminAccess();
    }

    /**
     * Determine whether the user can delete the model.
     * Restricted to Super Admins & MEAL Officers.
     */
    public function delete(User $user, Project $project): bool
    {
        return $user->hasAdminAccess();
    }

    /**
     * Determine whether the user can assign team members to the project.
     */
    public function assignUsers(User $user, Project $project): bool
    {
        return $user->hasAdminAccess();
    }
}
