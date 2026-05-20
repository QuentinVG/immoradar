<?php

namespace App\Services;

use App\Models\Property;

class PropertyAlertService
{
    public function __construct(
        private readonly PropertyCostCalculator $costCalculator,
        private readonly PropertyScoringService $scoringService,
    ) {}

    /**
     * @return array<int,array<string,string>>
     */
    public function evaluate(Property $property): array
    {
        $property->loadMissing('project', 'checklistAnswers.question');

        $alerts = [];
        $project = $property->project;
        $cost = $this->costCalculator->calculate($property);
        $scores = $this->scoringService->score($property);

        if ($project?->max_budget && $property->price && (float) $property->price > (float) $project->max_budget) {
            $alerts[] = $this->alert('budget_exceeded', 'danger', 'Budget dépassé', 'Le prix du bien dépasse le budget maximum du projet.');
        }

        if ($project?->target_monthly_cost && $cost['real_monthly_cost'] > (float) $project->target_monthly_cost) {
            $alerts[] = $this->alert('monthly_cost_high', 'warning', 'Coût mensuel au-dessus de la cible', 'Le coût réel estimé dépasse la mensualité cible.');
        }

        if ($property->monthly_charges === null) {
            $alerts[] = $this->alert('missing_charges', 'warning', 'Charges inconnues', 'Demande les charges mensuelles avant de décider.');
        }

        if ($property->yearly_property_tax === null && $property->transaction_type === 'achat') {
            $alerts[] = $this->alert('missing_tax', 'warning', 'Taxe foncière inconnue', 'La taxe foncière peut changer sensiblement le coût mensuel.');
        }

        if ($project?->max_work_cost && $property->estimated_work_cost !== null && (float) $property->estimated_work_cost > (float) $project->max_work_cost) {
            $alerts[] = $this->alert('high_works', 'warning', 'Travaux élevés', 'Les travaux estimés dépassent le cadre prévu.');
        }

        if (in_array($property->dpe, ['F', 'G'], true)) {
            $alerts[] = $this->alert('bad_dpe', 'danger', 'DPE mauvais', 'Le DPE peut peser sur le confort, les travaux et la revente.');
        }

        if ($property->transaction_type === 'achat' && $property->property_type === 'maison' && in_array($property->dpe, ['E', 'F', 'G'], true)) {
            $alerts[] = $this->alert('energy_audit', 'warning', 'Audit énergétique à demander', 'Pour une maison classée E, F ou G, vérifie que l’audit énergétique est disponible avant décision.');
        }

        if ($project?->requires_garage && ! $property->has_garage && ! $property->has_parking) {
            $alerts[] = $this->alert('missing_garage', 'danger', 'Stationnement manquant', 'Le projet demande un garage ou parking, mais ce bien n’en a pas.');
        }

        if ($project?->max_commute_minutes && $property->commute_minutes !== null && $property->commute_minutes > $project->max_commute_minutes) {
            $alerts[] = $this->alert('long_commute', 'warning', 'Trajet trop long', 'Le temps de trajet dépasse le maximum souhaité.');
        }

        if ($scores['projection']['score'] >= 75 && $scores['vigilance']['score'] >= 60) {
            $alerts[] = $this->alert('risky_crush', 'danger', 'Coup de cœur risqué', 'Ce bien te plaît beaucoup, mais plusieurs risques importants sont détectés.');
        }

        if (count($cost['missing']) >= 3) {
            $alerts[] = $this->alert('missing_information', 'warning', 'Informations importantes manquantes', 'Complète les données clés pour fiabiliser la comparaison.');
        }

        $documentAnswersToConfirm = $property->checklistAnswers
            ->filter(fn ($answer): bool => $answer->question?->category === 'Documents' && in_array($answer->answer, ['no', 'unknown'], true))
            ->count();

        if ($documentAnswersToConfirm > 0) {
            $alerts[] = $this->alert('documents_to_confirm', 'warning', 'Documents à vérifier', 'Diagnostics, copropriété ou audit énergétique restent à confirmer avant une offre.');
        }

        if ($scores['solidity']['score'] < 35) {
            $alerts[] = $this->alert('required_criteria_failed', 'danger', 'Critère obligatoire non respecté', 'Un ou plusieurs critères rationnels importants ne sont pas respectés.');
        }

        return $alerts;
    }

    public function refresh(Property $property): void
    {
        $property->alerts()->where('is_resolved', false)->delete();

        foreach ($this->evaluate($property) as $alert) {
            $property->alerts()->create($alert);
        }
    }

    /**
     * @return array{type:string,severity:string,title:string,message:string}
     */
    private function alert(string $type, string $severity, string $title, string $message): array
    {
        return compact('type', 'severity', 'title', 'message');
    }
}
