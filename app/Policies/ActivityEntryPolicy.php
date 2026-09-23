<?php

namespace App\Policies;

use App\Models\ActivityEntry;
use App\Models\Project;
use App\Models\User;

class ActivityEntryPolicy
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
    public function view(User $user, ActivityEntry $activityEntry): bool
    {
        return $user->canAccessProject($activityEntry->project_id);
    }

    /**
     * Determine whether the user can create models for a project.
     */
    public function create(User $user, ?Project $project = null): bool
    {
        if ($project) {
            return $user->canAccessProject($project);
        }

        return $user->isSuperAdmin() || $user->projects()->exists();
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, ActivityEntry $activityEntry): bool
    {
        return $user->canAccessProject($activityEntry->project_id);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, ActivityEntry $activityEntry): bool
    {
        return $user->canAccessProject($activityEntry->project_id);
    }
}
