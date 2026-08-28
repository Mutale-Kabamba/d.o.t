<?php

namespace Tests\Feature;

use App\Models\AnonymousSubmission;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class WorksheetTest extends TestCase
{
    use RefreshDatabase;

    public function test_worksheet_index_page_loads_successfully(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertSee('Cohort Review &amp; Planning', false);
        $response->assertSee('100% Anonymous');
        $response->assertSee('Journey Mapping');
        $response->assertSee('PACRA');
    }

    public function test_anonymous_submission_can_be_stored(): void
    {
        $payload = [
            's1_recruitment_high' => 'Great mobilization across centers',
            's1_recruitment_challenges' => 'Transport issues in remote zones',
            's4_safeguarding_accessible' => 'Yes, completely clear',
            's8_one_word' => 'Empowered',
            's8_change_one_thing' => 'More market walk sessions',
        ];

        $response = $this->postJson('/submit', $payload);

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'success',
            'token',
            'message',
            'redirect_url'
        ]);

        $this->assertDatabaseHas('anonymous_submissions', [
            's8_one_word' => 'Empowered',
            's4_safeguarding_accessible' => 'Yes, completely clear',
        ]);
    }

    public function test_success_page_renders_with_valid_token(): void
    {
        $submission = AnonymousSubmission::create([
            's8_one_word' => 'Inspired',
        ]);

        $response = $this->get('/success/' . $submission->token);

        $response->assertStatus(200);
        $response->assertSee('Worksheet Submitted!');
        $response->assertSee($submission->token);
    }
}
