<?php

namespace Tests\Feature;

use App\Models\Project;
use App\Models\Property;
use App\Models\PropertyChecklistAnswer;
use App\Models\VisitChecklistQuestion;
use App\Services\ProjectSummaryService;
use App\Services\PropertyAlertService;
use App\Services\PropertyScoringService;
use App\Services\PropertyVerdictService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DecisionServicesTest extends TestCase
{
    use RefreshDatabase;

    public function test_scoring_rewards_budget_respected(): void
    {
        $project = Project::factory()->create(['max_budget' => 180000, 'target_monthly_cost' => 1100]);
        $property = Property::factory()->for($project)->create(['price' => 150000]);

        $scores = app(PropertyScoringService::class)->score($property);

        $this->assertGreaterThanOrEqual(60, $scores['solidity']['score']);
        $this->assertContains('+ prix dans le budget', $scores['solidity']['reasons']);
    }

    public function test_scoring_penalizes_missing_required_garage(): void
    {
        $project = Project::factory()->create(['requires_garage' => true]);
        $property = Property::factory()->for($project)->create(['has_garage' => false, 'has_parking' => false]);

        $scores = app(PropertyScoringService::class)->score($property);

        $this->assertLessThan(65, $scores['solidity']['score']);
        $this->assertContains('- garage ou parking manquant', $scores['solidity']['reasons']);
    }

    public function test_alerts_detect_bad_dpe_and_missing_charges(): void
    {
        $property = Property::factory()->create(['dpe' => 'G', 'monthly_charges' => null]);

        $alerts = collect(app(PropertyAlertService::class)->evaluate($property));

        $this->assertTrue($alerts->contains('type', 'bad_dpe'));
        $this->assertTrue($alerts->contains('type', 'missing_charges'));
    }

    public function test_alerts_detect_energy_audit_needed_for_house_dpe_e(): void
    {
        $property = Property::factory()->create([
            'property_type' => 'maison',
            'transaction_type' => 'achat',
            'dpe' => 'E',
        ]);

        $alerts = collect(app(PropertyAlertService::class)->evaluate($property));

        $this->assertTrue($alerts->contains('type', 'energy_audit'));
    }

    public function test_alerts_detect_documents_to_confirm_from_visit_answers(): void
    {
        $property = Property::factory()->create();
        $question = VisitChecklistQuestion::create([
            'category' => 'Documents',
            'question' => 'Le dossier de diagnostics est-il disponible ?',
            'weight' => 2,
            'is_active' => true,
        ]);

        PropertyChecklistAnswer::create([
            'property_id' => $property->id,
            'visit_checklist_question_id' => $question->id,
            'answer' => 'unknown',
        ]);

        $alerts = collect(app(PropertyAlertService::class)->evaluate($property));

        $this->assertTrue($alerts->contains('type', 'documents_to_confirm'));
    }

    public function test_verdict_detects_risky_crush(): void
    {
        $project = Project::factory()->create(['target_monthly_cost' => 700]);
        $property = Property::factory()->for($project)->create([
            'hot_feeling_score' => 10,
            'cold_feeling_score' => 9,
            'price' => 220000,
            'dpe' => 'G',
            'monthly_charges' => null,
            'yearly_property_tax' => null,
            'estimated_work_cost' => 30000,
        ]);

        $verdict = app(PropertyVerdictService::class)->verdict($property);

        $this->assertSame('Coup de cœur risqué', $verdict['title']);
    }

    public function test_project_summary_exposes_decision_readiness(): void
    {
        $project = Project::factory()->create();
        Property::factory()->count(2)->for($project)->create(['monthly_charges' => null]);

        $summary = app(ProjectSummaryService::class)->summarize($project);

        $this->assertArrayHasKey('decision_readiness', $summary);
        $this->assertSame('Trop tôt pour décider', $summary['decision_readiness']['label']);
        $this->assertNotEmpty($summary['decision_readiness']['actions']);
    }
}
