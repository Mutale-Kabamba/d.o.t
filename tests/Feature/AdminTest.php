<?php

namespace Tests\Feature;

use App\Models\AnonymousSubmission;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AdminTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::create([
            'name' => 'DOT Admin',
            'email' => 'admin@dot.org',
            'password' => Hash::make('password'),
        ]);
    }

    public function test_unauthenticated_user_is_redirected_to_login(): void
    {
        $response = $this->get('/admin/submissions');

        $response->assertRedirect('/admin/login');
    }

    public function test_admin_can_login_with_valid_credentials(): void
    {
        $response = $this->post('/admin/login', [
            'email' => 'admin@dot.org',
            'password' => 'password',
        ]);

        $response->assertRedirect('/admin/submissions');
        $this->assertAuthenticatedAs($this->admin);
    }

    public function test_admin_dashboard_loads_successfully_when_authenticated(): void
    {
        AnonymousSubmission::create([
            's8_one_word' => 'Inspired',
            's4_safeguarding_accessible' => 'Yes, completely clear',
        ]);

        $response = $this->actingAs($this->admin)->get('/admin/submissions');

        $response->assertStatus(200);
        $response->assertSee('Cohort Submissions &amp; Reports', false);
        $response->assertSee('Total Submissions');
        $response->assertSee('Inspired');
    }

    public function test_admin_can_search_submissions(): void
    {
        AnonymousSubmission::create([
            's5_finance_stipends' => 'Mobile money delay issue',
            's8_one_word' => 'Resilient',
        ]);

        $response = $this->actingAs($this->admin)->get('/admin/submissions?q=Mobile');

        $response->assertStatus(200);
        $response->assertSee('Resilient');
    }

    public function test_admin_can_export_csv(): void
    {
        AnonymousSubmission::create([
            's8_one_word' => 'Empowered',
        ]);

        $response = $this->actingAs($this->admin)->get('/admin/submissions/export-csv');

        $response->assertStatus(200);
        $response->assertHeader('content-type', 'text/csv; charset=utf-8');
    }

    public function test_admin_can_export_master_pdf(): void
    {
        AnonymousSubmission::create([
            's8_one_word' => 'Transformed',
            's1_recruitment_high' => 'Great teamwork during recruitment',
        ]);

        $response = $this->actingAs($this->admin)->get('/admin/submissions/export-master-pdf');

        $response->assertStatus(200);
        $response->assertHeader('content-type', 'application/pdf');
    }

    public function test_admin_can_view_single_submission(): void
    {
        $submission = AnonymousSubmission::create([
            's8_one_word' => 'Motivated',
        ]);

        $response = $this->actingAs($this->admin)->get('/admin/submissions/' . $submission->token);

        $response->assertStatus(200);
        $response->assertSee($submission->token);
        $response->assertSee('Motivated');
    }

    public function test_admin_can_delete_submission(): void
    {
        $submission = AnonymousSubmission::create([
            's8_one_word' => 'Temporary',
        ]);

        $response = $this->actingAs($this->admin)->delete('/admin/submissions/' . $submission->token);

        $response->assertRedirect('/admin/submissions');
        $this->assertDatabaseMissing('anonymous_submissions', [
            'id' => $submission->id,
        ]);
    }

    public function test_admin_logout_redirects_to_main_app(): void
    {
        $response = $this->actingAs($this->admin)->post('/admin/logout');

        $response->assertRedirect('/');
        $this->assertGuest();
    }
}
