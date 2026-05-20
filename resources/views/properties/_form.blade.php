@csrf
@php($isEditing = $property->exists)

<div class="grid gap-6">
    <section class="ir-panel p-5">
        <p class="text-sm font-black uppercase text-teal-700">Étape 1</p>
        <h2 class="mt-1 text-lg font-black text-slate-950">Infos essentielles</h2>
        <p class="mt-1 text-sm text-slate-600">Titre, ville, prix, surface. Avec ça, tu peux déjà commencer à comparer.</p>
        <div class="mt-4 grid gap-4 md:grid-cols-2">
            <div>
                <x-input-label for="title" value="Titre" />
                <x-text-input id="title" name="title" class="mt-1 block w-full" value="{{ old('title', $property->title) }}" required />
            </div>
            <div>
                <x-input-label for="city" value="Ville" />
                <x-text-input id="city" name="city" class="mt-1 block w-full" value="{{ old('city', $property->city) }}" required />
            </div>
            <div>
                <x-input-label for="property_type" value="Type de bien" />
                <select id="property_type" name="property_type" class="mt-1 block w-full rounded-md border-slate-300 focus:border-teal-600 focus:ring-teal-600">
                    @foreach(['appartement','maison','terrain','autre'] as $value)
                        <option value="{{ $value }}" @selected(old('property_type', $property->property_type) === $value)>{{ ucfirst($value) }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <x-input-label for="transaction_type" value="Transaction" />
                <select id="transaction_type" name="transaction_type" class="mt-1 block w-full rounded-md border-slate-300 focus:border-teal-600 focus:ring-teal-600">
                    @foreach(['achat' => 'Achat', 'location' => 'Location'] as $value => $label)
                        <option value="{{ $value }}" @selected(old('transaction_type', $property->transaction_type) === $value)>{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            @foreach([
                'price' => ['Prix', '1000'],
                'surface' => ['Surface', '1'],
                'rooms' => ['Pièces', '1'],
                'bedrooms' => ['Chambres', '1'],
            ] as $field => [$label, $step])
                <div>
                    <x-input-label :for="$field" :value="$label" />
                    <x-text-input :id="$field" :name="$field" type="number" :step="$step" class="mt-1 block w-full" value="{{ old($field, $property->{$field}) }}" />
                </div>
            @endforeach
        </div>
    </section>

    <section class="ir-panel p-5">
        <p class="text-sm font-black uppercase text-teal-700">Étape 2</p>
        <h2 class="mt-1 text-lg font-black text-slate-950">Critères utiles</h2>
        <p class="mt-1 text-sm text-slate-600">DPE, trajet et stationnement suffisent souvent à faire sortir les premières alertes.</p>
        <div class="mt-4 grid gap-4 md:grid-cols-4">
            <div>
                <x-input-label for="dpe" value="DPE" />
                <select id="dpe" name="dpe" class="mt-1 block w-full rounded-md border-slate-300 focus:border-teal-600 focus:ring-teal-600">
                    @foreach(['A','B','C','D','E','F','G','inconnu'] as $dpe)
                        <option value="{{ $dpe }}" @selected(old('dpe', $property->dpe) === $dpe)>{{ $dpe }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <x-input-label for="commute_minutes" value="Trajet min." />
                <x-text-input id="commute_minutes" name="commute_minutes" type="number" class="mt-1 block w-full" value="{{ old('commute_minutes', $property->commute_minutes) }}" />
            </div>
            <div>
                <x-input-label for="floor" value="Étage" />
                <x-text-input id="floor" name="floor" type="number" class="mt-1 block w-full" value="{{ old('floor', $property->floor) }}" />
            </div>
            <div>
                <x-input-label for="status" value="Statut" />
                <select id="status" name="status" class="mt-1 block w-full rounded-md border-slate-300 focus:border-teal-600 focus:ring-teal-600">
                    @foreach(['nouveau','à_analyser','à_visiter','visité','favori','offre_envisagée','offre_faite','rejeté','archivé'] as $status)
                        <option value="{{ $status }}" @selected(old('status', $property->status) === $status)>{{ str_replace('_', ' ', ucfirst($status)) }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <x-input-label for="listing_url" value="Lien annonce" />
                <x-text-input id="listing_url" name="listing_url" type="url" class="mt-1 block w-full" value="{{ old('listing_url', $property->listing_url) }}" />
            </div>
            <div>
                <x-input-label for="address" value="Adresse" />
                <x-text-input id="address" name="address" class="mt-1 block w-full" value="{{ old('address', $property->address) }}" />
            </div>
        </div>
        <div class="mt-4 grid gap-3 md:grid-cols-3">
            @foreach(['has_garage' => 'Garage', 'has_parking' => 'Parking', 'has_balcony' => 'Balcon', 'has_garden' => 'Jardin', 'has_cellar' => 'Cave', 'has_elevator' => 'Ascenseur'] as $field => $label)
                <label class="flex items-center gap-3 rounded-md border border-slate-200 bg-white p-3 text-sm font-semibold text-slate-700">
                    <input type="checkbox" name="{{ $field }}" value="1" class="rounded border-slate-300 text-teal-700 focus:ring-teal-600" @checked(old($field, $property->{$field}))>
                    {{ $label }}
                </label>
            @endforeach
        </div>
    </section>

    <details class="ir-panel border-amber-200 bg-amber-50/50 p-5" {{ $isEditing ? 'open' : '' }}>
        <summary class="cursor-pointer text-lg font-black text-slate-950">Budget détaillé et coût réel mensuel</summary>
        <p class="mt-2 text-sm text-slate-600">À remplir quand tu veux fiabiliser la décision. Estimation indicative, à confirmer avec une banque, un courtier ou un professionnel.</p>
        <div class="mt-4 grid gap-4 md:grid-cols-4">
            @foreach([
                'monthly_charges' => 'Charges / mois',
                'yearly_property_tax' => 'Taxe foncière / an',
                'estimated_energy_monthly' => 'Énergie / mois',
                'estimated_home_insurance_monthly' => 'Assurance habitation',
                'estimated_loan_insurance_monthly' => 'Assurance emprunteur',
                'estimated_work_cost' => 'Travaux estimés',
                'agency_fees' => 'Frais agence',
                'bank_fees' => 'Frais dossier bancaire',
                'loan_guarantee_fees' => 'Frais garantie prêt',
                'down_payment' => 'Apport',
                'loan_rate' => 'Taux crédit (%)',
                'loan_duration_years' => 'Durée crédit (années)',
            ] as $field => $label)
                <div>
                    <x-input-label :for="$field" :value="$label" />
                    <x-text-input :id="$field" :name="$field" type="number" step="0.01" class="mt-1 block w-full" value="{{ old($field, $property->{$field}) }}" />
                </div>
            @endforeach
        </div>
    </details>

    <details class="ir-panel p-5" {{ $isEditing ? 'open' : '' }}>
        <summary class="cursor-pointer text-lg font-black text-slate-950">Ressenti, photo et notes</summary>
        <div class="mt-4 grid gap-4 md:grid-cols-3">
            <div>
                <x-input-label for="hot_feeling_score" value="Ressenti à chaud /10" />
                <x-text-input id="hot_feeling_score" name="hot_feeling_score" type="number" min="1" max="10" class="mt-1 block w-full" value="{{ old('hot_feeling_score', $property->hot_feeling_score) }}" />
            </div>
            <div>
                <x-input-label for="cold_feeling_score" value="Ressenti après réflexion /10" />
                <x-text-input id="cold_feeling_score" name="cold_feeling_score" type="number" min="1" max="10" class="mt-1 block w-full" value="{{ old('cold_feeling_score', $property->cold_feeling_score) }}" />
            </div>
            <div>
                <x-input-label for="main_photo" value="Photo principale" />
                <input id="main_photo" name="main_photo" type="file" accept="image/jpeg,image/png,image/webp" class="mt-1 block w-full text-sm">
            </div>
        </div>
        @foreach(['description' => 'Description', 'rational_notes' => 'Notes rationnelles', 'emotional_notes' => 'Notes ressenti', 'risk_notes' => 'Risques'] as $field => $label)
            <div class="mt-4">
                <x-input-label :for="$field" :value="$label" />
                <textarea id="{{ $field }}" name="{{ $field }}" rows="3" class="mt-1 block w-full rounded-md border-slate-300 focus:border-teal-600 focus:ring-teal-600">{{ old($field, $property->{$field}) }}</textarea>
            </div>
        @endforeach
    </details>
</div>

@if($errors->any())
    <div class="mt-4 rounded-md bg-red-50 p-4 text-sm text-red-800">Certains champs sont invalides. Vérifie le formulaire.</div>
@endif

<div class="mt-6 flex flex-wrap gap-3">
    <x-primary-button>{{ $submitLabel }}</x-primary-button>
    <a href="{{ route('projects.properties.index', $project) }}" class="ir-action-secondary">Annuler</a>
</div>
