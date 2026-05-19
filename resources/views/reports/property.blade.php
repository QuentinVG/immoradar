<!doctype html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <style>
        body { font-family: DejaVu Sans, sans-serif; color: #1f2937; font-size: 12px; }
        h1 { font-size: 24px; margin-bottom: 4px; }
        h2 { font-size: 16px; margin-top: 22px; border-bottom: 1px solid #d1d5db; padding-bottom: 6px; }
        .muted { color: #6b7280; }
        .box { border: 1px solid #d1d5db; padding: 10px; margin-top: 8px; }
        .grid { display: table; width: 100%; }
        .col { display: table-cell; width: 25%; padding: 6px; }
        ul { margin-top: 6px; }
    </style>
</head>
<body>
    <p class="muted">ImmoRadar · Rapport d’aide à la décision</p>
    <h1>{{ $property->title }}</h1>
    <p>{{ $property->city }} · {{ $property->surface ?: '—' }} m² · {{ $property->price ? number_format((float) $property->price, 0, ',', ' ') . ' €' : 'Prix à compléter' }}</p>

    <div class="box">
        <h2>Verdict : {{ $verdict['title'] }}</h2>
        <p>{{ $verdict['summary'] }}</p>
    </div>

    <h2>Coût réel mensuel</h2>
    <p><strong>{{ number_format($cost['real_monthly_cost'], 0, ',', ' ') }} €/mois</strong> {{ $cost['is_partial'] ? '(coût partiel estimé)' : '' }}</p>
    <p class="muted">{{ $cost['notice'] }}</p>

    <h2>Scores expliqués</h2>
    <div class="grid">
        @foreach(['compatibility' => 'Compatibilité', 'solidity' => 'Solidité', 'projection' => 'Projection', 'vigilance' => 'Vigilance'] as $key => $label)
            <div class="col"><strong>{{ $label }}</strong><br>{{ $scores[$key]['score'] }}/100</div>
        @endforeach
    </div>

    <h2>Points forts</h2>
    <ul>@foreach($verdict['strengths'] as $item)<li>{{ $item }}</li>@endforeach</ul>

    <h2>Points de vigilance</h2>
    <ul>@foreach($verdict['watch_points'] as $item)<li>{{ $item }}</li>@endforeach</ul>

    <h2>Alertes</h2>
    <ul>
        @forelse($property->alerts as $alert)
            <li><strong>{{ $alert->title }}</strong> — {{ $alert->message }}</li>
        @empty
            <li>Pas d’alerte majeure détectée.</li>
        @endforelse
    </ul>

    <h2>Checklist de visite</h2>
    <ul>
        @forelse($property->checklistAnswers as $answer)
            <li>{{ $answer->question?->question }} : {{ $answer->answer }}</li>
        @empty
            <li>Checklist non renseignée.</li>
        @endforelse
    </ul>

    <h2>Prochaines actions</h2>
    <ul>@foreach($verdict['next_actions'] as $action)<li>{{ $action }}</li>@endforeach</ul>
</body>
</html>
