<?php

namespace Tests\Feature;

use App\Models\ActivityEntry;
use App\Models\Project;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DepartmentsAndActivityPeriodTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test department with sub-projects hierarchy creation and relationship queries.
     */
    public function test_department_and_subprojects_hierarchy(): void
    {
        $superAdmin = User::factory()->create(['role' => User::ROLE_SUPER_ADMIN]);

        // 1. Create a Department (e.g. Digital Skills)
        $responseDept = $this->actingAs($superAdmin)->post(route('programmes.projects.store'), [
            'name' => 'Digital Skills',
            'code' => 'DS',
            'location' => 'Livingstone Hub',
            'description' => 'Department managing youth digital literacy and innovation.',
            'is_department' => '1',
            'status' => 'active',
        ]);
        $responseDept->assertRedirect();

        $dept = Project::where('name', 'Digital Skills')->first();
        $this->assertNotNull($dept);
        $this->assertTrue((bool) $dept->is_department);
        $this->assertTrue($dept->isDepartment());
        $this->assertNull($dept->parent_id);

        // 2. Create subprojects under Digital Skills (Ehub, Going Beyond, Secondary School)
        $subNames = ['Ehub', 'Going Beyond', 'Secondary School'];
        foreach ($subNames as $subName) {
            $responseSub = $this->actingAs($superAdmin)->post(route('programmes.projects.store'), [
                'name' => $subName,
                'parent_id' => $dept->id,
                'location' => 'Livingstone',
                'status' => 'active',
            ]);
            $responseSub->assertRedirect();
        }

        $dept->refresh();
        $this->assertCount(3, $dept->children);
        $this->assertEquals(['Ehub', 'Going Beyond', 'Secondary School'], $dept->children->pluck('name')->sort()->values()->all());

        // Test descendantProjectIds includes parent + all children
        $descendants = $dept->descendantProjectIds();
        $this->assertCount(4, $descendants);
        $this->assertContains($dept->id, $descendants);

        $ehub = Project::where('name', 'Ehub')->first();
        $this->assertTrue($ehub->isSubProject());
        $this->assertEquals('Digital Skills', $ehub->parent->name);
        $this->assertEquals('Digital Skills ↳ Ehub', $ehub->hierarchy_name);
    }

    /**
     * Test user access inheritance: user assigned to parent department has access to child projects.
     */
    public function test_user_assigned_to_parent_department_inherits_child_access(): void
    {
        $officer = User::factory()->create(['role' => User::ROLE_PROJECT_OFFICER]);
        $dept = Project::create([
            'name' => 'Digital Skills',
            'is_department' => true,
            'status' => 'active',
        ]);

        $child1 = Project::create([
            'name' => 'Ehub',
            'parent_id' => $dept->id,
            'status' => 'active',
        ]);

        $child2 = Project::create([
            'name' => 'Going Beyond',
            'parent_id' => $dept->id,
            'status' => 'active',
        ]);

        $unrelated = Project::create([
            'name' => 'Solar Agriculture',
            'status' => 'active',
        ]);

        // Assign officer only to parent department
        $dept->users()->attach($officer->id);

        $this->assertTrue($officer->canAccessProject($dept->id));
        $this->assertTrue($officer->canAccessProject($child1->id));
        $this->assertTrue($officer->canAccessProject($child2->id));
        $this->assertFalse($officer->canAccessProject($unrelated->id));

        // Test query scope for user
        $accessibleProjects = Project::forUser($officer)->pluck('name')->all();
        $this->assertContains('Digital Skills', $accessibleProjects);
        $this->assertContains('Ehub', $accessibleProjects);
        $this->assertContains('Going Beyond', $accessibleProjects);
        $this->assertNotContains('Solar Agriculture', $accessibleProjects);
    }

    /**
     * Test filtering activities by Department returns activities from department and all children.
     */
    public function test_filtering_hub_by_department_returns_subproject_activities(): void
    {
        $superAdmin = User::factory()->create(['role' => User::ROLE_SUPER_ADMIN]);

        $dept = Project::create(['name' => 'Digital Skills', 'is_department' => true, 'status' => 'active']);
        $ehub = Project::create(['name' => 'Ehub', 'parent_id' => $dept->id, 'status' => 'active']);
        $other = Project::create(['name' => 'Health & Sanitation', 'status' => 'active']);

        $actDept = ActivityEntry::create([
            'project_id' => $dept->id,
            'user_id' => $superAdmin->id,
            'activity_title' => 'Digital Skills Strategic Plan',
            'activity_date' => '2026-06-01',
            'reporting_period' => 'Quarter 2 2026',
        ]);

        $actEhub = ActivityEntry::create([
            'project_id' => $ehub->id,
            'user_id' => $superAdmin->id,
            'activity_title' => 'Ehub Advanced Python Training',
            'activity_date' => '2026-06-05',
            'reporting_period' => 'Quarter 2 2026',
        ]);

        $actOther = ActivityEntry::create([
            'project_id' => $other->id,
            'user_id' => $superAdmin->id,
            'activity_title' => 'Borehole Drilling',
            'activity_date' => '2026-06-10',
            'reporting_period' => 'Quarter 2 2026',
        ]);

        $res = $this->actingAs($superAdmin)->get(route('programmes.hub', [
            'tab' => 'activities',
            'project_id' => $dept->id,
        ]));

        $res->assertStatus(200);
        $res->assertSee('Digital Skills Strategic Plan');
        $res->assertSee('Ehub Advanced Python Training');
        $res->assertDontSee('Borehole Drilling');
    }

    /**
     * Test multi-day / ongoing activity periods (Training, Class, Session) with start and end dates and location.
     */
    public function test_multi_day_activity_with_period_and_location(): void
    {
        $superAdmin = User::factory()->create(['role' => User::ROLE_SUPER_ADMIN]);
        $project = Project::create(['name' => 'Ehub', 'status' => 'active']);

        // Log a 3-week Training with start/end date range and Location
        $response = $this->actingAs($superAdmin)->post(route('programmes.activities.store'), [
            'project_id' => $project->id,
            'activity_title' => 'Full-Stack Web Dev Bootcamp',
            'activity_type' => 'training',
            'period_type' => 'range',
            'start_date' => '2026-06-01',
            'end_date' => '2026-06-19',
            'period_cadence' => 'Monday – Friday, 09:00 - 16:00',
            'venue' => 'Innovation Lab Livingstone',
            'reporting_period' => 'Quarter 2 June 2026',
            'achievements_points' => "• 25 Youth enrolled\n• 100% attendance rate",
        ]);

        $response->assertRedirect();

        $activity = ActivityEntry::where('activity_title', 'Full-Stack Web Dev Bootcamp')->first();
        $this->assertNotNull($activity);
        $this->assertEquals('training', $activity->activity_type);
        $this->assertEquals('range', $activity->period_type);
        $this->assertEquals('2026-06-01', $activity->start_date->toDateString());
        $this->assertEquals('2026-06-19', $activity->end_date->toDateString());
        $this->assertEquals('2026-06-01', $activity->activity_date->toDateString()); // kept in sync
        $this->assertTrue($activity->isOngoing());
        $this->assertEquals('Training', $activity->type_label);
        $this->assertEquals('Location', $activity->venue_or_location_label);
        $this->assertStringContainsString('19 days', $activity->formatted_period);

        // View detail page
        $resShow = $this->actingAs($superAdmin)->get(route('programmes.activities.show', $activity->token));
        $resShow->assertStatus(200);
        $resShow->assertSee('Training');
        $resShow->assertSee('Location:');
        $resShow->assertSee('Innovation Lab Livingstone');
        $resShow->assertSee('Monday – Friday, 09:00 - 16:00');
    }

    /**
     * Test single-day activity displays Venue label and single date.
     */
    public function test_single_day_activity_displays_venue(): void
    {
        $superAdmin = User::factory()->create(['role' => User::ROLE_SUPER_ADMIN]);
        $project = Project::create(['name' => 'Secondary School', 'status' => 'active']);

        // Log a single-day activity
        $response = $this->actingAs($superAdmin)->post(route('programmes.activities.store'), [
            'project_id' => $project->id,
            'activity_title' => 'Girls Coding Hackathon Day',
            'activity_type' => 'activity',
            'period_type' => 'single',
            'activity_date' => '2026-07-15',
            'venue' => 'Civic Center Auditorium',
            'reporting_period' => 'Quarter 3 July 2026',
            'achievements_points' => '• 50 secondary students coded mini apps',
        ]);

        $response->assertRedirect();

        $activity = ActivityEntry::where('activity_title', 'Girls Coding Hackathon Day')->first();
        $this->assertNotNull($activity);
        $this->assertEquals('activity', $activity->activity_type);
        $this->assertFalse($activity->isOngoing());
        $this->assertEquals('Venue', $activity->venue_or_location_label);

        // View detail page
        $resShow = $this->actingAs($superAdmin)->get(route('programmes.activities.show', $activity->token));
        $resShow->assertStatus(200);
        $resShow->assertSee('Venue:');
        $resShow->assertSee('Civic Center Auditorium');
    }

    /**
     * Test Admin Teams controller updates parent_id and is_department.
     */
    public function test_admin_teams_crud_with_department_and_parent(): void
    {
        $superAdmin = User::factory()->create(['role' => User::ROLE_SUPER_ADMIN]);

        // 1. Create Department via Admin Teams Store
        $resStore = $this->actingAs($superAdmin)->post(route('admin.teams.store'), [
            'name' => 'Digital Skills Dept',
            'code' => 'DSD',
            'is_department' => '1',
            'location' => 'Livingstone',
        ]);
        $resStore->assertRedirect(route('admin.teams.index'));

        $dept = Project::where('name', 'Digital Skills Dept')->first();
        $this->assertNotNull($dept);
        $this->assertTrue((bool) $dept->is_department);

        // 2. Create subproject linked to the department
        $resStoreSub = $this->actingAs($superAdmin)->post(route('admin.teams.store'), [
            'name' => 'Ehub Tech Center',
            'parent_id' => $dept->id,
            'location' => 'Town Center',
        ]);
        $resStoreSub->assertRedirect(route('admin.teams.index'));

        $sub = Project::where('name', 'Ehub Tech Center')->first();
        $this->assertEquals($dept->id, $sub->parent_id);

        // 3. Update project via Admin Teams Update
        $resUpdate = $this->actingAs($superAdmin)->put(route('admin.teams.update', $sub->id), [
            'name' => 'Ehub Tech Center Renamed',
            'parent_id' => $dept->id,
            'is_department' => '0',
            'status' => 'active',
        ]);
        $resUpdate->assertRedirect();

        $sub->refresh();
        $this->assertEquals('Ehub Tech Center Renamed', $sub->name);
        $this->assertEquals($dept->id, $sub->parent_id);
    }

    /**
     * Test parent departments are NOT counted as projects in metrics and statistics.
     */
    public function test_parent_departments_are_not_counted_as_projects(): void
    {
        $superAdmin = User::factory()->create(['role' => User::ROLE_SUPER_ADMIN]);

        // Create 2 departments
        $dept1 = Project::create(['name' => 'Education', 'is_department' => true, 'status' => 'active']);
        $dept2 = Project::create(['name' => 'Health', 'is_department' => true, 'status' => 'active']);

        // Create 2 children under Education
        $child1 = Project::create(['name' => 'Literacy', 'parent_id' => $dept1->id, 'is_department' => false, 'status' => 'active']);
        $child2 = Project::create(['name' => 'After Class', 'parent_id' => $dept1->id, 'is_department' => false, 'status' => 'active']);

        // Create 1 standalone project
        $standalone = Project::create(['name' => 'Football for Good', 'is_department' => false, 'status' => 'active']);

        // Operational projects scope should exclude departments
        $this->assertEquals(3, Project::onlyProjects()->count());
        $this->assertEquals(2, Project::departments()->count());

        // Check Hub response metrics
        $resHub = $this->actingAs($superAdmin)->get(route('programmes.hub'));
        $resHub->assertStatus(200);
        $metrics = $resHub->viewData('metrics');
        $this->assertEquals(3, $metrics['total_projects'], 'Parent departments must NOT be counted in total_projects');
        $this->assertEquals(3, $metrics['active_projects'], 'Parent departments must NOT be counted in active_projects');
        $this->assertEquals(2, $metrics['total_departments']);

        // Check Admin Teams response metrics
        $resTeams = $this->actingAs($superAdmin)->get(route('admin.teams.index'));
        $resTeams->assertStatus(200);
        $this->assertEquals(3, $resTeams->viewData('totalProjects'));
        $this->assertEquals(3, $resTeams->viewData('activeProjects'));
        $this->assertEquals(2, $resTeams->viewData('totalDepartments'));
    }

    /**
     * Test linking sibling projects under a parent department via controller and model helpers.
     */
    public function test_linking_sibling_projects_under_parent_department(): void
    {
        $superAdmin = User::factory()->create(['role' => User::ROLE_SUPER_ADMIN]);

        // 1. Create Parent Department: Education
        $dept = Project::create(['name' => 'Education', 'is_department' => true, 'status' => 'active']);

        // 2. Create first child project: Literacy
        $res1 = $this->actingAs($superAdmin)->post(route('programmes.projects.store'), [
            'name' => 'Literacy',
            'parent_id' => $dept->id,
            'is_department' => '0',
            'status' => 'active',
        ]);
        $res1->assertRedirect();
        $literacy = Project::where('name', 'Literacy')->first();
        $this->assertNotNull($literacy);
        $this->assertFalse($literacy->isLinked());

        // 3. Create second child project: After Class, linked to Literacy
        $res2 = $this->actingAs($superAdmin)->post(route('programmes.projects.store'), [
            'name' => 'After Class',
            'parent_id' => $dept->id,
            'linked_project_id' => $literacy->id,
            'is_department' => '0',
            'status' => 'active',
        ]);
        $res2->assertRedirect();

        $afterClass = Project::where('name', 'After Class')->first();
        $this->assertNotNull($afterClass);
        $literacy->refresh();

        // Both must be linked with the same link_group
        $this->assertTrue($literacy->isLinked());
        $this->assertTrue($afterClass->isLinked());
        $this->assertNotNull($literacy->link_group);
        $this->assertEquals($literacy->link_group, $afterClass->link_group);

        // Combined presentation title
        $this->assertEquals('Education (After Class & Literacy)', $literacy->combined_presentation_title);
        $this->assertEquals('Education (After Class & Literacy)', $afterClass->combined_presentation_title);
    }

    /**
     * Test bullet points formatting for linked projects.
     */
    public function test_bullet_formatting_for_linked_projects(): void
    {
        // 1. Bullet with colon prefix (e.g. "Sessions: 20 sessions done")
        $b1 = Project::formatLinkedBullet("• Sessions: 20 sessions done", null, "Literacy");
        $this->assertEquals("Sessions (Literacy): 20 sessions done", $b1);

        $b2 = Project::formatLinkedBullet("Sessions: 20 sessions done", null, "After Class");
        $this->assertEquals("Sessions (After Class): 20 sessions done", $b2);

        // 2. Bullet without colon prefix
        $b3 = Project::formatLinkedBullet("20 sessions done", "Sessions", "Literacy");
        $this->assertEquals("Sessions (Literacy): 20 sessions done", $b3);

        $b4 = Project::formatLinkedBullet("Completed quarterly curriculum", null, "Literacy");
        $this->assertEquals("Completed quarterly curriculum (Literacy)", $b4);
    }

    /**
     * Test combined presentation data outputs single combined slide under parent department.
     */
    public function test_combined_presentation_data_groups_linked_projects_on_single_slide(): void
    {
        $superAdmin = User::factory()->create(['role' => User::ROLE_SUPER_ADMIN]);

        $dept = Project::create(['name' => 'Education', 'is_department' => true, 'status' => 'active']);
        $groupKey = 'grp_edu_123';

        $literacy = Project::create([
            'name' => 'Literacy',
            'parent_id' => $dept->id,
            'link_group' => $groupKey,
            'is_department' => false,
            'status' => 'active',
        ]);

        $afterClass = Project::create([
            'name' => 'After Class',
            'parent_id' => $dept->id,
            'link_group' => $groupKey,
            'is_department' => false,
            'status' => 'active',
        ]);

        // Log activity entries with specific milestone bullets
        ActivityEntry::create([
            'project_id' => $literacy->id,
            'user_id' => $superAdmin->id,
            'activity_title' => 'Literacy Reading Clubs',
            'activity_date' => '2026-06-10',
            'reporting_period' => 'Quarter 2 2026',
            'achievements_points' => "• Sessions: 20 sessions done",
        ]);

        ActivityEntry::create([
            'project_id' => $afterClass->id,
            'user_id' => $superAdmin->id,
            'activity_title' => 'After Class Homework Center',
            'activity_date' => '2026-06-12',
            'reporting_period' => 'Quarter 2 2026',
            'achievements_points' => "• Sessions: 20 sessions done",
        ]);

        $controller = app(\App\Http\Controllers\ProgrammesMeetingController::class);
        $request = new \Illuminate\Http\Request();
        $presentationData = $controller->getAggregatedPresentationData($request);

        $projectDataList = $presentationData['projectDataList'];

        // Parent department itself is NOT an empty slide
        $projectNames = collect($projectDataList)->pluck('project_name')->all();
        $this->assertNotContains('Education', $projectNames);

        // Literacy & After Class are grouped into 1 composite unit
        $this->assertCount(1, $projectDataList);
        $combinedUnit = $projectDataList[0];
        $this->assertEquals('Education (After Class & Literacy)', $combinedUnit['project_name']);

        // Achievements points on the combined slide
        $achievementPoints = $combinedUnit['theme_points']['achievements'];
        $this->assertContains('Sessions (Literacy): 20 sessions done', $achievementPoints);
        $this->assertContains('Sessions (After Class): 20 sessions done', $achievementPoints);

        // Also verify live web projector view displays the combined slide
        $resProjector = $this->actingAs($superAdmin)->get(route('programmes.projector'));
        $resProjector->assertStatus(200);
        $resProjector->assertSee('Education (After Class &amp; Literacy)', false);
        $resProjector->assertSee('Sessions (Literacy): 20 sessions done');
        $resProjector->assertSee('Sessions (After Class): 20 sessions done');
    }

    /**
     * Test separate Parent Projects tab and card display differentiation between Parent, Child, and Standalone.
     */
    public function test_parent_projects_tab_and_card_display_differentiation(): void
    {
        $superAdmin = User::factory()->create(['role' => User::ROLE_SUPER_ADMIN]);

        // 1. Setup Parent Department, Child sub-projects, and Standalone project
        $dept = Project::create([
            'name' => 'Digital Skills',
            'code' => 'DS',
            'is_department' => true,
            'status' => 'active',
        ]);

        $child1 = Project::create([
            'name' => 'Ehub',
            'code' => 'EH',
            'parent_id' => $dept->id,
            'is_department' => false,
            'status' => 'active',
        ]);

        $child2 = Project::create([
            'name' => 'Going Beyond',
            'code' => 'GB',
            'parent_id' => $dept->id,
            'is_department' => false,
            'status' => 'active',
        ]);

        $standalone = Project::create([
            'name' => 'Community Library',
            'code' => 'CL',
            'is_department' => false,
            'status' => 'active',
        ]);

        // Log an activity for child1
        ActivityEntry::create([
            'project_id' => $child1->id,
            'user_id' => $superAdmin->id,
            'activity_title' => 'Web Dev Workshop',
            'activity_date' => '2026-06-15',
            'reporting_period' => 'Quarter 2 2026',
        ]);

        // 2. Test Hub tab=departments (Separate Parent Projects tab)
        $resDeptTab = $this->actingAs($superAdmin)->get(route('programmes.hub', ['tab' => 'departments']));
        $resDeptTab->assertStatus(200);
        $resDeptTab->assertSee('Parent Projects Directory');
        $resDeptTab->assertSee('DEPARTMENT');
        $resDeptTab->assertSee('Digital Skills');
        $resDeptTab->assertSee('↳ Ehub');
        $resDeptTab->assertSee('↳ Going Beyond');
        $resDeptTab->assertSee('Project Department Deck');
        $resDeptTab->assertSee('2 Sub-Projects');

        // 3. Test Hub tab=projects card display (Standalone and Child are all Projects)
        $resProjTab = $this->actingAs($superAdmin)->get(route('programmes.hub', ['tab' => 'projects']));
        $resProjTab->assertStatus(200);
        // All operational projects should be labeled as Project
        $resProjTab->assertSee('PROJECT');
        $resProjTab->assertSee('Project Deck');
        // Child project cues
        $resProjTab->assertSee('Ehub');
        $resProjTab->assertSee('Going Beyond');
        $resProjTab->assertSee('Department: Digital Skills');
        // Standalone project cues
        $resProjTab->assertSee('Standalone Initiative');
        $resProjTab->assertSee('Community Library');

        // 4. Test Admin Teams type filters
        $resAdminDept = $this->actingAs($superAdmin)->get(route('admin.teams.index', ['type' => 'departments']));
        $resAdminDept->assertStatus(200);
        $resAdminDept->assertSee('Digital Skills');
        $resAdminDept->assertSee('DEPARTMENT');
        $resAdminDept->assertDontSee('Community Library');

        $resAdminChild = $this->actingAs($superAdmin)->get(route('admin.teams.index', ['type' => 'children']));
        $resAdminChild->assertStatus(200);
        $resAdminChild->assertSee('Ehub');
        $resAdminChild->assertSee('Going Beyond');
        $resAdminChild->assertSee('PROJECT');
        $resAdminChild->assertSee('Department: Digital Skills');
        $resAdminChild->assertDontSee('Community Library');

        $resAdminStand = $this->actingAs($superAdmin)->get(route('admin.teams.index', ['type' => 'standalone']));
        $resAdminStand->assertStatus(200);
        $resAdminStand->assertSee('Community Library');
        $resAdminStand->assertSee('PROJECT');
        $resAdminStand->assertSee('Standalone Project');
    }
}

