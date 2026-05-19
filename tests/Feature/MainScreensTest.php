<?php

namespace Tests\Feature;

use App\Models\Property;
use App\Models\VisitChecklistQuestion;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MainScreensTest extends TestCase
{
    use RefreshDatabase;

    public function test_main_authenticated_screens_render(): void
    {
        VisitChecklistQuestion::create([
            'category' => 'Ressenti',
            'question' => 'Est-ce que je me projette dans ce logement ?',
            'weight' => 1,
            'is_active' => true,
        ]);

        /** @var Property $property */
        $property = Property::factory()->create();
        $user = $property->project->user;

        $this->actingAs($user)->get(route('projects.index'))->assertOk();
        $this->actingAs($user)->get(route('projects.show', $property->project))->assertOk();
        $this->actingAs($user)->get(route('projects.properties.index', $property->project))->assertOk();
        $this->actingAs($user)->get(route('projects.properties.show', [$property->project, $property]))->assertOk();
        $this->actingAs($user)->get(route('projects.properties.visit', [$property->project, $property]))->assertOk();
        $this->actingAs($user)->get(route('projects.compare', $property->project))->assertOk();
    }
}
