<?php

namespace App\Services;

use App\Models\Property;

class PropertyDueDiligenceService
{
    /**
     * @return array<int,array{key:string,label:string,why:string,status:string,action:string}>
     */
    public function review(Property $property): array
    {
        $property->loadMissing('checklistAnswers.question');

        $items = [
            $this->item(
                'diagnostics',
                'Dossier de diagnostics',
                'DPE, risques, électricité, gaz, amiante, plomb ou assainissement selon le bien.',
                $this->statusFromQuestion($property, 'diagnostics'),
                'Demander le dossier de diagnostic technique avant toute offre.'
            ),
        ];

        if ($property->transaction_type === 'achat' && $property->property_type === 'maison' && in_array($property->dpe, ['E', 'F', 'G'], true)) {
            $items[] = $this->item(
                'energy_audit',
                'Audit énergétique',
                'Maison classée E, F ou G : les scénarios de travaux doivent être lus avant de juger le prix.',
                $this->statusFromQuestion($property, 'audit énergétique'),
                'Demander l’audit énergétique et vérifier les scénarios de travaux.'
            );
        }

        if ($property->transaction_type === 'achat' && $property->property_type === 'appartement') {
            $items[] = $this->item(
                'coproperty_documents',
                'Documents de copropriété',
                'PV d’assemblée générale, travaux votés, charges, fonds travaux et situation financière.',
                $this->statusFromQuestion($property, 'PV d’AG'),
                'Demander les PV d’AG, travaux votés et informations financières de copropriété.'
            );
        }

        if ($property->transaction_type === 'achat') {
            $items[] = $this->item(
                'tax_and_charges',
                'Charges et taxe foncière',
                'Ces montants changent directement le coût réel mensuel.',
                $property->monthly_charges !== null && $property->yearly_property_tax !== null ? 'confirmed' : 'missing',
                'Confirmer les charges et la taxe foncière annuelle.'
            );
        }

        return $items;
    }

    /**
     * @return array<int,array{key:string,label:string,why:string,status:string,action:string}>
     */
    public function missingItems(Property $property): array
    {
        return array_values(array_filter(
            $this->review($property),
            fn (array $item): bool => $item['status'] !== 'confirmed'
        ));
    }

    private function statusFromQuestion(Property $property, string $needle): string
    {
        $answer = $property->checklistAnswers
            ->first(fn ($answer): bool => $answer->question !== null && str_contains(strtolower($answer->question->question), strtolower($needle)));

        return match ($answer?->answer) {
            'yes' => 'confirmed',
            'no' => 'missing',
            'not_applicable' => 'not_applicable',
            default => 'unknown',
        };
    }

    /**
     * @return array{key:string,label:string,why:string,status:string,action:string}
     */
    private function item(string $key, string $label, string $why, string $status, string $action): array
    {
        return compact('key', 'label', 'why', 'status', 'action');
    }
}
