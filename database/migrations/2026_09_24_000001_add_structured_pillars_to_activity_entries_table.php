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
            if (!Schema::hasColumn('activity_entries', 'period_granularity')) {
                $table->string('period_granularity')->default('quarter')->after('reporting_period');
            }

            // Pillar 1: Project Achievements (JSON {milestones: [], impact: [], stories: []} + Narrative)
            if (!Schema::hasColumn('activity_entries', 'pillar_1_achievements')) {
                $table->json('pillar_1_achievements')->nullable()->after('achievements_stories');
            }
            if (!Schema::hasColumn('activity_entries', 'pillar_1_narrative')) {
                $table->longText('pillar_1_narrative')->nullable()->after('pillar_1_achievements');
            }

            // Pillar 2: Challenges & Risks (JSON {operational: [], resources: [], risks: []} + Narrative)
            if (!Schema::hasColumn('activity_entries', 'pillar_2_challenges')) {
                $table->json('pillar_2_challenges')->nullable()->after('challenges_risks');
            }
            if (!Schema::hasColumn('activity_entries', 'pillar_2_narrative')) {
                $table->longText('pillar_2_narrative')->nullable()->after('pillar_2_challenges');
            }

            // Pillar 3: Learning & Adaptation (JSON {lessons: [], feedback: [], innovation: []} + Narrative)
            if (!Schema::hasColumn('activity_entries', 'pillar_3_learning')) {
                $table->json('pillar_3_learning')->nullable()->after('learning_innovation');
            }
            if (!Schema::hasColumn('activity_entries', 'pillar_3_narrative')) {
                $table->longText('pillar_3_narrative')->nullable()->after('pillar_3_learning');
            }

            // Pillar 4: Monitoring & Evaluation (JSON {performance: [], data_quality: [], evaluation: []} + Narrative)
            if (!Schema::hasColumn('activity_entries', 'pillar_4_monitoring')) {
                $table->json('pillar_4_monitoring')->nullable()->after('mne_evaluation_plans');
            }
            if (!Schema::hasColumn('activity_entries', 'pillar_4_narrative')) {
                $table->longText('pillar_4_narrative')->nullable()->after('pillar_4_monitoring');
            }

            // Pillar 5: Collaboration & Coordination (JSON {project_collab: [], partnerships: [], cross_learning: []} + Narrative)
            if (!Schema::hasColumn('activity_entries', 'pillar_5_collaboration')) {
                $table->json('pillar_5_collaboration')->nullable()->after('collab_cross_learning');
            }
            if (!Schema::hasColumn('activity_entries', 'pillar_5_narrative')) {
                $table->longText('pillar_5_narrative')->nullable()->after('pillar_5_collaboration');
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
                'period_granularity',
                'pillar_1_achievements', 'pillar_1_narrative',
                'pillar_2_challenges', 'pillar_2_narrative',
                'pillar_3_learning', 'pillar_3_narrative',
                'pillar_4_monitoring', 'pillar_4_narrative',
                'pillar_5_collaboration', 'pillar_5_narrative',
            ];
            foreach ($columns as $column) {
                if (Schema::hasColumn('activity_entries', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
