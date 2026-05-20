<?php

namespace App\Services;

use App\Models\Property;

class PropertyScoringService
{
    public function __construct(
        private readonly PropertyCostCalculator $costCalculator,
        private readonly PropertyDueDiligenceService $dueDiligenceService,
    ) {}

    /**
     * @return array<string, mixed>
     */
    public function score(Property $property): array
    {
        $property->loadMissing('project', 'checklistAnswers.question');

        $solid = $this->solidite($property);
        $projection = $this->projection($property);
        $vigilance = $this->vigilance($property);
        $compatibility = (int) round($solid['score'] * 0.45 + $projection['score'] * 0.35 + (100 - $vigilance['score']) * 0.20);

        return [
            'compatibility' => [
                'score' => $this->bound($compatibility),
                'label' => 'Compatibilité',
                'reasons' => [
                    ...$this->prefixReasons($solid['reasons'], 'Solidité'),
                    ...$this->prefixReasons($projection['reasons'], 'Projection'),
                    ...$this->prefixReasons($vigilance['reasons'], 'Vigilance'),
                ],
            ],
            'solidity' => $solid,
            'projection' => $projection,
            'vigilance' => $vigilance,
            'confidence_level' => $this->confidenceLevel($property),
        ];
    }

    /**
     * @return array{score:int,label:string,reasons:array<int,string>}
     */
    private function solidite(Property $property): array
    {
        $score = 50;
        $reasons = [];
        $cost = $this->costCalculator->calculate($property);
        $project = $property->project;

        if ($project?->max_budget && $property->price) {
            if ((float) $property->price <= (float) $project->max_budget) {
                $score += 12;
                $reasons[] = '+ prix dans le budget';
            } else {
                $score -= 16;
                $reasons[] = '- prix au-dessus du budget';
            }
        }

        if ($project?->target_monthly_cost) {
            if ($cost['real_monthly_cost'] <= (float) $project->target_monthly_cost) {
                $score += 12;
                $reasons[] = '+ coût mensuel dans la cible';
            } else {
                $score -= 14;
                $reasons[] = '- coût mensuel au-dessus de la cible';
            }
        }

        if ($project?->min_surface && $property->surface) {
            if ((float) $property->surface >= (float) $project->min_surface) {
                $score += 8;
                $reasons[] = '+ surface suffisante';
            } else {
                $score -= 10;
                $reasons[] = '- surface sous le minimum visé';
            }
        }

        if ($project?->requires_garage) {
            if ($property->has_garage || $property->has_parking) {
                $score += 8;
                $reasons[] = '+ stationnement présent';
            } else {
                $score -= 14;
                $reasons[] = '- garage ou parking manquant';
            }
        }

        if ($project?->max_commute_minutes && $property->commute_minutes !== null) {
            if ($property->commute_minutes <= $project->max_commute_minutes) {
                $score += 8;
                $reasons[] = '+ trajet acceptable';
            } else {
                $score -= 10;
                $reasons[] = '- trajet trop long';
            }
        }

        if ($project?->min_dpe && $property->dpe !== 'inconnu') {
            if ($this->dpeRank($property->dpe) <= $this->dpeRank($project->min_dpe)) {
                $score += 7;
                $reasons[] = '+ DPE compatible avec le critère';
            } else {
                $score -= 9;
                $reasons[] = '- DPE sous le minimum visé';
            }
        }

        if ($project?->max_work_cost && $property->estimated_work_cost !== null) {
            if ((float) $property->estimated_work_cost <= (float) $project->max_work_cost) {
                $score += 6;
                $reasons[] = '+ travaux dans l’enveloppe';
            } else {
                $score -= 10;
                $reasons[] = '- travaux élevés';
            }
        }

        return ['score' => $this->bound($score), 'label' => 'Solidité', 'reasons' => $reasons ?: ['informations rationnelles encore limitées']];
    }

