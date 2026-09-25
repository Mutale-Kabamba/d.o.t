<?php

namespace Tests\Feature;

use App\Models\ActivityEntry;
use App\Models\Project;
use App\Models\ProjectSubmission;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProgrammesMeetingTest extends TestCase
{
    use RefreshDatabase;

    public function test_unauthenticated_root_redirects_to_login(): void
    {
        $response = $this->get('/');

        $response->assertRedirect(route('login'));
    }

    public function test_authenticated_root_redirects_to_hub(): void
    {
        $user = User::factory()->create(['role' => User::ROLE_SUPER_ADMIN]);

        $response = $this->actingAs($user)->get('/');

        $response->assertRedirect(route('programmes.hub'));
    }

    /**
     * Test urgent bug fix: Leading numbers, percentages, and metrics must NEVER be stripped.
     */
    public function test_metrics_and_percentages_are_preserved_verbatim_in_bullet_parsing(): void
    {
        $text = "• 98% of girls passed\n100+ participants attended\n1 in 4 youth coaches certified\n• 24 youth placed into apprenticeships\n1. Standard ordered point\n• https://docs.google.com/document/d/123";

        $points = ActivityEntry::extractBulletPoints($text);

        $this->assertEquals('98% of girls passed', $points[0]);
        $this->assertEquals('100+ participants attended', $points[1]);
        $this->assertEquals('1 in 4 youth coaches certified', $points[2]);
        $this->assertEquals('24 youth placed into apprenticeships', $points[3]);
        $this->assertEquals('Standard ordered point', $points[4]);

        // HTML formatting tests
        $html1 = ActivityEntry::formatPointHtml('• 98% of girls passed');
        $this->assertStringContainsString('98% of girls passed', $html1);
        $this->assertNotEqualsIgnoringCase('of girls passed', $html1);

        $html2 = ActivityEntry::formatPointHtml('100+ participants attended');
        $this->assertStringContainsString('100+ participants attended', $html2);

        $html3 = ActivityEntry::formatPointHtml('1 in 4 youth coaches certified');
        $this->assertStringContainsString('1 in 4 youth coaches certified', $html3);

        // Plain text formatting tests
        $plain = ActivityEntry::formatPointText('• 98% of girls passed');
        $this->assertEquals('98% of girls passed', $plain);

        // ProjectSubmission model tests
        $legacySub = new ProjectSubmission([
            'achievements_milestones' => "• 98% of girls passed\n100+ youth reached\n1 in 4 coaches",
        ]);
        $legacyPts = $legacySub->getPoints('achievements_milestones');
        $this->assertEquals('98% of girls passed', $legacyPts[0]);
        $this->assertEquals('100+ youth reached', $legacyPts[1]);
        $this->assertEquals('1 in 4 coaches', $legacyPts[2]);
    }

    /**
     * Test User Roles, Project Scoping, and Super Admin Controls.
     */
    public function test_roles_and_project_scoping_authorization(): void
    {
        $superAdmin = User::factory()->create(['role' => User::ROLE_SUPER_ADMIN]);
        $officer = User::factory()->create(['role' => User::ROLE_PROJECT_OFFICER]);
        $otherOfficer = User::factory()->create(['role' => User::ROLE_PROJECT_OFFICER]);

        $projectA = Project::create(['name' => 'Project Alpha', 'status' => 'active']);
        $projectB = Project::create(['name' => 'Project Beta', 'status' => 'active']);

        // Assign officer to Project A only
        $projectA->users()->attach($officer->id);

        $this->assertTrue($superAdmin->canAccessProject($projectA));
        $this->assertTrue($superAdmin->canAccessProject($projectB));
        $this->assertTrue($officer->canAccessProject($projectA));
        $this->assertFalse($officer->canAccessProject($projectB));

        // Super Admin can create projects
        $response = $this->actingAs($superAdmin)->post(route('programmes.projects.store'), [
            'name' => 'New Super Project',
            'code' => 'NSP',
            'user_ids' => [$officer->id],
        ]);
        $response->assertRedirect(route('programmes.hub'));
        $this->assertDatabaseHas('projects', ['name' => 'New Super Project']);

        // Project Officer CANNOT create projects (403)
        $responseOfficer = $this->actingAs($officer)->post(route('programmes.projects.store'), [
            'name' => 'Unauthorized Project',
        ]);
        $responseOfficer->assertStatus(403);

        // Officer can log activity for assigned Project A
        $actResponse = $this->actingAs($officer)->post(route('programmes.activities.store'), [
            'project_id' => $projectA->id,
            'activity_title' => 'Weekly Coaching',
            'activity_date' => now()->toDateString(),
            'achievements_points' => '• 98% passed',
            'achievements_narrative' => 'Detailed explanation narrative',
        ]);
        $actResponse->assertRedirect(route('programmes.hub'));
        $this->assertDatabaseHas('activity_entries', ['activity_title' => 'Weekly Coaching']);

        // Officer CANNOT log activity for unassigned Project B (403)
        $unauthActResponse = $this->actingAs($officer)->post(route('programmes.activities.store'), [
            'project_id' => $projectB->id,
            'activity_title' => 'Unauthorized Activity',
            'activity_date' => now()->toDateString(),
        ]);
        $unauthActResponse->assertStatus(403);
    }

    /**
     * Test Super Admin User Account Management.
     */
    public function test_super_admin_can_create_and_manage_user_accounts(): void
    {
        $superAdmin = User::factory()->create(['role' => User::ROLE_SUPER_ADMIN]);
        $project = Project::create(['name' => 'Livingstone Health', 'status' => 'active']);

        $res = $this->actingAs($superAdmin)->post(route('programmes.users.store'), [
            'name' => 'Chanda Bwalya',
            'email' => 'chanda@pifzambia.org',
            'password' => 'secret123',
            'role' => User::ROLE_PROJECT_OFFICER,
            'project_ids' => [$project->id],
        ]);

        $res->assertRedirect(route('programmes.hub'));
        $this->assertDatabaseHas('users', [
            'email' => 'chanda@pifzambia.org',
            'role' => User::ROLE_PROJECT_OFFICER,
        ]);

        $createdUser = User::where('email', 'chanda@pifzambia.org')->first();
        $this->assertTrue($createdUser->canAccessProject($project));
    }

    /**
     * Test Continuous Activity Logging CRUD.
     */
    public function test_continuous_activity_logging_crud(): void
    {
        $user = User::factory()->create(['role' => User::ROLE_SUPER_ADMIN]);
        $project = Project::create(['name' => 'GEM Project', 'status' => 'active']);

        // 1. Create Form loads
        $resCreate = $this->actingAs($user)->get(route('programmes.activities.create'));
        $resCreate->assertStatus(200);
        $resCreate->assertSee('GEM Project');
        $resCreate->assertSee('Key Milestones');
        $resCreate->assertSee('Detailed Narrative');

        // 2. Store
        $this->actingAs($user)->post(route('programmes.activities.store'), [
            'project_id' => $project->id,
            'activity_title' => 'Sister Circle Session',
            'activity_date' => '2026-06-10',
            'location' => 'Maramba',
            'reporting_period' => 'Quarter 2 April, May, June 2026',
            'achievements_points' => "• 98% attendance\n• 100+ kits distributed",
            'achievements_narrative' => 'Comprehensive narrative explanation of the circle.',
            'challenges_points' => '• Room conflict',
            'challenges_narrative' => 'Resolved by scheduling morning.',
        ]);

        $entry = ActivityEntry::where('activity_title', 'Sister Circle Session')->first();
        $this->assertNotNull($entry);
        $this->assertEquals('Maramba', $entry->location);

        // 3. Show Details
        $resShow = $this->actingAs($user)->get(route('programmes.activities.show', $entry->token));
        $resShow->assertStatus(200);
        $resShow->assertSee('Sister Circle Session');
        $resShow->assertSee('98% attendance');
        $resShow->assertSee('Comprehensive narrative explanation');

        // 4. Edit Form
        $resEdit = $this->actingAs($user)->get(route('programmes.activities.edit', $entry->token));
        $resEdit->assertStatus(200);
        $resEdit->assertSee('Sister Circle Session');

        // 5. Update
        $this->actingAs($user)->put(route('programmes.activities.update', $entry->token), [
            'project_id' => $project->id,
            'activity_title' => 'Sister Circle Session Updated',
            'activity_date' => '2026-06-11',
            'location' => 'Maramba Hall',
            'achievements_points' => '• 99% attendance',
        ]);
        $this->assertEquals('Sister Circle Session Updated', $entry->fresh()->activity_title);

        // 6. Delete
        $this->actingAs($user)->delete(route('programmes.activities.destroy', $entry->token));
        $this->assertDatabaseMissing('activity_entries', ['id' => $entry->id]);
    }

    /**
     * Test Dynamic Interval Filtering (Day, Month, Quarter, Year).
     */
    public function test_dynamic_interval_filtering(): void
    {
        $user = User::factory()->create(['role' => User::ROLE_SUPER_ADMIN]);
        $project = Project::create(['name' => 'Livingstone Sports', 'status' => 'active']);

        // Create activity on June 15, 2026 (Q2)
        ActivityEntry::create([
            'project_id' => $project->id,
            'user_id' => $user->id,
            'activity_title' => 'June Match Day',
            'activity_date' => '2026-06-15',
            'achievements_points' => '• 98% pass rate in June',
        ]);

        // Create activity on January 10, 2026 (Q1)
        ActivityEntry::create([
            'project_id' => $project->id,
            'user_id' => $user->id,
            'activity_title' => 'January Bootcamp',
            'activity_date' => '2026-01-10',
            'achievements_points' => '• 50 youth enrolled in January',
        ]);

        // Filter by Day (June 15, 2026)
        $resDay = $this->actingAs($user)->get(route('programmes.hub', ['interval' => 'day', 'date' => '2026-06-15']));
        $resDay->assertSee('June Match Day');
        $resDay->assertDontSee('January Bootcamp');

        // Filter by Month (June 2026)
        $resMonth = $this->actingAs($user)->get(route('programmes.hub', ['interval' => 'month', 'month' => 6, 'year' => 2026]));
        $resMonth->assertSee('June Match Day');
        $resMonth->assertDontSee('January Bootcamp');

        // Filter by Quarter (Q2 2026)
        $resQ2 = $this->actingAs($user)->get(route('programmes.hub', ['interval' => 'quarter', 'quarter' => 2, 'year' => 2026]));
        $resQ2->assertSee('June Match Day');
        $resQ2->assertDontSee('January Bootcamp');

        // Filter by Year (2026)
        $resYear = $this->actingAs($user)->get(route('programmes.hub', ['interval' => 'year', 'year' => 2026]));
        $resYear->assertSee('June Match Day');
        $resYear->assertSee('January Bootcamp');
    }

    /**
     * Test Isolated Single Project Exports (PDF, PPTX, Web Projector).
     */
    public function test_isolated_single_project_exports(): void
    {
        $user = User::factory()->create(['role' => User::ROLE_SUPER_ADMIN]);
        $project = Project::create(['name' => 'Single Project Demo', 'status' => 'active']);

        ActivityEntry::create([
            'project_id' => $project->id,
            'user_id' => $user->id,
            'activity_title' => 'Single Proj Activity',
            'activity_date' => now()->toDateString(),
            'achievements_points' => '• 98% isolated metric',
        ]);

        // Isolated PDF
        $resPdf = $this->actingAs($user)->get(route('programmes.projects.export_pdf', $project->id));
        $resPdf->assertStatus(200);
        $resPdf->assertHeader('content-type', 'application/pdf');

        // Isolated PPTX
        $resPptx = $this->actingAs($user)->get(route('programmes.projects.export_pptx', $project->id));
        $resPptx->assertStatus(200);
        $resPptx->assertHeader('content-type', 'application/vnd.openxmlformats-officedocument.presentationml.presentation');

        // Isolated Projector
        $resProjector = $this->actingAs($user)->get(route('programmes.projects.projector', $project->id));
        $resProjector->assertStatus(200);
        $resProjector->assertSee('Single Project Demo');
    }

    /**
     * Test Consolidated Transposed Matrix Presentation Exports.
     */
    public function test_consolidated_transposed_matrix_projector_and_exports(): void
    {
        $user = User::factory()->create(['role' => User::ROLE_SUPER_ADMIN]);

        $p1 = Project::create(['name' => 'Project One', 'status' => 'active']);
        $p2 = Project::create(['name' => 'Project Two', 'status' => 'active']);

        ActivityEntry::create([
            'project_id' => $p1->id,
            'user_id' => $user->id,
            'activity_title' => 'P1 Activity',
            'activity_date' => now()->toDateString(),
            'achievements_points' => '• 98% of girls in P1 passed',
        ]);

        ActivityEntry::create([
            'project_id' => $p2->id,
            'user_id' => $user->id,
            'activity_title' => 'P2 Activity',
            'activity_date' => now()->toDateString(),
            'achievements_points' => '• 100+ youth in P2 attended',
        ]);

        // Transposed Projector View
        $resProjector = $this->actingAs($user)->get(route('programmes.projector'));
        $resProjector->assertStatus(200);
        $resProjector->assertSee('Project One');
        $resProjector->assertSee('Project Two');
        $resProjector->assertSee('98% of girls in P1 passed');
        $resProjector->assertSee('100+ youth in P2 attended');

        // Consolidated PDF Export
        $resPdf = $this->actingAs($user)->get(route('programmes.export_consolidated_pdf'));
        $resPdf->assertStatus(200);
        $resPdf->assertHeader('content-type', 'application/pdf');

        // Consolidated PPTX Export
        $resPptx = $this->actingAs($user)->get(route('programmes.export_consolidated_pptx'));
        $resPptx->assertStatus(200);
        $resPptx->assertHeader('content-type', 'application/vnd.openxmlformats-officedocument.presentationml.presentation');
    }

    /**
     * Test Discrete Pillar Sections (e.g. Milestones, Impact, Success Stories) and Qualitative Narratives in Presentations.
     */
    public function test_discrete_pillar_sections_and_qualitative_narratives_displayed_on_presentations(): void
    {
        $user = User::factory()->create(['role' => User::ROLE_SUPER_ADMIN]);
        $project = Project::create(['name' => 'Girls Academy Project', 'status' => 'active']);

        ActivityEntry::create([
            'project_id' => $project->id,
            'user_id' => $user->id,
            'activity_title' => 'Quarterly Milestone Review',
            'activity_date' => now()->toDateString(),
            'achievements_milestones' => '• Completed 5 coach trainings',
            'achievements_impact' => '• 92% of schools reported improved attendance',
            'achievements_stories' => '• Chanda enrolled in university following scholarship',
            'achievements_narrative' => 'Comprehensive qualitative narrative highlighting the transformative community impact.',
        ]);

        // 1. Live Projector View
        $resProjector = $this->actingAs($user)->get(route('programmes.projector'));
        $resProjector->assertStatus(200);
        $resProjector->assertSee('Girls Academy Project');
        $resProjector->assertSee('Key Milestones');
        $resProjector->assertSee('Quarterly Milestone Review: Completed 5 coach trainings');
        $resProjector->assertSee('Impact Evidence');
        $resProjector->assertSee('Quarterly Milestone Review: 92% of schools reported improved attendance');
        $resProjector->assertSee('Success Stories');
        $resProjector->assertSee('Quarterly Milestone Review: Chanda enrolled in university following scholarship');
        $resProjector->assertDontSee('Detailed Qualitative Narrative');
        $resProjector->assertDontSee('Comprehensive qualitative narrative highlighting the transformative community impact.');

        // 2. Consolidated PDF Export
        $resPdf = $this->actingAs($user)->get(route('programmes.export_consolidated_pdf'));
        $resPdf->assertStatus(200);
        $resPdf->assertHeader('content-type', 'application/pdf');

        // 3. Isolated Project PDF Export
        $resSinglePdf = $this->actingAs($user)->get(route('programmes.projects.export_pdf', $project->id));
        $resSinglePdf->assertStatus(200);
        $resSinglePdf->assertHeader('content-type', 'application/pdf');
    }

    /**
     * Test Smart Dynamic Project-Scoped Entry Creation with Structured Pillar JSON.
     */
    public function test_smart_dynamic_project_scoped_entry_creation_and_structured_pillars(): void
    {
        $officer = User::factory()->create(['role' => User::ROLE_PROJECT_OFFICER]);
        $project = Project::create(['name' => 'Empower Girls Zambia', 'status' => 'active']);
        $project->users()->attach($officer->id);

        // 1. Authenticated Project Officer can open project-scoped entry form
        $resForm = $this->actingAs($officer)->get(route('projects.entries.create', $project->id));
        $resForm->assertStatus(200);
        $resForm->assertSee('Empower Girls Zambia');
        $resForm->assertSee('Pillar 1: Project Achievements');
        $resForm->assertSee('Key Milestones');
        $resForm->assertSee('Impact Evidence');
        $resForm->assertSee('Success Stories');
        $resForm->assertSee('Detailed Narrative &amp; Comprehensive Qualitative Summary', false);

        // 2. Submit structured JSON pillar payload
        $postData = [
            'project_id' => $project->id,
            'activity_title' => 'Leadership Academy Induction',
            'activity_date' => '2026-06-20',
            'location' => 'Livingstone Hub',
            'period_granularity' => 'quarter',
            'pillar_1_achievements' => [
                'milestones' => ['• 98% of girls passed entrance assessment', '• 40 leaders inducted'],
                'impact' => ['• 100% attendance rate in week 1'],
                'stories' => ['• Faith shared inspiring testimony of resilience'],
            ],
            'pillar_1_narrative' => 'Pillar 1 full qualitative context with participant quotes and donor reflections.',
            'pillar_2_challenges' => [
                'operational' => ['• Rain delay on day 2'],
                'resources' => ['• Needed extra learning modules'],
                'risks' => ['• Transportation logistical risks mitigated with local bus charter'],
            ],
            'pillar_2_narrative' => 'Pillar 2 full qualitative risk analysis.',
            'pillar_3_learning' => [
                'lessons' => ['• Peer coaching increases retention'],
                'feedback' => ['• Girls requested longer practical sessions'],
                'innovation' => ['• Introduced digital check-in tablets'],
            ],
            'pillar_3_narrative' => 'Pillar 3 learning synthesis and adaptation plans.',
            'pillar_4_monitoring' => [
                'performance' => ['• On track with 95% target achievement'],
                'data_quality' => ['• Double-entry verification completed'],
                'evaluation' => ['• Mid-term evaluation scheduled for Q3'],
            ],
            'pillar_4_narrative' => 'Pillar 4 data quality audit notes.',
            'pillar_5_collaboration' => [
                'project_collab' => ['• Co-hosted workshop with Sports For Life'],
                'partnerships' => ['• MoE signed agreement for facility usage'],
                'cross_learning' => ['• Shared safeguarding best practices across cohorts'],
            ],
            'pillar_5_narrative' => 'Pillar 5 multi-stakeholder partnership reflections.',
        ];

        $resPost = $this->actingAs($officer)->post(route('projects.entries.store', $project->id), $postData);
        $resPost->assertRedirect(route('programmes.hub'));

        // 3. Verify Database Storage and Bidirectional Sync
        $entry = ActivityEntry::where('activity_title', 'Leadership Academy Induction')->first();
        $this->assertNotNull($entry);
        $this->assertEquals($project->id, $entry->project_id);
        $this->assertEquals($officer->id, $entry->user_id);
        $this->assertEquals('quarter', $entry->period_granularity);

        // JSON Columns
        $this->assertIsArray($entry->pillar_1_achievements);
        $this->assertContains('• 98% of girls passed entrance assessment', $entry->pillar_1_achievements['milestones']);
        $this->assertEquals('Pillar 1 full qualitative context with participant quotes and donor reflections.', $entry->pillar_1_narrative);

        // Synchronized Flat Bullet Points
        $milestoneBullets = $entry->getPoints('achievements_milestones');
        $this->assertContains('98% of girls passed entrance assessment', $milestoneBullets);

        $this->assertEquals('Pillar 1 full qualitative context with participant quotes and donor reflections.', $entry->achievements_narrative);
        $this->assertEquals('Pillar 2 full qualitative risk analysis.', $entry->challenges_narrative);

        // 4. Test Unassigned Officer cannot access or post to this project
        $otherOfficer = User::factory()->create(['role' => User::ROLE_PROJECT_OFFICER]);
        $resUnauthGet = $this->actingAs($otherOfficer)->get(route('projects.entries.create', $project->id));
        $resUnauthGet->assertStatus(403);

        $resUnauthPost = $this->actingAs($otherOfficer)->post(route('projects.entries.store', $project->id), $postData);
        $resUnauthPost->assertStatus(403);
    }
}
