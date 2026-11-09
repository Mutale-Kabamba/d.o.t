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
        Schema::table('activity_entries', function (Blueprint $table) {
            // 1. Project Achievements (3 respective sections)
            if (!Schema::hasColumn('activity_entries', 'achievements_milestones')) {
                $table->text('achievements_milestones')->nullable()->after('reporting_period');
            }
            if (!Schema::hasColumn('activity_entries', 'achievements_impact')) {
                $table->text('achievements_impact')->nullable()->after('achievements_milestones');
            }
            if (!Schema::hasColumn('activity_entries', 'achievements_stories')) {
                $table->text('achievements_stories')->nullable()->after('achievements_impact');
            }

            // 2. Challenges & Risks (3 respective sections)
            if (!Schema::hasColumn('activity_entries', 'challenges_operational')) {
                $table->text('challenges_operational')->nullable()->after('achievements_narrative');
            }
            if (!Schema::hasColumn('activity_entries', 'challenges_resources')) {
                $table->text('challenges_resources')->nullable()->after('challenges_operational');
            }
            if (!Schema::hasColumn('activity_entries', 'challenges_risks')) {
                $table->text('challenges_risks')->nullable()->after('challenges_resources');
            }

            // 3. Learning & Adaptation (3 respective sections)
            if (!Schema::hasColumn('activity_entries', 'learning_lessons')) {
                $table->text('learning_lessons')->nullable()->after('challenges_narrative');
            }
            if (!Schema::hasColumn('activity_entries', 'learning_feedback')) {
                $table->text('learning_feedback')->nullable()->after('learning_lessons');
            }
            if (!Schema::hasColumn('activity_entries', 'learning_innovation')) {
                $table->text('learning_innovation')->nullable()->after('learning_feedback');
            }

            // 4. Monitoring & Evaluation (3 respective sections)
            if (!Schema::hasColumn('activity_entries', 'mne_performance')) {
                $table->text('mne_performance')->nullable()->after('learning_narrative');
            }
            if (!Schema::hasColumn('activity_entries', 'mne_data_quality')) {
                $table->text('mne_data_quality')->nullable()->after('mne_performance');
            }
            if (!Schema::hasColumn('activity_entries', 'mne_evaluation_plans')) {
                $table->text('mne_evaluation_plans')->nullable()->after('mne_data_quality');
            }

            // 5. Collaboration & Coordination (3 respective sections)
            if (!Schema::hasColumn('activity_entries', 'collab_projects')) {
                $table->text('collab_projects')->nullable()->after('mne_narrative');
            }
            if (!Schema::hasColumn('activity_entries', 'collab_partnerships')) {
                $table->text('collab_partnerships')->nullable()->after('collab_projects');
            }
            if (!Schema::hasColumn('activity_entries', 'collab_cross_learning')) {
                $table->text('collab_cross_learning')->nullable()->after('collab_partnerships');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('activity_entries', function (Blueprint $table) {
            $columns = [
                'achievements_milestones', 'achievements_impact', 'achievements_stories',
                'challenges_operational', 'challenges_resources', 'challenges_risks',
                'learning_lessons', 'learning_feedback', 'learning_innovation',
                'mne_performance', 'mne_data_quality', 'mne_evaluation_plans',
                'collab_projects', 'collab_partnerships', 'collab_cross_learning'
            ];
            foreach ($columns as $column) {
                if (Schema::hasColumn('activity_entries', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
