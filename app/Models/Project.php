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

    protected $casts = [
        'is_department' => 'boolean',
    ];

    /**
     * Parent department or umbrella project.
     */
    public function parent(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Project::class, 'parent_id');
    }

    /**
     * Sub-projects belonging to this department / umbrella initiative.
     */
    public function children(): HasMany
    {
        return $this->hasMany(Project::class, 'parent_id')->orderBy('name');
    }

    /**
     * Alias for children sub-projects.
     */
    public function subProjects(): HasMany
    {
        return $this->children();
    }

    /**
     * Check if this project acts as a Department or has sub-projects.
     */
    public function isDepartment(): bool
    {
        return (bool) ($this->is_department || $this->children()->exists());
    }

    /**
     * Check if this project is a sub-project under a department.
     */
    public function isSubProject(): bool
    {
        return !empty($this->parent_id);
    }

    /**
     * Get array of IDs including this project and all its sub-projects.
     */
    public function descendantProjectIds(): array
    {
        $ids = [$this->id];
        foreach ($this->children as $child) {
            $ids[] = $child->id;
        }
        return $ids;
    }

    /**
     * Full hierarchical display name.
     */
    public function getHierarchyNameAttribute(): string
    {
        if ($this->parent) {
            return "{$this->parent->name} ↳ {$this->name}";
        }
        return $this->isDepartment() ? "{$this->name} (Department)" : $this->name;
    }

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
     * Scope for departments / parent projects.
     */
    public function scopeDepartments(Builder $query): Builder
    {
        return $query->where('is_department', true)->orWhereNull('parent_id');
    }

    /**
     * Scope for subprojects.
     */
    public function scopeSubProjects(Builder $query): Builder
    {
        return $query->whereNotNull('parent_id');
    }

    /**
     * Scope projects accessible by a specific user.
     * Inherits access if user is assigned directly or to parent department.
     */
    public function scopeForUser(Builder $query, User $user): Builder
    {
        if ($user->hasAdminAccess()) {
            return $query;
        }

        return $query->where(function ($q) use ($user) {
            $q->whereHas('users', function ($uq) use ($user) {
                $uq->where('users.id', $user->id);
            })->orWhereHas('parent.users', function ($pq) use ($user) {
                $pq->where('users.id', $user->id);
            });
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
