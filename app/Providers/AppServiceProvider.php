<?php

namespace App\Providers;

use App\Models\ActivityEntry;
use App\Models\Project;
use App\Policies\ActivityEntryPolicy;
use App\Policies\ProjectPolicy;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Gate::policy(Project::class, ProjectPolicy::class);
        Gate::policy(ActivityEntry::class, ActivityEntryPolicy::class);

        if (config('database.default') === 'sqlite') {
            $dbPath = config('database.connections.sqlite.database');
            if ($dbPath && $dbPath !== ':memory:' && !file_exists($dbPath)) {
                $dbDir = dirname($dbPath);
                if (!is_dir($dbDir)) {
                    @mkdir($dbDir, 0755, true);
                }
                @touch($dbPath);
            }
        }
    }
}
