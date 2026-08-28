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
        Schema::create('anonymous_submissions', function (Blueprint $table) {
            $table->id();
            $table->uuid('token')->unique();
            
            // Section 1: Journey Mapping
            $table->text('s1_recruitment_high')->nullable();
            $table->text('s1_recruitment_challenges')->nullable();
            $table->text('s1_grad_high')->nullable();
            $table->text('s1_grad_challenges')->nullable();
            $table->text('s1_bds_high')->nullable();
            $table->text('s1_bds_challenges')->nullable();
            
            // Section 2: Operations & Placements
            $table->text('s2_ops_comm')->nullable();
            $table->text('s2_host_criteria')->nullable();
            $table->text('s2_reporting_fixes')->nullable();
            
            // Section 3: PACRA & BDS
            $table->text('s3_pacra_strategy')->nullable();
            $table->text('s3_market_access')->nullable();
            
            // Section 4: Community & Safeguarding
            $table->text('s4_household_buyin')->nullable();
            $table->string('s4_safeguarding_accessible')->nullable();
            $table->text('s4_safeguarding_details')->nullable();
            
            // Section 5: Recruitment Strategy & Finance
            $table->text('s5_recruitment_walkthroughs')->nullable();
            $table->text('s5_finance_stipends')->nullable();
            
            // Section 6: Youth Leader Transition
            $table->text('s6_yl_transition')->nullable();
            
            // Section 7: Mindset Impact
            $table->text('s7_skills_gained')->nullable();
            $table->text('s7_mindset_shift')->nullable();
            $table->text('s7_action_taken')->nullable();
            
            // Section 8: Summary Recommendations
            $table->text('s8_top_worked')->nullable();
            $table->text('s8_top_barriers')->nullable();
            $table->text('s8_change_one_thing')->nullable();
            $table->string('s8_one_word')->nullable();
            $table->text('s8_final_message')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('anonymous_submissions');
    }
};
