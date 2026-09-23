<?php

namespace Tests\Feature;

use App\Models\AnonymousSubmission;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class WorksheetTest extends TestCase
{
    use RefreshDatabase;

    public function test_worksheet_index_page_is_disabled(): void
    {
        $response = $this->get('/worksheet');

        $response->assertStatus(404);
    }

    public function test_anonymous_submission_endpoint_is_disabled(): void
    {
        $payload = [
            's1_recruitment_high' => 'Great mobilization across centers',
        ];

        $response = $this->postJson('/submit', $payload);

        $response->assertStatus(404);
    }

    public function test_legacy_success_page_is_disabled(): void
    {
        $submission = AnonymousSubmission::create([
            's8_one_word' => 'Inspired',
        ]);

        $response = $this->get('/success/' . $submission->token);

        $response->assertStatus(404);
    }

    public function test_legacy_pdf_export_is_disabled(): void
    {
        $payload = [
            's1_recruitment_high' => 'Great mobilization',
        ];

        $response = $this->post('/export-pdf', $payload);

        $response->assertStatus(404);
    }
}
