<?php

namespace Tests\Feature;

use App\Models\ActivityEntry;
use App\Models\Project;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PillarDetailedSummaryTest extends TestCase
{
    use RefreshDatabase;

    public function test_detailed_summary_is_per_pillar_section_not_per_point_section(): void
    {
        $user = User::factory()->create([
            'role' => User::ROLE_SUPER_ADMIN,
        ]);
        $project = Project::create([
            'name' => 'Youth Leadership Hub',
            'status' => 'active',
        ]);

        // 1. Visit activity create form
        $response = $this->actingAs($user)->get(route('programmes.activities.create', ['project_id' => $project->id]));
        $response->assertStatus(200);

        // Verify there is a Detailed Summary textarea per pillar section
        $response->assertSee('name="pillar_1_narrative"', false);
        $response->assertSee('name="pillar_2_narrative"', false);
        $response->assertSee('name="pillar_3_narrative"', false);
        $response->assertSee('name="pillar_4_narrative"', false);
        $response->assertSee('name="pillar_5_narrative"', false);

        // Verify point section entries have hidden backward compatibility inputs rather than individual textareas
        $response->assertDontSee('<textarea name="pillar_1_achievements[milestones][narrative]"', false);
        $response->assertDontSee('<textarea name="pillar_1_achievements[impact][narrative]"', false);
        $response->assertDontSee('<textarea name="pillar_1_achievements[stories][narrative]"', false);

        // 2. Submit activity entry with pillar-level detailed summaries
        $postData = [
            'project_id' => $project->id,
            'activity_title' => 'Weekly Leadership & Life Skills Session',
            'activity_date' => '2026-06-15',
            'location' => 'Lusaka Hub',
            'pillar_1_achievements' => [
                'milestones' => ['points' => ['12 coaches certified', '4 clinic sessions completed']],
                'impact' => ['points' => ['98% participant retention']],
                'stories' => ['points' => ['Musonda became junior coach captain']],
            ],
            'pillar_1_narrative' => 'Pillar 1 comprehensive detailed summary covering coach clinics and community leadership evolution.',
            'pillar_2_challenges' => [
                'operational' => ['points' => ['Pitch surface required drainage']],
            ],
            'pillar_2_narrative' => 'Pillar 2 comprehensive detailed summary covering seasonal weather mitigation plans.',
        ];

        $storeResponse = $this->actingAs($user)->post(route('programmes.activities.store'), $postData);
        $storeResponse->assertRedirect(route('programmes.hub'));

        $entry = ActivityEntry::where('activity_title', 'Weekly Leadership & Life Skills Session')->first();
        $this->assertNotNull($entry);
        $this->assertEquals('Pillar 1 comprehensive detailed summary covering coach clinics and community leadership evolution.', $entry->pillar_1_narrative);
        $this->assertEquals('Pillar 1 comprehensive detailed summary covering coach clinics and community leadership evolution.', $entry->achievements_narrative);
        $this->assertEquals('Pillar 2 comprehensive detailed summary covering seasonal weather mitigation plans.', $entry->pillar_2_narrative);
        $this->assertEquals('Pillar 2 comprehensive detailed summary covering seasonal weather mitigation plans.', $entry->challenges_narrative);

        // 3. Verify show page displays Detailed Summary under the pillar section
        $showResponse = $this->actingAs($user)->get(route('programmes.activities.show', $entry->token));
        $showResponse->assertStatus(200);
        $showResponse->assertSee('Detailed Summary');
        $showResponse->assertSee('Pillar 1 comprehensive detailed summary covering coach clinics and community leadership evolution.');
        $showResponse->assertSee('Pillar 2 comprehensive detailed summary covering seasonal weather mitigation plans.');

        // 4. Verify edit page loads and pre-fills the pillar-level detailed summary
        $editResponse = $this->actingAs($user)->get(route('programmes.activities.edit', $entry->token));
        $editResponse->assertStatus(200);
        $editResponse->assertSee('Pillar 1 comprehensive detailed summary covering coach clinics and community leadership evolution.');
    }
}

