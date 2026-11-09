<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Add role column to users table if not already present
        if (Schema::hasTable('users') && !Schema::hasColumn('users', 'role')) {
            Schema::table('users', function (Blueprint $table) {
                $table->string('role')->default('project_officer')->after('email');
            });
        }

        // Create projects table
        if (!Schema::hasTable('projects')) {
            Schema::create('projects', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->string('code')->nullable();
                $table->text('description')->nullable();
                $table->string('location')->nullable();
                $table->string('status')->default('active'); // active, archived
                $table->timestamps();
            });
        }

        // Create project_user pivot table for assignment
        if (!Schema::hasTable('project_user')) {
            Schema::create('project_user', function (Blueprint $table) {
                $table->id();
                $table->foreignId('project_id')->constrained()->cascadeOnDelete();
                $table->foreignId('user_id')->constrained()->cascadeOnDelete();
                $table->timestamps();
                $table->unique(['project_id', 'user_id']);
            });
        }

        // Create continuous activity entries table with dual fields for 5 thematic pillars
        if (!Schema::hasTable('activity_entries')) {
            Schema::create('activity_entries', function (Blueprint $table) {
                $table->id();
                $table->uuid('token')->unique();
                $table->foreignId('project_id')->constrained()->cascadeOnDelete();
                $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
                $table->string('activity_title');
                $table->date('activity_date');
                $table->string('location')->nullable();
                $table->string('reporting_period')->nullable();

                // 1. Project Achievements (Presentation Points + Detailed Narrative)
                $table->text('achievements_points')->nullable();
                $table->text('achievements_narrative')->nullable();

                // 2. Challenges & Risks (Presentation Points + Detailed Narrative)
                $table->text('challenges_points')->nullable();
                $table->text('challenges_narrative')->nullable();

                // 3. Learning & Adaptation (Presentation Points + Detailed Narrative)
                $table->text('learning_points')->nullable();
                $table->text('learning_narrative')->nullable();

                // 4. Monitoring & Evaluation (Presentation Points + Detailed Narrative)
                $table->text('mne_points')->nullable();
                $table->text('mne_narrative')->nullable();

                // 5. Collaboration & Coordination (Presentation Points + Detailed Narrative)
                $table->text('collab_points')->nullable();
                $table->text('collab_narrative')->nullable();

                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('activity_entries');
        Schema::dropIfExists('project_user');
        Schema::dropIfExists('projects');

        if (Schema::hasTable('users') && Schema::hasColumn('users', 'role')) {
            Schema::table('users', function (Blueprint $table) {
                $table->dropColumn('role');
            });
        }
    }
};
