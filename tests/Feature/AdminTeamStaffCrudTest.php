<?php

namespace Tests\Feature;

use App\Models\ActivityEntry;
use App\Models\Project;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AdminTeamStaffCrudTest extends TestCase
{
    use RefreshDatabase;

    protected User $superAdmin;
    protected User $officer;
    protected Project $project;

    protected function setUp(): void
    {
        parent::setUp();

        $this->superAdmin = User::create([
            'name' => 'Super Administrator',
            'email' => 'admin@dot.org',
            'password' => Hash::make('password'),
            'role' => User::ROLE_SUPER_ADMIN,
        ]);

        $this->officer = User::create([
            'name' => 'Mwila Tembo',
            'email' => 'mwila@dot.org',
            'password' => Hash::make('password'),
            'role' => User::ROLE_PROJECT_OFFICER,
        ]);

        $this->project = Project::create([
            'name' => 'Football for Health',
            'code' => 'F4H',
            'location' => 'Livingstone',
            'description' => 'Youth sports and life skills',
            'status' => 'active',
        ]);

        $this->project->users()->attach($this->officer->id);
    }

    public function test_unauthenticated_user_cannot_access_admin_staff(): void
    {
        $response = $this->get('/admin/staff');
        $response->assertRedirect('/login');
    }

    public function test_admin_can_view_staff_index_with_kpis(): void
    {
        $response = $this->actingAs($this->superAdmin)->get('/admin/staff');

        $response->assertStatus(200);
        $response->assertSee('Staff &amp; Personnel Management', false);
        $response->assertSee('Mwila Tembo');
        $response->assertSee('mwila@dot.org');
        $response->assertSee('Total Staff');
        $response->assertSee('Project Officers');
    }

    public function test_admin_can_filter_and_search_staff(): void
    {
        User::create([
            'name' => 'Faith Musonda',
            'email' => 'faith@dot.org',
            'password' => Hash::make('password'),
            'role' => User::ROLE_PROJECT_ASSISTANT,
        ]);

        // Search by name
        $response = $this->actingAs($this->superAdmin)->get('/admin/staff?q=Faith');
        $response->assertStatus(200);
        $response->assertSee('Faith Musonda');
        $response->assertDontSee('Mwila Tembo');

        // Filter by role
        $roleResponse = $this->actingAs($this->superAdmin)->get('/admin/staff?role=project_assistant');
        $roleResponse->assertStatus(200);
        $roleResponse->assertSee('Faith Musonda');
    }

    public function test_admin_can_create_new_staff_account(): void
    {
        $response = $this->actingAs($this->superAdmin)->post('/admin/staff', [
            'name' => 'Kelvin Phiri',
            'email' => 'kelvin@dot.org',
            'password' => 'secret123',
            'role' => User::ROLE_PROJECT_OFFICER,
            'project_ids' => [$this->project->id],
        ]);

        $response->assertRedirect(route('admin.staff.index'));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('users', [
            'name' => 'Kelvin Phiri',
            'email' => 'kelvin@dot.org',
            'role' => User::ROLE_PROJECT_OFFICER,
        ]);

        $createdUser = User::where('email', 'kelvin@dot.org')->first();
        $this->assertTrue(Hash::check('secret123', $createdUser->password));
        $this->assertTrue($createdUser->projects->contains($this->project->id));
    }

    public function test_admin_can_view_single_staff_profile(): void
    {
        $response = $this->actingAs($this->superAdmin)->get('/admin/staff/' . $this->officer->id);

        $response->assertStatus(200);
        $response->assertSee('Mwila Tembo');
        $response->assertSee('Football for Health');

        // JSON endpoint test
        $jsonResponse = $this->actingAs($this->superAdmin)->getJson('/admin/staff/' . $this->officer->id);
        $jsonResponse->assertStatus(200)
            ->assertJsonFragment([
                'name' => 'Mwila Tembo',
                'email' => 'mwila@dot.org',
            ]);
    }

    public function test_admin_can_update_staff_member(): void
    {
        $proj2 = Project::create([
            'name' => 'Girls Mentorship',
            'code' => 'GEM',
            'status' => 'active',
        ]);

        $response = $this->actingAs($this->superAdmin)->put('/admin/staff/' . $this->officer->id, [
            'name' => 'Mwila Tembo Updated',
            'email' => 'mwila.updated@dot.org',
            'password' => 'newpassword123',
            'role' => User::ROLE_PROJECT_ASSISTANT,
            'project_ids' => [$proj2->id],
        ]);

        $response->assertRedirect(route('admin.staff.index'));
        $response->assertSessionHas('success');

        $this->officer->refresh();
        $this->assertEquals('Mwila Tembo Updated', $this->officer->name);
        $this->assertEquals('mwila.updated@dot.org', $this->officer->email);
        $this->assertEquals(User::ROLE_PROJECT_ASSISTANT, $this->officer->role);
        $this->assertTrue(Hash::check('newpassword123', $this->officer->password));
        $this->assertTrue($this->officer->projects->contains($proj2->id));
        $this->assertFalse($this->officer->projects->contains($this->project->id));
    }

    public function test_admin_cannot_delete_own_account(): void
    {
        $response = $this->actingAs($this->superAdmin)->delete('/admin/staff/' . $this->superAdmin->id);

        $response->assertRedirect(route('admin.staff.index'));
        $response->assertSessionHas('error');
        $this->assertDatabaseHas('users', ['id' => $this->superAdmin->id]);
    }

    public function test_admin_can_delete_other_staff_member(): void
    {
        $response = $this->actingAs($this->superAdmin)->delete('/admin/staff/' . $this->officer->id);

        $response->assertRedirect(route('admin.staff.index'));
        $response->assertSessionHas('success');
        $this->assertDatabaseMissing('users', ['id' => $this->officer->id]);
    }

    public function test_admin_can_view_teams_index(): void
    {
        $response = $this->actingAs($this->superAdmin)->get('/admin/teams');

        $response->assertStatus(200);
        $response->assertSee('Teams &amp; Project Initiatives', false);
        $response->assertSee('Football for Health');
        $response->assertSee('Total Initiatives');
    }

    public function test_admin_can_create_and_manage_teams(): void
    {
        // 1. Create
        $response = $this->actingAs($this->superAdmin)->post('/admin/teams', [
            'name' => 'Youth Entrepreneurship Hub',
            'code' => 'YEH',
            'location' => 'Lusaka',
            'description' => 'Business incubation for youth',
            'status' => 'active',
            'user_ids' => [$this->officer->id],
        ]);

        $response->assertRedirect(route('admin.teams.index'));
        $this->assertDatabaseHas('projects', [
            'name' => 'Youth Entrepreneurship Hub',
            'code' => 'YEH',
        ]);

        $team = Project::where('code', 'YEH')->first();
        $this->assertTrue($team->users->contains($this->officer->id));

        // 2. Update
        $updateResponse = $this->actingAs($this->superAdmin)->put('/admin/teams/' . $team->id, [
            'name' => 'Youth Enterprise & Tech Incubator',
            'code' => 'YETI',
            'location' => 'Livingstone Hub',
            'description' => 'Updated description',
            'status' => 'active',
            'user_ids' => [],
        ]);

        $updateResponse->assertRedirect(route('admin.teams.index'));
        $team->refresh();
        $this->assertEquals('Youth Enterprise & Tech Incubator', $team->name);
        $this->assertCount(0, $team->users);

        // 3. Toggle Status
        $toggleResponse = $this->actingAs($this->superAdmin)->post('/admin/teams/' . $team->id . '/toggle-status');
        $toggleResponse->assertRedirect(route('admin.teams.index'));
        $team->refresh();
        $this->assertEquals('archived', $team->status);

        // 4. Delete
        $deleteResponse = $this->actingAs($this->superAdmin)->delete('/admin/teams/' . $team->id);
        $deleteResponse->assertRedirect(route('admin.teams.index'));
        $this->assertDatabaseMissing('projects', ['id' => $team->id]);
    }

    public function test_programmes_hub_user_update_route(): void
    {
        $response = $this->actingAs($this->superAdmin)->put('/programmes-meeting/users/' . $this->officer->id, [
            'name' => 'Mwila Hub Edit',
            'email' => 'mwila.hub@dot.org',
            'role' => User::ROLE_PROJECT_OFFICER,
            'project_ids' => [$this->project->id],
        ]);

        $response->assertRedirect(route('programmes.hub', ['tab' => 'staff']));
        $this->officer->refresh();
        $this->assertEquals('Mwila Hub Edit', $this->officer->name);
        $this->assertEquals('mwila.hub@dot.org', $this->officer->email);
    }
}
