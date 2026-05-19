<!doctype html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <style>
        body { font-family: DejaVu Sans, sans-serif; color: #1f2937; font-size: 12px; }
        h1 { font-size: 24px; margin-bottom: 4px; }
        h2 { font-size: 16px; margin-top: 22px; border-bottom: 1px solid #d1d5db; padding-bottom: 6px; }
        table { width: 100%; border-collapse: collapse; margin-top: 8px; }
        th, td { border: 1px solid #d1d5db; padding: 6px; text-align: left; }
        th { background: #f3f4f6; }
        .muted { color: #6b7280; }
    </style>
</head>
<body>
    <p class="muted">ImmoRadar · Rapport comparatif projet</p>
    <h1>{{ $project->name }}</h1>
    <p>{{ $summary['properties_count'] }} biens comparés · coût mensuel moyen {{ $summary['average_monthly_cost'] ? number_format($summary['average_monthly_cost'], 0, ',', ' ') . ' €' : 'à compléter' }}</p>
    <p class="muted">Estimation indicative, à confirmer avec une banque, un courtier ou un professionnel.</p>

    <h2>Conclusion simple</h2>
    @if($summary['best_property'])
        <p>Meilleur choix rationnel actuel : <strong>{{ $summary['best_property']['property']->title }}</strong>, avec une compatibilité de {{ $summary['best_property']['compatibility'] }}/100.</p>
    @else
        <p>Aucun bien à comparer pour l’instant.</p>
    @endif

    @if($summary['risky_crush'])
        <p>Coup de cœur risqué : <strong>{{ $summary['risky_crush']['property']->title }}</strong>.</p>
    @endif

    <h2>Top 3</h2>
    <table>
        <thead>
            <tr><th>Bien</th><th>Ville</th><th>Coût réel</th><th>Compatibilité</th><th>Vigilance</th><th>Verdict</th></tr>
        </thead>
        <tbody>
            @forelse($summary['top_properties'] as $card)
                <tr>
                    <td>{{ $card['property']->title }}</td>
                    <td>{{ $card['property']->city }}</td>
                    <td>{{ number_format($card['real_monthly_cost'], 0, ',', ' ') }} €/mois</td>
                    <td>{{ $card['compatibility'] }}/100</td>
                    <td>{{ $card['vigilance'] }}/100</td>
                    <td>{{ $card['verdict'] }}</td>
                </tr>
            @empty
                <tr><td colspan="6">Aucun bien.</td></tr>
            @endforelse
        </tbody>
    </table>

    <h2>Alertes principales</h2>
    <ul>
        @forelse($summary['main_alerts'] as $alert)
            <li><strong>{{ $alert->title }}</strong> — {{ $alert->message }}</li>
        @empty
            <li>Pas d’alerte majeure détectée.</li>
        @endforelse
    </ul>
</body>
</html>
