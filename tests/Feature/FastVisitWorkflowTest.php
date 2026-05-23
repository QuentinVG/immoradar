<?php

namespace Tests\Feature;

use App\Models\Project;
use App\Models\Property;
use App\Models\VisitChecklistQuestion;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FastVisitWorkflowTest extends TestCase
{
    use RefreshDatabase;

    public function test_property_can_be_created_with_minimal_express_payload(): void
    {
        $project = Project::factory()->create();

        $this->actingAs($project->user)
            ->post(route('projects.properties.store', $project), [
                'title' => 'Studio repere',
                'city' => 'Lyon',
            ])
            ->assertRedirect();

        $this->assertDatabaseHas('properties', [
            'project_id' => $project->id,
            'title' => 'Studio repere',
            'city' => 'Lyon',
            'property_type' => 'appartement',
            'transaction_type' => 'achat',
            'dpe' => 'inconnu',
            'status' => 'nouveau',
        ]);
    }

    public function test_visit_opens_in_express_mode_and_full_mode_reveals_all_questions(): void
    {
        $property = Property::factory()->create();
        $this->seedVisitQuestions();

        $this->actingAs($property->project->user)
            ->get(route('projects.properties.visit', [$property->project, $property]))
            ->assertOk()
            ->assertSee('Visite express')
            ->assertSee('>8 questions</h2>', false)
            ->assertDontSee('Les commerces utiles sont-ils proches ?');

        $this->actingAs($property->project->user)
            ->get(route('projects.properties.visit', [$property->project, $property, 'mode' => 'full']))
            ->assertOk()
            ->assertSee('Visite complète')
            ->assertSee('>9 questions</h2>', false)
            ->assertSee('Les commerces utiles sont-ils proches ?');
    }

    public function test_unanswered_critical_visit_questions_count_as_missing(): void
    {
        $property = Property::factory()->create();

        VisitChecklistQuestion::create([
            'category' => 'Technique',
            'question' => 'Y a-t-il des traces d’humidité ?',
            'weight' => 2,
            'is_active' => true,
        ]);

        VisitChecklistQuestion::create([
            'category' => 'Documents',
            'question' => 'Le dossier de diagnostics est-il disponible ?',
            'weight' => 2,
            'is_active' => true,
        ]);

        $this->actingAs($property->project->user)
            ->get(route('projects.properties.visit', [$property->project, $property]))
            ->assertOk()
            ->assertSee('initialCriticalMissingCount: 2', false);
    }

    public function test_express_visit_fills_to_eight_questions_when_a_priority_question_is_missing(): void
    {
        $property = Property::factory()->create();

        foreach ([
            ['Quartier', 'Le trajet vers le travail est-il acceptable ?', 1],
            ['Intérieur', 'La luminosité est-elle suffisante ?', 1],
            ['Technique', 'Y a-t-il des traces d’humidité ?', 2],
            ['Technique', 'Y a-t-il des fissures inquiétantes ?', 2],
            ['Budget', 'Les travaux semblent-ils maîtrisables ?', 1],
            ['Documents', 'Le dossier de diagnostics est-il disponible ?', 2],
            ['Ressenti', 'Est-ce que je me projette dans ce logement ?', 1],
            ['Quartier', 'Le quartier semble-t-il calme ?', 1],
            ['Quartier', 'Les commerces utiles sont-ils proches ?', 1],
        ] as [$category, $question, $weight]) {
            VisitChecklistQuestion::create([
                'category' => $category,
                'question' => $question,
                'weight' => $weight,
                'is_active' => true,
            ]);
        }

        $this->actingAs($property->project->user)
            ->get(route('projects.properties.visit', [$property->project, $property]))
            ->assertOk()
            ->assertSee('Visite express')
            ->assertSee('>8 questions</h2>', false);
    }

    private function seedVisitQuestions(): void
    {
        foreach ([
            ['Quartier', 'Le trajet vers le travail est-il acceptable ?', 1],
            ['Intérieur', 'La luminosité est-elle suffisante ?', 1],
            ['Intérieur', 'L’isolation sonore semble-t-elle correcte ?', 1],
            ['Technique', 'Y a-t-il des traces d’humidité ?', 2],
            ['Technique', 'Y a-t-il des fissures inquiétantes ?', 2],
            ['Budget', 'Les travaux semblent-ils maîtrisables ?', 1],
            ['Documents', 'Le dossier de diagnostics est-il disponible ?', 2],
            ['Ressenti', 'Est-ce que je me projette dans ce logement ?', 1],
            ['Quartier', 'Les commerces utiles sont-ils proches ?', 1],
        ] as [$category, $question, $weight]) {
            VisitChecklistQuestion::create([
                'category' => $category,
                'question' => $question,
                'weight' => $weight,
                'is_active' => true,
            ]);
        }
    }
}