    /**
     * @return array{score:int,label:string,reasons:array<int,string>}
     */
    private function projection(Property $property): array
    {
        $scores = array_filter([$property->hot_feeling_score, $property->cold_feeling_score], fn ($value) => $value !== null);
        $score = $scores ? (int) round((array_sum($scores) / count($scores)) * 10) : 45;
        $reasons = [];

        if ($property->hot_feeling_score !== null) {
            $reasons[] = $property->hot_feeling_score >= 7 ? '+ bon ressenti à chaud' : '- ressenti à chaud modéré';
        }

        if ($property->cold_feeling_score !== null) {
            $reasons[] = $property->cold_feeling_score >= 7 ? '+ ressenti confirmé après réflexion' : '- projection moins forte après réflexion';
        }

        $positiveFeelingAnswers = $property->checklistAnswers
            ->filter(fn ($answer) => $answer->question?->category === 'Ressenti' && $answer->answer === 'yes')
            ->count();

        $negativeFeelingAnswers = $property->checklistAnswers
            ->filter(fn ($answer) => $answer->question?->category === 'Ressenti' && $answer->answer === 'no')
            ->count();

        $score += $positiveFeelingAnswers * 5;
        $score -= $negativeFeelingAnswers * 7;

        if ($positiveFeelingAnswers > 0) {
            $reasons[] = '+ réponses positives pendant la visite';
        }
        if ($negativeFeelingAnswers > 0) {
            $reasons[] = '- doutes apparus pendant la visite';
        }

        return ['score' => $this->bound($score), 'label' => 'Projection', 'reasons' => $reasons ?: ['ressenti à compléter après visite']];
    }

    /**
     * @return array{score:int,label:string,reasons:array<int,string>}
     */
    private function vigilance(Property $property): array
    {
        $score = 20;
        $reasons = [];
        $cost = $this->costCalculator->calculate($property);
        $project = $property->project;

        if (in_array($property->dpe, ['F', 'G'], true)) {
            $score += 18;
            $reasons[] = '- DPE mauvais';
        } elseif ($property->transaction_type === 'achat' && $property->property_type === 'maison' && $property->dpe === 'E') {
            $score += 8;
            $reasons[] = '- DPE E : audit et travaux à clarifier';
        } elseif ($property->dpe === 'inconnu') {
            $score += 10;
            $reasons[] = '- DPE inconnu';
        }

        foreach ($cost['missing'] as $missing) {
            $score += 4;
            $reasons[] = '- '.$missing.' à confirmer';
        }

        if ($project?->requires_garage && ! $property->has_garage && ! $property->has_parking) {
            $score += 12;
            $reasons[] = '- critère stationnement non respecté';
        }

        if ($project?->target_monthly_cost && $cost['real_monthly_cost'] > (float) $project->target_monthly_cost) {
            $score += 12;
            $reasons[] = '- coût réel mensuel supérieur à la cible';
        }

        if ($project?->max_work_cost && $property->estimated_work_cost !== null && (float) $property->estimated_work_cost > (float) $project->max_work_cost) {
            $score += 10;
            $reasons[] = '- travaux au-dessus du cadre';
        }

        $riskyAnswers = $property->checklistAnswers
            ->filter(fn ($answer) => in_array($answer->answer, ['no', 'unknown'], true) && $answer->question?->category !== 'Ressenti')
            ->count();

        if ($riskyAnswers > 0) {
            $score += min(20, $riskyAnswers * 3);
            $reasons[] = '- points de visite à vérifier';
        }

        $documentAnswersToConfirm = collect($this->dueDiligenceService->missingItems($property))
            ->where('status', '!=', 'not_applicable')
            ->count();

        if ($documentAnswersToConfirm > 0) {
            $score += min(12, $documentAnswersToConfirm * 4);
            $reasons[] = '- documents d’achat à confirmer';
        }

        return ['score' => $this->bound($score), 'label' => 'Vigilance', 'reasons' => $reasons ?: ['pas d’alerte majeure détectée avec les informations actuelles']];
    }

    private function confidenceLevel(Property $property): string
    {
        $knownFields = collect([
            $property->price,
            $property->surface,
            $property->monthly_charges,
            $property->yearly_property_tax,
            $property->estimated_energy_monthly,
            $property->loan_rate,
            $property->loan_duration_years,
            $property->commute_minutes,
        ])->filter(fn ($value) => $value !== null)->count();

        $answeredQuestions = $property->checklistAnswers()->count();

        return match (true) {
            $knownFields >= 7 && $answeredQuestions >= 20 => 'élevée',
            $knownFields >= 5 && $answeredQuestions >= 8 => 'moyenne',
            default => 'faible',
        };
    }

    private function dpeRank(?string $dpe): int
    {
        return Property::DPE_ORDER[$dpe ?: 'inconnu'] ?? 8;
    }

    private function bound(int $score): int
    {
        return max(0, min(100, $score));
    }

    /**
     * @param  array<int,string>  $reasons
     * @return array<int,string>
     */
    private function prefixReasons(array $reasons, string $prefix): array
    {
        return array_map(fn (string $reason): string => $prefix.' : '.$reason, $reasons);
    }
}
