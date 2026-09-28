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
}
