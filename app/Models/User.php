<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    public const ROLE_SUPER_ADMIN = 'super_admin';
    public const ROLE_PROJECT_OFFICER = 'project_officer';
    public const ROLE_PROJECT_ASSISTANT = 'project_assistant';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Projects assigned to this user.
     */
    public function projects(): BelongsToMany
    {
        return $this->belongsToMany(Project::class, 'project_user')->withTimestamps();
    }

    /**
     * Activity entries created by this user.
     */
    public function activityEntries(): HasMany
    {
        return $this->hasMany(ActivityEntry::class);
    }

    /**
     * Check if user is Super Admin.
     */
    public function isSuperAdmin(): bool
    {
        return $this->role === self::ROLE_SUPER_ADMIN;
    }

    /**
     * Check if user is Project Officer.
     */
    public function isProjectOfficer(): bool
    {
        return $this->role === self::ROLE_PROJECT_OFFICER;
    }

    /**
     * Check if user is Project Assistant.
     */
    public function isProjectAssistant(): bool
    {
        return $this->role === self::ROLE_PROJECT_ASSISTANT;
    }

    /**
     * Check if user can access a specific project.
     * Super Admins can access all projects; others only access assigned projects.
     */
    public function canAccessProject($project): bool
    {
        if ($this->isSuperAdmin()) {
            return true;
        }

        $projectId = is_object($project) ? $project->id : (int) $project;
        return $this->projects()->where('projects.id', $projectId)->exists();
    }

    /**
     * Scope query to search by name or email.
     */
    public function scopeSearch(Builder $query, ?string $term): Builder
    {
        if (empty($term)) {
            return $query;
        }

        return $query->where(function ($q) use ($term) {
            $q->where('name', 'like', "%{$term}%")
              ->orWhere('email', 'like', "%{$term}%");
        });
    }

    /**
     * Scope query to filter by role.
     */
    public function scopeRole(Builder $query, ?string $role): Builder
    {
        if (empty($role) || $role === 'all') {
            return $query;
        }

        return $query->where('role', $role);
    }

    /**
     * Get human-readable role label.
     */
    public function getRoleLabelAttribute(): string
    {
        return match ($this->role) {
            self::ROLE_SUPER_ADMIN => 'Super Admin',
            self::ROLE_PROJECT_ASSISTANT => 'Project Assistant',
            default => 'Project Officer',
        };
    }
}
