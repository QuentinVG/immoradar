<?php

use App\Http\Controllers\CompareController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\PropertyController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\VisitChecklistController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

$marketingPages = [
    'checklist-visite-immobiliere' => [
        'title' => 'Checklist visite immobilière - Questions à vérifier',
        'description' => 'Prépare une visite immobilière avec une checklist simple : quartier, intérieur, technique, budget, ressenti et points à confirmer avant une offre.',
        'eyebrow' => 'Guide visite',
        'h1' => 'Checklist visite immobilière : quoi vérifier avant de s’emballer ?',
        'intro' => 'Une visite réussie ne sert pas seulement à savoir si le bien plaît. Elle doit aussi faire ressortir ce qui peut coûter cher, gêner au quotidien ou rendre la décision trop fragile.',
        'sections' => [
            ['title' => 'Avant la visite', 'body' => 'Note le budget cible, le trajet acceptable, les critères non négociables et les questions à poser sur les charges, la taxe foncière, les travaux et le DPE.'],
            ['title' => 'Pendant la visite', 'body' => 'Regarde la lumière, le bruit, l’humidité, les fissures, la ventilation, l’état des fenêtres, l’électricité, les communs et le stationnement.'],
            ['title' => 'Après la visite', 'body' => 'Reprends le ressenti à froid, complète les informations manquantes et compare le bien avec au moins deux alternatives avant de parler d’offre.'],
        ],
    ],
    'cout-reel-mensuel-immobilier' => [
        'title' => 'Coût réel mensuel immobilier - Estimation complète',
        'description' => 'Comprends le coût réel mensuel d’un bien immobilier : crédit, charges, taxe foncière, énergie, assurance, travaux et informations manquantes.',
        'eyebrow' => 'Guide budget',
        'h1' => 'Le prix affiché ne suffit pas : calcule le coût réel mensuel.',
        'intro' => 'Deux biens au même prix peuvent avoir un impact très différent chaque mois. Le coût réel mensuel remet le crédit, les charges et les frais récurrents au même endroit.',
        'sections' => [
            ['title' => 'Ce qu’il faut additionner', 'body' => 'Mensualité de crédit, charges, taxe foncière divisée par douze, énergie estimée, assurance habitation, assurance emprunteur et travaux lissés.'],
            ['title' => 'Ce qui rend l’estimation fragile', 'body' => 'Charges inconnues, taxe foncière absente, travaux mal chiffrés, DPE mauvais ou taux de crédit non confirmé.'],
            ['title' => 'Limite importante', 'body' => 'Toute estimation financière reste indicative et doit être confirmée avec une banque, un courtier ou un professionnel.'],
        ],
    ],
    'comparer-biens-immobiliers' => [
        'title' => 'Comparer des biens immobiliers sans décider au feeling',
        'description' => 'Compare plusieurs biens immobiliers avec des critères simples : coût mensuel, surface, trajet, DPE, projection, vigilance et verdict.',
        'eyebrow' => 'Guide comparaison',
        'h1' => 'Comparer plusieurs biens évite de surévaluer le dernier coup de cœur.',
        'intro' => 'Un bien peut sembler évident juste après la visite. La comparaison force à remettre chaque option sur les mêmes critères et à expliquer pourquoi elle ressort.',
        'sections' => [
            ['title' => 'Comparer peu, mais bien', 'body' => 'Trois ou quatre biens suffisent souvent pour voir les compromis : budget, trajet, surface, état, DPE, stationnement et projection.'],
            ['title' => 'Séparer envie et risque', 'body' => 'Un bon comparateur doit distinguer la projection personnelle du niveau de vigilance. Un bien très séduisant peut rester trop risqué.'],
            ['title' => 'Décider au bon moment', 'body' => 'Si trop d’informations manquent, la bonne décision est souvent de sécuriser les données avant de faire une offre.'],
        ],
    ],
    'documents-achat-immobilier' => [
        'title' => 'Documents achat immobilier - Diagnostics, copropriété et audit',
        'description' => 'Liste les documents à demander avant une offre immobilière : diagnostics, DPE, audit énergétique, charges, PV d’AG, travaux votés et informations de copropriété.',
        'eyebrow' => 'Guide documents',
        'h1' => 'Les documents à demander avant d’acheter un bien immobilier.',
        'intro' => 'Une visite peut donner confiance, mais les documents confirment ce que le vendeur affirme. Diagnostics, copropriété, travaux et charges doivent être vérifiés avant de s’engager.',
        'sections' => [
            ['title' => 'Diagnostics obligatoires', 'body' => 'Demande le dossier de diagnostic technique : DPE, plomb, amiante, gaz, électricité, risques, termites ou assainissement selon le bien et sa localisation.'],
            ['title' => 'Audit énergétique', 'body' => 'Pour une maison classée E, F ou G, vérifie si l’audit énergétique est disponible et lis les scénarios de travaux avant de juger le prix.'],
            ['title' => 'Copropriété', 'body' => 'Pour un appartement, demande les charges, les PV d’assemblée générale, les travaux votés, le fonds travaux et les informations financières de la copropriété.'],
        ],
    ],
];

Route::get('/', function () {
    return Auth::check()
        ? redirect()->route('projects.index')
        : view('marketing.home');
})->name('marketing.home');

foreach ($marketingPages as $slug => $page) {
    Route::get('/guides/'.$slug, fn () => view('marketing.page', [
        'page' => $page,
        'slug' => $slug,
    ]))->name('marketing.guides.'.$slug);
}

Route::get('/robots.txt', function () {
    $url = rtrim(config('app.url'), '/');

    return response(
        "User-agent: *\n".
        "Allow: /\n".
        "Sitemap: {$url}/sitemap.xml\n",
        200,
        ['Content-Type' => 'text/plain; charset=UTF-8']
    );
})->name('robots');

Route::get('/sitemap.xml', function () {
    $url = e(rtrim(config('app.url'), '/'));
    $guideUrls = collect([
        'checklist-visite-immobiliere',
        'cout-reel-mensuel-immobilier',
        'comparer-biens-immobiliers',
        'documents-achat-immobilier',
    ])->map(fn (string $slug): string => "  <url>\n    <loc>{$url}/guides/{$slug}</loc>\n    <priority>0.8</priority>\n  </url>")
        ->implode("\n");

    return response(
        <<<XML
<?xml version="1.0" encoding="UTF-8"?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
  <url>
    <loc>{$url}/</loc>
    <priority>1.0</priority>
  </url>
{$guideUrls}
</urlset>
XML,
        200,
        ['Content-Type' => 'application/xml; charset=UTF-8']
    );
})->name('sitemap');

Route::get('/dashboard', function () {
    return redirect()->route('projects.index');
})->middleware('auth')->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::resource('projects', ProjectController::class);
    Route::get('projects/{project}/compare', CompareController::class)->name('projects.compare');
    Route::get('projects/{project}/report', [ReportController::class, 'project'])->name('projects.report');
    Route::resource('projects.properties', PropertyController::class);
    Route::get('projects/{project}/properties/{property}/visit', [VisitChecklistController::class, 'edit'])->name('projects.properties.visit');
    Route::post('projects/{project}/properties/{property}/visit', [VisitChecklistController::class, 'update'])->name('projects.properties.visit.update');
    Route::post('projects/{project}/properties/{property}/visit/answer', [VisitChecklistController::class, 'updateAnswer'])->name('projects.properties.visit.answer');
    Route::get('projects/{project}/properties/{property}/report', [ReportController::class, 'property'])->name('projects.properties.report');
});

require __DIR__.'/auth.php';
