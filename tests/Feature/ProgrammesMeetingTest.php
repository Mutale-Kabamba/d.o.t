<?php

namespace Tests\Feature;

use App\Models\ProjectSubmission;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProgrammesMeetingTest extends TestCase
{
    use RefreshDatabase;

    public function test_project_brief_page_loads_successfully(): void
    {
        $response = $this->get(route('programmes.index'));

        $response->assertStatus(200);
        $response->assertSee('Play It Forward Zambia');
        $response->assertSee('Project Achievements');
        $response->assertSee('Challenges & Risks');
        $response->assertSee('Learning and Adaptation');
        $response->assertSee('Monitoring & Evaluation');
        $response->assertSee('Collaboration and Coordination');
    }

    public function test_project_brief_can_be_submitted_via_ajax(): void
    {
        $payload = [
            'project_name' => 'Football for Health & Life Skills',
            'officer_name' => 'Mwila Tembo',
            'location' => 'Livingstone Urban',
            'reporting_period' => 'Quarter 2 April, May, June 2026',
            'achievements_milestones' => '• Reached 1,200 participants\n• Hosted tournament',
            'achievements_impact' => '• 84% improved life skills',
            'achievements_stories' => '• Chileshe became a coach',
            'challenges_operational' => '• Rainy season pitch issues',
            'challenges_resources' => '• Ball shortage',
            'challenges_risks' => '• Heat monitoring',
            'learning_lessons' => '• School alignment worked well',
            'learning_feedback' => '• Parents requested weekend events',
            'learning_innovation' => '• Digital attendance app',
            'mne_performance' => '• 95% of target',
            'mne_data_quality' => '• Bi-weekly checks',
            'mne_evaluation_plans' => '• Endline survey in Q3',
            'collab_projects' => '• Shared equipment with YL',
            'collab_partnerships' => '• Partnered with Health Office',
            'collab_cross_learning' => '• Shared drill guide',
        ];

        $response = $this->postJson(route('programmes.store'), $payload);

        $response->assertStatus(200);
        $response->assertJson(['success' => true]);

        $this->assertDatabaseHas('project_submissions', [
            'project_name' => 'Football for Health & Life Skills',
            'officer_name' => 'Mwila Tembo',
        ]);
    }

    public function test_success_page_renders_with_valid_token(): void
    {
        $submission = ProjectSubmission::create([
            'project_name' => 'Girls Empowerment & Mentorship',
            'officer_name' => 'Faith Musonda',
            'reporting_period' => 'Quarter 2 April, May, June 2026',
        ]);

        $response = $this->get(route('programmes.success', ['token' => $submission->token]));

        $response->assertStatus(200);
        $response->assertSee('Girls Empowerment & Mentorship');
        $response->assertSee('Faith Musonda');
    }

    public function test_supervisor_hub_loads_and_displays_submissions(): void
    {
        ProjectSubmission::create([
            'project_name' => 'Youth Leadership Incubator',
            'officer_name' => 'Kelvin Phiri',
            'location' => 'Zambezi Hub',
            'reporting_period' => 'Quarter 2 April, May, June 2026',
            'achievements_milestones' => '• 65 youth graduated',
        ]);

        $response = $this->get(route('programmes.hub'));

        $response->assertStatus(200);
        $response->assertSee('Youth Leadership Incubator');
        $response->assertSee('Kelvin Phiri');
    }

    public function test_single_project_presentation_pdf_export(): void
    {
        $submission = ProjectSubmission::create([
            'project_name' => 'Community Coaching Academy',
            'officer_name' => 'Chanda Bwalya',
            'reporting_period' => 'Quarter 2 April, May, June 2026',
            'achievements_milestones' => '• Trained 30 grassroot coaches',
        ]);

        $response = $this->get(route('programmes.download_single_pdf', ['token' => $submission->token]));

        $response->assertStatus(200);
        $response->assertHeader('content-type', 'application/pdf');
    }

    public function test_consolidated_powerpoint_pdf_export(): void
    {
        ProjectSubmission::create([
            'project_name' => 'Project Alpha',
            'officer_name' => 'Officer 1',
            'reporting_period' => 'Quarter 2 April, May, June 2026',
            'achievements_milestones' => '• Alpha milestone',
        ]);

        ProjectSubmission::create([
            'project_name' => 'Project Beta',
            'officer_name' => 'Officer 2',
            'reporting_period' => 'Quarter 2 April, May, June 2026',
            'achievements_milestones' => '• Beta milestone',
        ]);

        $response = $this->get(route('programmes.export_consolidated_pdf'));

        $response->assertStatus(200);
        $response->assertHeader('content-type', 'application/pdf');
    }

    public function test_sample_seeding_action(): void
    {
        $response = $this->post(route('programmes.seed'));

        $response->assertRedirect(route('programmes.hub'));
        $this->assertGreaterThan(0, ProjectSubmission::count());
    }

    public function test_consolidated_powerpoint_pptx_export(): void
    {
        ProjectSubmission::create([
            'project_name' => 'Project Alpha',
            'officer_name' => 'Officer 1',
            'reporting_period' => 'Quarter 2 April, May, June 2026',
            'achievements_milestones' => '• Alpha milestone',
        ]);

        $response = $this->get(route('programmes.export_consolidated_pptx'));

        $response->assertStatus(200);
        $response->assertHeader('content-type', 'application/vnd.openxmlformats-officedocument.presentationml.presentation');
    }

    public function test_live_projector_mode_loads(): void
    {
        ProjectSubmission::create([
            'project_name' => 'Project Alpha',
            'officer_name' => 'Officer 1',
            'reporting_period' => 'Quarter 2 April, May, June 2026',
            'achievements_milestones' => '• Alpha milestone',
        ]);

        $response = $this->get(route('programmes.projector'));

        $response->assertStatus(200);
        $response->assertSee('Play It Forward Zambia');
        $response->assertSee('Programmes Meeting');
    }
}
