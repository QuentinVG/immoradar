<?php

namespace Tests\Feature;

use App\Models\Project;
use App\Models\Property;
use App\Models\User;
use App\Models\VisitChecklistQuestion;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;

class SecurityAndReportsTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_cannot_access_another_users_project(): void
    {
        $owner = User::factory()->create();
        $intruder = User::factory()->create();
        $project = Project::factory()->for($owner)->create();

        $this->actingAs($intruder)
            ->get(route('projects.show', $project))
            ->assertForbidden();
    }

    public function test_photo_upload_validation_rejects_non_image(): void
    {
        $project = Project::factory()->create();

        $payload = Property::factory()->make([
            'main_photo' => UploadedFile::fake()->create('document.pdf', 10, 'application/pdf'),
        ])->toArray();

        $this->actingAs($project->user)
            ->post(route('projects.properties.store', $project), $payload)
            ->assertSessionHasErrors('main_photo');
    }

    public function test_property_report_pdf_is_generated(): void
    {
        $property = Property::factory()->create();

        $this->actingAs($property->project->user)
            ->get(route('projects.properties.report', [$property->project, $property]))
            ->assertOk()
            ->assertHeader('content-type', 'application/pdf');
    }

    public function test_demo_account_cannot_write_data(): void
    {
        $demo = User::factory()->create(['email' => 'demo@immoradar.test']);
        $project = Project::factory()->for($demo)->create();
        $property = Property::factory()->for($project)->create();
        $question = VisitChecklistQuestion::create([
            'category' => 'Quartier',
            'question' => 'Le quartier semble-t-il calme ?',
            'weight' => 1,
            'is_active' => true,
        ]);

        $this->actingAs($demo)
            ->postJson(route('projects.properties.visit.answer', [$project, $property]), [
                'question_id' => $question->id,
                'answer' => 'yes',
            ])
            ->assertStatus(423);

        $this->assertDatabaseMissing('property_checklist_answers', [
            'property_id' => $property->id,
            'visit_checklist_question_id' => $question->id,
        ]);
    }

    public function test_visit_answer_autosave_updates_a_question(): void
    {
        $property = Property::factory()->create();
        $question = VisitChecklistQuestion::create([
            'category' => 'Quartier',
            'question' => 'Le quartier semble-t-il calme ?',
            'weight' => 1,
            'is_active' => true,
        ]);

        $this->actingAs($property->project->user)
            ->postJson(route('projects.properties.visit.answer', [$property->project, $property]), [
                'question_id' => $question->id,
                'answer' => 'yes',
                'comment' => 'calme pendant la visite',
            ])
            ->assertOk()
            ->assertJsonPath('answered_count', 1)
            ->assertJsonPath('total_questions', 1);

        $this->assertDatabaseHas('property_checklist_answers', [
            'property_id' => $property->id,
            'visit_checklist_question_id' => $question->id,
            'answer' => 'yes',
            'comment' => 'calme pendant la visite',
        ]);
    }

    public function test_user_can_update_property_due_diligence_review(): void
    {
        $property = Property::factory()->create();

        $this->actingAs($property->project->user)
            ->patch(route('projects.properties.due-diligence.update', [$property->project, $property]), [
                'items' => [
                    [
                        'key' => 'diagnostics',
                        'status' => 'read',
                        'is_blocking' => false,
                        'note' => 'DDT reçu et relu.',
                    ],
                ],
            ])
            ->assertRedirect(route('projects.properties.show', [$property->project, $property]));

        $this->assertDatabaseHas('property_due_diligence_items', [
            'property_id' => $property->id,
            'key' => 'diagnostics',
            'status' => 'read',
            'is_blocking' => false,
            'note' => 'DDT reçu et relu.',
        ]);
    }
}
