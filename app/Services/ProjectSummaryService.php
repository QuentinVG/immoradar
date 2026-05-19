<?php

namespace App\Services;

use App\Models\Project;
use App\Models\Property;
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
}
