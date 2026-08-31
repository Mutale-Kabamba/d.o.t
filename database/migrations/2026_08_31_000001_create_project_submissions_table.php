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
        Schema::create('project_submissions', function (Blueprint $table) {
            $table->id();
            $table->uuid('token')->unique();

            // Project Metadata
            $table->string('project_name');
            $table->string('officer_name');
            $table->string('location')->nullable();
            $table->string('reporting_period')->default('Quarter 2 April, May, June 2026');

            // Slide 1: Project Achievements
            $table->text('achievements_milestones')->nullable();
            $table->text('achievements_impact')->nullable();
            $table->text('achievements_stories')->nullable();

            // Slide 2: Challenges & Risks
            $table->text('challenges_operational')->nullable();
            $table->text('challenges_resources')->nullable();
            $table->text('challenges_risks')->nullable();

            // Slide 3: Learning and Adaptation
            $table->text('learning_lessons')->nullable();
            $table->text('learning_feedback')->nullable();
            $table->text('learning_innovation')->nullable();

            // Slide 4: Monitoring & Evaluation
            $table->text('mne_performance')->nullable();
            $table->text('mne_data_quality')->nullable();
            $table->text('mne_evaluation_plans')->nullable();

            // Slide 5: Collaboration and Coordination
            $table->text('collab_projects')->nullable();
            $table->text('collab_partnerships')->nullable();
            $table->text('collab_cross_learning')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('project_submissions');
    }
};
