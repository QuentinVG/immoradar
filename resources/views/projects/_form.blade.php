@csrf

<div class="grid gap-6">
    <section class="rounded-lg border border-slate-200 bg-white p-5">
        <h2 class="text-lg font-semibold text-slate-900">Projet</h2>
        <div class="mt-4 grid gap-4 md:grid-cols-2">
            <div>
                <x-input-label for="name" value="Nom du projet" />
                <x-text-input id="name" name="name" class="mt-1 block w-full" value="{{ old('name', $project->name) }}" required />
                <x-input-error :messages="$errors->get('name')" class="mt-2" />
            </div>
            <div>
                <x-input-label for="type" value="Type" />
                <select id="type" name="type" class="mt-1 block w-full rounded-md border-slate-300">
                    @foreach(['achat' => 'Achat', 'location' => 'Location', 'investissement' => 'Investissement'] as $value => $label)
                        <option value="{{ $value }}" @selected(old('type', $project->type) === $value)>{{ $label }}</option>
                    @endforeach
                </select>
            </div>
        </div>
    </section>

    <section class="rounded-lg border border-slate-200 bg-white p-5">
        <h2 class="text-lg font-semibold text-slate-900">Critères utiles</h2>
        <div class="mt-4 grid gap-4 md:grid-cols-3">
            <div>
                <x-input-label for="max_budget" value="Budget max" />
                <x-text-input id="max_budget" name="max_budget" type="number" step="1000" class="mt-1 block w-full" value="{{ old('max_budget', $project->max_budget) }}" />
            </div>
            <div>
                <x-input-label for="target_monthly_cost" value="Mensualité cible" />
                <x-text-input id="target_monthly_cost" name="target_monthly_cost" type="number" step="50" class="mt-1 block w-full" value="{{ old('target_monthly_cost', $project->target_monthly_cost) }}" />
            </div>
            <div>
                <x-input-label for="min_surface" value="Surface min." />
                <x-text-input id="min_surface" name="min_surface" type="number" step="1" class="mt-1 block w-full" value="{{ old('min_surface', $project->min_surface) }}" />
            </div>
            <div>
                <x-input-label for="reference_location" value="Lieu de référence" />
                <x-text-input id="reference_location" name="reference_location" class="mt-1 block w-full" value="{{ old('reference_location', $project->reference_location) }}" />
            </div>
            <div>
                <x-input-label for="max_commute_minutes" value="Trajet max. en minutes" />
                <x-text-input id="max_commute_minutes" name="max_commute_minutes" type="number" class="mt-1 block w-full" value="{{ old('max_commute_minutes', $project->max_commute_minutes) }}" />
            </div>
            <div>
                <x-input-label for="max_work_cost" value="Travaux max." />
                <x-text-input id="max_work_cost" name="max_work_cost" type="number" step="500" class="mt-1 block w-full" value="{{ old('max_work_cost', $project->max_work_cost) }}" />
            </div>
            <div>
                <x-input-label for="min_dpe" value="DPE minimum" />
                <select id="min_dpe" name="min_dpe" class="mt-1 block w-full rounded-md border-slate-300">
                    <option value="">Pas de minimum</option>
                    @foreach(['A','B','C','D','E','F','G'] as $dpe)
                        <option value="{{ $dpe }}" @selected(old('min_dpe', $project->min_dpe) === $dpe)>{{ $dpe }}</option>
                    @endforeach
                </select>
            </div>
            <label class="flex items-center gap-3 rounded-md border border-slate-200 p-3 text-sm font-medium text-slate-700">
                <input type="checkbox" name="requires_garage" value="1" class="rounded border-slate-300" @checked(old('requires_garage', $project->requires_garage))>
                Garage ou parking obligatoire
            </label>
        </div>
    </section>

    <section class="rounded-lg border border-slate-200 bg-white p-5">
        <x-input-label for="notes" value="Notes" />
        <textarea id="notes" name="notes" rows="4" class="mt-1 block w-full rounded-md border-slate-300">{{ old('notes', $project->notes) }}</textarea>
    </section>
</div>

<div class="mt-6 flex flex-wrap gap-3">
    <x-primary-button>{{ $submitLabel }}</x-primary-button>
    <a href="{{ route('projects.index') }}" class="inline-flex items-center rounded-md border border-slate-300 px-4 py-2 text-sm font-semibold text-slate-700">Annuler</a>
</div>
