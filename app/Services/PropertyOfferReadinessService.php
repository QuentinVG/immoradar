<?php

namespace App\Services;

use App\Models\Property;
use App\Models\VisitChecklistQuestion;
use Illuminate\Support\Collection;

class PropertyOfferReadinessService
{
    public function __construct(
        private readonly PropertyCostCalculator $costCalculator,
        private readonly PropertyScoringService $scoringService,
        private readonly PropertyAlertService $alertService,
        private readonly PropertyDueDiligenceService $dueDiligenceService,
    ) {}

    /**
     * @return array{score:int,label:string,summary:string,proof_level:string,checklist_progress:int,blockers:array<int,string>,next_actions:array<int,string>,conditions:array<int,string>}
     */
    public function review(Property $property): array
    {
        $property->loadMissing('project', 'checklistAnswers.question');

        $score = 100;
        $blockers = [];
        $nextActions = [];
        $cost = $this->costCalculator->calculate($property);
        $scores = $this->scoringService->score($property);
        $alerts = collect($this->alertService->evaluate($property));
        $dueDiligence = collect($this->dueDiligenceService->missingItems($property))
            ->where('status', '!=', 'not_applicable')
            ->values();
        $checklistProgress = $this->checklistProgress($property);

        if ($cost['is_partial']) {
            $score -= 24;
            $blockers[] = 'Coût réel mensuel incomplet';
            $nextActions[] = 'Compléter les informations financières manquantes : '.implode(', ', $cost['missing']).'.';
        }

        if ($dueDiligence->isNotEmpty()) {
            $score -= min(28, $dueDiligence->count() * 9);
            $blockers[] = 'Documents avant offre incomplets';
            $nextActions[] = 'Récupérer les documents bloquants : '.$dueDiligence->pluck('label')->implode(', ').'.';
        }

        $dangerCount = $alerts->where('severity', 'danger')->count();
        if ($dangerCount > 0) {
            $score -= min(28, $dangerCount * 14);
            $blockers[] = 'Alerte danger non traitée';
        }

        if ($scores['vigilance']['score'] >= 60) {
            $score -= 18;
            $blockers[] = 'Vigilance trop élevée';
        }

        if ($scores['compatibility']['score'] < 55) {
            $score -= 14;
            $blockers[] = 'Compatibilité insuffisante';
        }

        if ($checklistProgress < 70) {
            $score -= 14;
            $nextActions[] = 'Finir au moins 70 % de la checklist avant de parler d’offre.';
        }

        $score = max(0, min(100, $score));

        return [
            'score' => $score,
            'label' => $this->label($score, $blockers),
            'summary' => $this->summary($score, $blockers),
            'proof_level' => $this->proofLevel($cost['is_partial'], $dueDiligence->count(), $checklistProgress),
            'checklist_progress' => $checklistProgress,
            'blockers' => array_values(array_unique($blockers)),
            'next_actions' => array_values(array_unique($nextActions)) ?: ['Comparer ce bien avec une alternative avant de formuler une offre.'],
            'conditions' => $this->conditions($property, $alerts, $dueDiligence),
        ];
    }

    private function checklistProgress(Property $property): int
    {
        $activeQuestions = VisitChecklistQuestion::query()->where('is_active', true)->count();

        if ($activeQuestions === 0) {
            return 100;
        }

        $answeredQuestions = $property->checklistAnswers
            ->where('answer', '!=', 'unknown')
            ->count();

        return min(100, (int) round(($answeredQuestions / $activeQuestions) * 100));
    }

    /**
     * @param  array<int,string>  $blockers
     */
    private function label(int $score, array $blockers): string
    {
        return match (true) {
            $score >= 82 && $blockers === [] => 'Prêt pour offre',
            $score >= 55 => 'À sécuriser avant offre',
            default => 'Pas prêt pour offre',
        };
    }

    /**
     * @param  array<int,string>  $blockers
     */
    private function summary(int $score, array $blockers): string
    {
        if ($score >= 82 && $blockers === []) {
            return 'Les informations clés sont assez solides pour préparer une offre prudente.';
        }

        if ($score >= 55) {
            return 'Le bien peut rester intéressant, mais il manque encore des preuves avant de s’engager.';
        }

        return 'Le risque de décider trop vite est trop élevé avec les informations actuelles.';
    }

    private function proofLevel(bool $costIsPartial, int $missingDocuments, int $checklistProgress): string
    {
        return match (true) {
            ! $costIsPartial && $missingDocuments === 0 && $checklistProgress >= 80 => 'solide',
            ! $costIsPartial && $missingDocuments === 0 && $checklistProgress >= 50 => 'moyen',
            default => 'faible',
        };
    }

    /**
     * @param  Collection<int,array<string,string>>  $alerts
     * @param  Collection<int,array{key:string,label:string,why:string,status:string,action:string}>  $dueDiligence
     * @return array<int,string>
     */
    private function conditions(Property $property, Collection $alerts, Collection $dueDiligence): array
    {
        if ($property->transaction_type !== 'achat') {
            return ['Validation du bail et des conditions d’entrée dans le logement.'];
        }

        $conditions = ['Obtention du prêt immobilier.'];

        if ($alerts->contains('type', 'high_works') || $property->estimated_work_cost === null || (float) $property->estimated_work_cost > 0) {
            $conditions[] = 'Chiffrage des travaux avant engagement définitif.';
        }

        if ($dueDiligence->contains('key', 'energy_audit')) {
            $conditions[] = 'Réception et lecture de l’audit énergétique.';
        }

        if ($dueDiligence->contains('key', 'coproperty_documents')) {
            $conditions[] = 'Analyse des documents de copropriété et travaux votés.';
        }

        if ($dueDiligence->contains('key', 'diagnostics')) {
            $conditions[] = 'Réception du dossier de diagnostics.';
        }

        return array_values(array_unique($conditions));
    }
}
