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
     * Total activities logged across this project and all its child sub-projects.
     */
    public function totalDescendantActivitiesCount(): int
    {
        return ActivityEntry::whereIn('project_id', $this->descendantProjectIds())->count();
    }

    /**
     * Check if project is a child sub-project.
     */
    public function isChild(): bool
    {
        return !empty($this->parent_id) && !$this->is_department;
    }

    /**
     * Check if project is standalone (not a department and has no parent).
     */
    public function isStandalone(): bool
    {
        return empty($this->parent_id) && !$this->is_department;
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
     * Scope for operational projects only (excluding parent departments).
     */
    public function scopeOnlyProjects(Builder $query): Builder
    {
        return $query->where('is_department', false);
    }

    /**
     * Scope for parent departments only.
     */
    public function scopeDepartments(Builder $query): Builder
    {
        return $query->where('is_department', true);
    }

    /**
     * Check if this child project is linked with siblings for combined presentation.
     */
    public function isLinked(): bool
    {
        return !empty($this->link_group) && !empty($this->parent_id);
    }

    /**
     * Sibling child projects linked with this project under the same parent.
     */
    public function linkedSiblingProjects(): \Illuminate\Support\Collection
    {
        if (!$this->isLinked()) {
            return collect([$this]);
        }

        return Project::where('parent_id', $this->parent_id)
            ->where('link_group', $this->link_group)
            ->orderBy('name')
            ->get();
    }

    /**
     * Get combined presentation slide title, e.g. "Education (Literacy & After Class)".
     */
    public function getCombinedPresentationTitleAttribute(): string
    {
        if (!$this->isLinked() || !$this->parent) {
            return $this->parent ? "{$this->parent->name} ↳ {$this->name}" : $this->name;
        }

        $siblings = $this->linkedSiblingProjects();
        $names = $siblings->pluck('name')->all();
        if (count($names) <= 1) {
            return "{$this->parent->name} ↳ {$this->name}";
        }

        $last = array_pop($names);
        $formattedNames = implode(', ', $names) . ' & ' . $last;
        return "{$this->parent->name} ({$formattedNames})";
    }

    /**
     * Helper to format bullet points for linked child projects, e.g.:
     * "- Sessions (Literacy): 20 sessions done"
     */
    public static function formatLinkedBullet(string $pt, ?string $actTitle, string $projectName): string
    {
        $pt = trim($pt);
        $projectName = trim($projectName);

        if (empty($pt)) {
            return '';
        }

        // Clean any leading bullet characters with UTF-8 support
        $cleanPt = trim(preg_replace('/^[\s\-\*\•\–\—\>]+/u', '', $pt));

        // If point already mentions project name in parentheses, return it
        if (str_contains($cleanPt, "({$projectName})")) {
            return $cleanPt;
        }

        // If bullet already has a category/metric prefix before colon, e.g. "Sessions: 20 sessions done"
        if (preg_match('/^([^:]+):(.*)$/s', $cleanPt, $matches)) {
            $prefix = trim($matches[1]);
            $rest = trim($matches[2]);
            return "{$prefix} ({$projectName}): {$rest}";
        }

        // Use actTitle prefix if available and not redundant
        $baseText = !empty($actTitle) && !str_starts_with(strtolower($cleanPt), strtolower($actTitle))
            ? "{$actTitle}: {$cleanPt}"
            : $cleanPt;

        if (preg_match('/^([^:]+):(.*)$/s', $baseText, $matches)) {
            $prefix = trim($matches[1]);
            $rest = trim($matches[2]);
            return "{$prefix} ({$projectName}): {$rest}";
        }

        // Otherwise append: "20 sessions done (Literacy)"
        return "{$baseText} ({$projectName})";
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
