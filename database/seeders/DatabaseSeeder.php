<?php

namespace Database\Seeders;

use App\Models\Project;
use App\Models\Property;
use App\Models\User;
use App\Models\VisitChecklistQuestion;
use App\Services\PropertyAlertService;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->seedChecklistQuestions();

        $user = User::updateOrCreate(
            ['email' => 'demo@immoradar.test'],
            ['name' => 'Utilisateur démo', 'password' => Hash::make('password'), 'email_verified_at' => now()],
        );

        $project = Project::updateOrCreate(
            ['user_id' => $user->id, 'name' => 'Achat résidence principale autour de Valence'],
            [
                'type' => 'achat',
                'max_budget' => 180000,
                'target_monthly_cost' => 1000,
                'reference_location' => 'Valence',
                'max_commute_minutes' => 35,
                'min_surface' => 60,
                'requires_garage' => true,
                'max_work_cost' => 15000,
                'min_dpe' => 'D',
                'notes' => 'Priorités : calme, garage, trajet boulot, peu de travaux.',
            ],
        );

        foreach ($this->demoProperties() as $data) {
            $property = Property::updateOrCreate(
                ['project_id' => $project->id, 'title' => $data['title']],
                $data,
            );

            app(PropertyAlertService::class)->refresh($property);
        }
    }

    private function seedChecklistQuestions(): void
    {
        $questions = [
            'Quartier' => [
                'Le quartier semble-t-il calme ?',
                'Le stationnement est-il simple ?',
                'Le trajet vers le travail est-il acceptable ?',
                'L’environnement semble-t-il rassurant ?',
                'Les commerces utiles sont-ils proches ?',
            ],
            'Immeuble / extérieur' => [
                'Les parties communes sont-elles propres ?',
                'La façade semble-t-elle en bon état ?',
                'Le garage ou parking est-il pratique ?',
                'L’accès au logement est-il agréable ?',
            ],
            'Intérieur' => [
                'La luminosité est-elle suffisante ?',
                'La disposition des pièces est-elle pratique ?',
                'Les fenêtres semblent-elles en bon état ?',
                'L’isolation sonore semble-t-elle correcte ?',
                'Y a-t-il assez de rangements ?',
            ],
            'Technique' => [
                'Le tableau électrique semble-t-il correct ?',
                'Le chauffage semble-t-il adapté ?',
                'Y a-t-il des traces d’humidité ?',
                'Y a-t-il des fissures inquiétantes ?',
                'La ventilation semble-t-elle correcte ?',
            ],
            'Budget' => [
                'Les charges sont-elles connues ?',
                'La taxe foncière est-elle connue ?',
                'Les travaux semblent-ils maîtrisables ?',
                'Le DPE est-il acceptable ?',
            ],
            'Documents' => [
                'Le dossier de diagnostics est-il disponible ?',
                'En copropriété, les PV d’AG et travaux votés ont-ils été consultés ?',
                'Si le DPE est E, F ou G, l’audit énergétique est-il disponible ?',
            ],
            'Ressenti' => [
                'Est-ce que je me projette dans ce logement ?',
                'Ai-je envie d’y retourner ?',
                'Le logement me semble-t-il confortable ?',
                'Le ressenti est-il toujours bon après réflexion ?',
            ],
        ];

        foreach ($questions as $category => $items) {
            foreach ($items as $question) {
                VisitChecklistQuestion::updateOrCreate(
                    ['question' => $question],
                    [
                        'category' => $category,
                        'help_text' => $this->questionHelp($question),
                        'weight' => in_array($category, ['Technique', 'Documents'], true) ? 2 : 1,
                        'is_active' => true,
                    ],
                );
            }
        }
    }

    private function questionHelp(string $question): ?string
    {
        return match ($question) {
            'Y a-t-il des traces d’humidité ?' => 'Regarde angles, plafonds, bas de murs, odeurs et joints autour des fenêtres.',
            'Y a-t-il des fissures inquiétantes ?' => 'Une fissure large, traversante ou récente doit être clarifiée avant offre.',
            'Le tableau électrique semble-t-il correct ?' => 'Si doute : demander diagnostic électricité et budget de remise aux normes.',
            'Le dossier de diagnostics est-il disponible ?' => 'Sans DDT, la décision reste fragile même si la visite est positive.',
            'En copropriété, les PV d’AG et travaux votés ont-ils été consultés ?' => 'Lis les travaux votés, impayés, litiges et gros entretiens prévus.',
            'Si le DPE est E, F ou G, l’audit énergétique est-il disponible ?' => 'Indispensable pour juger les travaux énergétiques et la négociation.',
            'Les charges sont-elles connues ?' => 'Demande le montant exact et ce qu’il inclut.',
            'La taxe foncière est-elle connue ?' => 'À intégrer au coût mensuel réel.',
            default => null,
        };
    }

    /**
     * @return array<int,array<string,mixed>>
     */
    private function demoProperties(): array
    {
        return [
            [
                'title' => 'Appartement T3 Bourg-lès-Valence',
                'city' => 'Bourg-lès-Valence',
                'property_type' => 'appartement',
                'transaction_type' => 'achat',
                'price' => 158000,
                'surface' => 67,
                'rooms' => 3,
                'bedrooms' => 2,
                'dpe' => 'C',
                'monthly_charges' => 110,
                'yearly_property_tax' => 920,
                'estimated_energy_monthly' => 95,
                'estimated_home_insurance_monthly' => 18,
                'estimated_loan_insurance_monthly' => 28,
                'estimated_work_cost' => 5000,
                'agency_fees' => 0,
                'bank_fees' => 900,
                'loan_guarantee_fees' => 1800,
                'down_payment' => 20000,
                'loan_rate' => 3.7,
                'loan_duration_years' => 20,
                'has_garage' => true,
                'commute_minutes' => 18,
                'status' => 'favori',
                'hot_feeling_score' => 7,
                'cold_feeling_score' => 7,
                'rational_notes' => 'Bon compromis, copropriété lisible.',
            ],
            [
                'title' => 'Maison Montoison',
                'city' => 'Montoison',
                'property_type' => 'maison',
                'transaction_type' => 'achat',
                'price' => 172000,
                'surface' => 82,
                'rooms' => 4,
                'bedrooms' => 3,
                'dpe' => 'D',
                'monthly_charges' => 0,
                'yearly_property_tax' => 1050,
                'estimated_energy_monthly' => 145,
                'estimated_home_insurance_monthly' => 28,
                'estimated_loan_insurance_monthly' => 31,
                'estimated_work_cost' => 14000,
                'agency_fees' => 0,
                'bank_fees' => 950,
                'loan_guarantee_fees' => 2100,
                'down_payment' => 18000,
                'loan_rate' => 3.8,
                'loan_duration_years' => 22,
                'has_garage' => true,
                'has_garden' => true,
                'commute_minutes' => 34,
                'status' => 'à_visiter',
                'hot_feeling_score' => 9,
                'cold_feeling_score' => 8,
                'emotional_notes' => 'Très bonne projection, maison agréable.',
                'risk_notes' => 'Travaux à chiffrer sérieusement.',
            ],
            [
                'title' => 'Appartement Guilherand-Granges',
                'city' => 'Guilherand-Granges',
                'property_type' => 'appartement',
                'transaction_type' => 'achat',
                'price' => 149000,
                'surface' => 62,
                'rooms' => 3,
                'bedrooms' => 2,
                'dpe' => 'C',
                'monthly_charges' => 95,
                'yearly_property_tax' => 840,
                'estimated_energy_monthly' => 90,
                'estimated_home_insurance_monthly' => 18,
                'estimated_loan_insurance_monthly' => 25,
                'estimated_work_cost' => 3000,
                'agency_fees' => 0,
                'bank_fees' => 850,
                'loan_guarantee_fees' => 1700,
                'down_payment' => 20000,
                'loan_rate' => 3.6,
                'loan_duration_years' => 20,
                'has_garage' => false,
                'has_parking' => false,
                'commute_minutes' => 42,
                'status' => 'à_analyser',
                'hot_feeling_score' => 5,
                'cold_feeling_score' => 5,
                'rational_notes' => 'Prix cohérent, mais moins adapté aux critères.',
            ],
            [
                'title' => 'Maison Alixan',
                'city' => 'Alixan',
                'property_type' => 'maison',
                'transaction_type' => 'achat',
                'price' => 185000,
                'surface' => 90,
                'rooms' => 4,
                'bedrooms' => 3,
                'dpe' => 'E',
                'monthly_charges' => 0,
                'yearly_property_tax' => null,
                'estimated_energy_monthly' => 170,
                'estimated_home_insurance_monthly' => 30,
                'estimated_loan_insurance_monthly' => 33,
                'estimated_work_cost' => 18000,
                'agency_fees' => 0,
                'bank_fees' => 950,
                'loan_guarantee_fees' => 2200,
                'down_payment' => 18000,
                'loan_rate' => 3.9,
                'loan_duration_years' => 22,
                'has_garage' => true,
                'has_garden' => true,
                'commute_minutes' => 28,
                'status' => 'à_analyser',
                'hot_feeling_score' => 7,
                'cold_feeling_score' => 6,
                'risk_notes' => 'Hors budget et DPE moyen.',
            ],
        ];
    }
}
