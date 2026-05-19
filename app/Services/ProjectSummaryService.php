<?php

namespace App\Services;

use App\Models\Project;
use App\Models\Property;
use App\Models\VisitChecklistQuestion;
use Illuminate\Support\Collection;

class ProjectSummaryService
{
    public function __construct(
        private readonly PropertyCostCalculator $costCalculator,
        private readonly PropertyScoringService $scoringService,
        private readonly PropertyVerdictService $verdictService,
    ) {}

    /**
     * @return array<string,mixed>
     */
    public function summarize(Project $project): array
    {
        $properties = $project->properties()->with(['alerts', 'checklistAnswers.question'])->get();
        $cards = $properties->map(fn (Property $property): array => $this->propertyCard($property));
        $top = $cards->sortByDesc('compatibility')->take(3)->values();
        $riskyCrush = $cards
            ->filter(fn (array $card): bool => $card['projection'] >= 75 && $card['vigilance'] >= 60)
            ->sortByDesc('projection')
            ->first();

        return [
            'properties_count' => $properties->count(),
            'best_property' => $top->first(),
            'risky_crush' => $riskyCrush,
            'missing_information_count' => $cards->filter(fn (array $card): bool => $card['is_partial'])->count(),
            'average_monthly_cost' => $this->average($cards->pluck('real_monthly_cost')),
            'top_properties' => $top,
            'main_alerts' => $properties
                ->flatMap(fn (Property $property) => $property->alerts)
                ->where('is_resolved', false)
                ->take(5)
                ->values(),
            'decision_readiness' => $this->decisionReadiness($properties, $cards),
        ];
    }

    /**
     * @return array<string,mixed>
     */
    public function propertyCard(Property $property): array
    {
        $scores = $this->scoringService->score($property);
        $cost = $this->costCalculator->calculate($property);
        $verdict = $this->verdictService->verdict($property);

        return [
            'property' => $property,
            'compatibility' => $scores['compatibility']['score'],
            'solidity' => $scores['solidity']['score'],
            'projection' => $scores['projection']['score'],
            'vigilance' => $scores['vigilance']['score'],
            'confidence_level' => $scores['confidence_level'],
            'real_monthly_cost' => $cost['real_monthly_cost'],
            'is_partial' => $cost['is_partial'],
            'verdict' => $verdict['title'],
        ];
    }

    /**
     * @param  Collection<int,float|int|string|null>  $values
     */
    private function average(Collection $values): ?float
    {
        $filtered = $values->filter(fn ($value) => $value > 0);

        if ($filtered->isEmpty()) {
            return null;
        }

        return round($filtered->avg(), 2);
    }

    /**
     * @param  Collection<int,Property>  $properties
     * @param  Collection<int,array<string,mixed>>  $cards
     * @return array{score:int,label:string,checklist_progress:int,actions:array<int,string>}
     */
    private function decisionReadiness(Collection $properties, Collection $cards): array
    {
        if ($properties->isEmpty()) {
            return [
                'score' => 0,
                'label' => 'Pas encore prêt',
                'checklist_progress' => 0,
                'actions' => ['Ajouter au moins deux biens pour commencer à comparer.'],
            ];
        }

        $score = 100;
        $actions = [];
        $activeQuestions = max(1, VisitChecklistQuestion::query()->where('is_active', true)->count());
        $answeredQuestions = $properties->sum(fn (Property $property): int => $property->checklistAnswers->where('answer', '!=', 'unknown')->count());
        $expectedAnswers = max(1, $properties->count() * $activeQuestions);
        $checklistProgress = (int) round(($answeredQuestions / $expectedAnswers) * 100);
        $missingCount = $cards->filter(fn (array $card): bool => $card['is_partial'])->count();
        $dangerCount = $properties
            ->flatMap(fn (Property $property) => $property->alerts)
            ->where('severity', 'danger')
            ->count();

        if ($properties->count() < 3) {
            $score -= 24;
            $actions[] = 'Ajouter un troisième bien pour éviter de décider trop vite.';
        }

        if ($missingCount > 0) {
            $score -= min(28, $missingCount * 9);
            $actions[] = 'Compléter les coûts et informations manquantes des biens suivis.';
        }

        if ($checklistProgress < 50) {
            $score -= 22;
            $actions[] = 'Remplir au moins la moitié des checklists avant de trancher.';
        }

        if ($dangerCount > 0) {
            $score -= min(22, $dangerCount * 8);
            $actions[] = 'Traiter les alertes danger avant toute offre.';
        }

        $score = max(0, min(100, $score));

        return [
            'score' => $score,
            'label' => match (true) {
                $score >= 75 => 'Décision assez mûre',
                $score >= 45 => 'Encore à sécuriser',
                default => 'Trop tôt pour décider',
            },
            'checklist_progress' => $checklistProgress,
            'actions' => $actions ?: ['Comparer le meilleur bien avec une alternative avant de décider.'],
        ];
    }
}
