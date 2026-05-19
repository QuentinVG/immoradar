<?php

namespace Tests\Feature;

use App\Models\Project;
use App\Models\Property;
use App\Models\User;
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
}
