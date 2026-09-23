<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Project extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    /**
     * Users assigned to this project.
     */
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'project_user')->withTimestamps();
    }

    /**
     * Project Officers assigned to this project.
     */
    public function officers(): BelongsToMany
    {
        return $this->users()->where('users.role', User::ROLE_PROJECT_OFFICER);
    }

    /**
     * Project Assistants assigned to this project.
     */
    public function assistants(): BelongsToMany
    {
        return $this->users()->where('users.role', User::ROLE_PROJECT_ASSISTANT);
    }

    /**
     * Continuous activity entries logged for this project.
     */
    public function activityEntries(): HasMany
    {
        return $this->hasMany(ActivityEntry::class)->latest('activity_date');
    }

    /**
     * Scope for active projects.
     */
    public function scopeActive(Builder $query): Builder
    {
        return $query->where('status', 'active');
    }

    /**
     * Scope for archived projects.
     */
    public function scopeArchived(Builder $query): Builder
    {
        return $query->where('status', 'archived');
    }

    /**
     * Scope projects accessible by a specific user.
     */
    public function scopeForUser(Builder $query, User $user): Builder
    {
        if ($user->isSuperAdmin()) {
            return $query;
        }

        return $query->whereHas('users', function ($q) use ($user) {
            $q->where('users.id', $user->id);
        });
    }

    /**
     * Helper to get names of assigned officers/assistants.
     */
    public function getLeadOfficerNameAttribute(): string
    {
        $officer = $this->officers()->first() ?? $this->users()->first();
        return $officer ? $officer->name : 'Unassigned Officer';
    }

    /**
     * Helper to get assigned user summary list.
     */
    public function getAssignedTeamSummaryAttribute(): string
    {
        $names = $this->users->pluck('name')->all();
        return !empty($names) ? implode(', ', $names) : 'No staff assigned';
    }
}
