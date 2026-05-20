<?php

namespace App\Services;

use App\Models\Property;
use App\Models\PropertyDueDiligenceItem;

class PropertyDueDiligenceService
{
    public const STATUSES = ['unknown', 'requested', 'received', 'read', 'missing', 'not_applicable'];

    /**
     * @return array<int,array{key:string,label:string,why:string,status:string,action:string,is_blocking:bool,note:?string}>
     */
    public function review(Property $property): array
    {
        $property->loadMissing('checklistAnswers.question', 'dueDiligenceItems');
        $savedItems = $property->dueDiligenceItems->keyBy('key');

        return collect($this->definitions($property))
            ->map(function (array $definition) use ($savedItems): array {
                /** @var PropertyDueDiligenceItem|null $saved */
                $saved = $savedItems->get($definition['key']);

                if (! $saved instanceof PropertyDueDiligenceItem) {
                    return [
                        ...$definition,
                        'note' => null,
                    ];
                }

                return [
                    ...$definition,
                    'status' => $saved->status,
                    'is_blocking' => (bool) $saved->is_blocking,
                    'note' => $saved->note,
                ];
            })
            ->values()
            ->all();
    }

    /**
     * @param  array<int,array{key:string,status:string,is_blocking:bool,note:?string}>  $items
     */
    public function update(Property $property, array $items): void
    {
        $definitions = collect($this->definitions($property))->keyBy('key');

        foreach ($items as $item) {
            $definition = $definitions->get($item['key']);

            if (! $definition) {
                continue;
            }

            $property->dueDiligenceItems()->updateOrCreate(
                ['key' => $item['key']],
                [
                    'label' => $definition['label'],
                    'why' => $definition['why'],
                    'action' => $definition['action'],
                    'status' => $item['status'],
                    'is_blocking' => $item['is_blocking'],
                    'note' => $item['note'],
                ],
            );
        }
    }

    /**
     * @return array<int,array{key:string,label:string,why:string,status:string,action:string,is_blocking:bool,note:?string}>
     */
    public function missingItems(Property $property): array
    {
        return array_values(array_filter(
            $this->review($property),
            fn (array $item): bool => $item['status'] === 'missing' || ($item['is_blocking'] && ! in_array($item['status'], ['read', 'not_applicable'], true))
        ));
    }

    /**
     * @return array<int,array{key:string,label:string,why:string,status:string,action:string,is_blocking:bool}>
     */
    private function definitions(Property $property): array
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
                $property->monthly_charges !== null && $property->yearly_property_tax !== null ? 'read' : 'missing',
                'Confirmer les charges et la taxe foncière annuelle.'
            );
        }

        $items[] = $this->item(
            'final_visit',
            'Dernière visite avant signature',
            'Vérifier que le logement, les équipements et les éléments inclus sont toujours conformes avant l’acte.',
            'unknown',
            'Prévoir une dernière visite avant la signature de l’acte authentique.',
            false
        );

        return $items;
    }

    private function statusFromQuestion(Property $property, string $needle): string
    {
        $answer = $property->checklistAnswers
            ->first(fn ($answer): bool => $answer->question !== null && str_contains(strtolower($answer->question->question), strtolower($needle)));

        return match ($answer?->answer) {
            'yes' => 'read',
            'no' => 'missing',
            'not_applicable' => 'not_applicable',
            default => 'unknown',
        };
    }

    /**
     * @return array{key:string,label:string,why:string,status:string,action:string,is_blocking:bool}
     */
    private function item(string $key, string $label, string $why, string $status, string $action, bool $isBlocking = true): array
    {
        return [
            'key' => $key,
            'label' => $label,
            'why' => $why,
            'status' => $status,
            'action' => $action,
            'is_blocking' => $isBlocking,
        ];
    }
}
