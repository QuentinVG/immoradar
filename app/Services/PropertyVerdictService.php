<?php

namespace App\Services;

use App\Models\Property;

class PropertyVerdictService
{
    public function __construct(
        private readonly PropertyScoringService $scoringService,
        private readonly PropertyAlertService $alertService,
    ) {}

    /**
     * @return array<string,mixed>
     */
    public function verdict(Property $property): array
    {
        $scores = $this->scoringService->score($property);
        $alerts = $this->alertService->evaluate($property);

        $compatibility = $scores['compatibility']['score'];
        $projection = $scores['projection']['score'];
        $vigilance = $scores['vigilance']['score'];
        $solidity = $scores['solidity']['score'];
        $dangerCount = collect($alerts)->where('severity', 'danger')->count();

        [$title, $summary] = match (true) {
            $projection >= 75 && $vigilance >= 60 => ['Coup de cœur risqué', 'Le bien donne envie, mais les risques demandent une vraie vérification.'],
            $vigilance >= 75 || $dangerCount >= 2 => ['Trop risqué pour l’instant', 'Plusieurs signaux invitent à sécuriser ou écarter ce bien.'],
            $scores['confidence_level'] === 'faible' => ['Informations insuffisantes', 'Il manque encore trop d’éléments pour décider calmement.'],
            $compatibility >= 75 && $vigilance < 45 => ['Bon candidat', 'Le bien coche plusieurs critères sans alerte majeure.'],
            $solidity >= 70 && $projection < 45 => ['Rationnel mais peu engageant', 'Le bien est cohérent sur le papier, mais la projection reste faible.'],
            $compatibility >= 55 => ['Bien intéressant, mais à sécuriser', 'Le bien mérite d’être gardé, avec quelques points à confirmer.'],
            $compatibility < 40 => ['À écarter', 'Le compromis semble faible avec les informations actuelles.'],
            default => ['Informations insuffisantes', 'Complète les données clés avant de comparer sérieusement.'],
        };

        return [
            'title' => $title,
            'summary' => $summary,
            'strengths' => $this->positiveReasons($scores),
            'watch_points' => $this->watchPoints($scores, $alerts),
            'next_actions' => $this->nextActions($property, $alerts),
        ];
    }

    /**
     * @param  array<string,mixed>  $scores
     * @return array<int,string>
     */
    private function positiveReasons(array $scores): array
    {
        return collect([$scores['solidity']['reasons'], $scores['projection']['reasons']])
            ->flatten()
            ->filter(fn (string $reason) => str_starts_with($reason, '+'))
            ->take(5)
            ->values()
            ->all() ?: ['Aucun point fort confirmé pour l’instant.'];
    }

    /**
     * @param  array<string,mixed>  $scores
     * @param  array<int,array<string,string>>  $alerts
     * @return array<int,string>
     */
    private function watchPoints(array $scores, array $alerts): array
    {
        $points = collect($alerts)->pluck('title');

        return $points
            ->merge(collect($scores['vigilance']['reasons'])->filter(fn (string $reason) => str_contains($reason, '-')))
            ->take(6)
            ->values()
            ->all() ?: ['Pas de point de vigilance majeur détecté.'];
    }

    /**
     * @param  array<int,array<string,string>>  $alerts
     * @return array<int,string>
     */
    private function nextActions(Property $property, array $alerts): array
    {
        $actions = collect($alerts)->map(fn (array $alert): string => match ($alert['type']) {
            'missing_charges' => 'Demander le montant exact des charges.',
            'missing_tax' => 'Demander la taxe foncière annuelle.',
            'bad_dpe' => 'Clarifier les travaux énergétiques possibles.',
            'energy_audit' => 'Demander l’audit énergétique et ses scénarios de travaux.',
            'documents_to_confirm' => 'Récupérer les diagnostics, PV d’AG et documents de copropriété utiles.',
            'high_works' => 'Faire chiffrer les travaux avant offre.',
            'missing_garage' => 'Vérifier les alternatives de stationnement.',
            'long_commute' => 'Tester le trajet en conditions réelles.',
            default => 'Vérifier : '.$alert['title'].'.',
        });

        if ($property->checklistAnswers()->count() < 20) {
            $actions->push('Finir la checklist de visite.');
        }

        return $actions->unique()->take(6)->values()->all() ?: ['Comparer avec au moins deux autres biens.'];
    }
}
