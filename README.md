# ImmoRadar

ImmoRadar est un assistant de visite et de décision immobilière anti-coup de cœur.

L'objectif est simple : comparer plusieurs biens, préparer les visites, estimer le coût réel mensuel et décider avec moins d'émotion et plus de repères concrets.

L'application n'utilise pas d'IA, ne scrape aucune annonce et ne se connecte pas à Leboncoin, SeLoger ou autre plateforme. L'utilisateur ajoute ses biens manuellement.

## Présentation

ImmoRadar répond à un problème fréquent : pendant une recherche immobilière, il est facile de tomber amoureux d'un bien avant d'avoir vérifié le budget, les travaux, le trajet, les charges ou les risques techniques.

Le parcours V1 tient en six étapes :

1. Créer un projet de recherche.
2. Ajouter des biens manuellement.
3. Préparer une visite.
4. Remplir une checklist mobile.
5. Lire le coût réel mensuel, les scores et les alertes.
6. Comparer les biens et exporter un rapport PDF.

## Problème Résolu

ImmoRadar aide à éviter les décisions immobilières prises uniquement au ressenti. Le produit transforme des informations dispersées en une synthèse lisible :

- coût réel mensuel estimé ;
- score de compatibilité ;
- solidité rationnelle ;
- projection personnelle ;
- vigilance ;
- verdict clair ;
- alertes et prochaines actions.

Les calculs financiers sont volontairement affichés comme indicatifs.

> Estimation indicative, à confirmer avec une banque, un courtier ou un professionnel.

## Fonctionnalités

- Authentification Laravel Breeze.
- Isolation stricte des projets et biens par utilisateur.
- Création, édition et suppression de projets immobiliers.
- Création, édition, suppression et statut des biens.
- Upload d'une photo principale par bien.
- Calcul du prix au mètre carré, frais de notaire, financement, mensualité de crédit et coût réel mensuel.
- Checklist de visite mobile-friendly avec environ 30 questions maximum.
- Scores expliqués : compatibilité, solidité, projection, vigilance, confiance.
- Alertes automatiques : budget dépassé, DPE mauvais, charges inconnues, trajet long, garage manquant, coup de cœur risqué, etc.
- Verdict automatique avec points forts, points de vigilance et prochaines actions.
- Dashboard projet : meilleur bien, coup de cœur risqué, top 3, informations manquantes.
- Comparateur de biens avec tris utiles.
- Rapport PDF par bien.
- Rapport PDF comparatif du projet.
- Données de démonstration réalistes.

## Stack Technique

- PHP 8.3+
- Laravel 13
- MySQL ou MariaDB
- Blade
- Tailwind CSS
- Alpine.js via Breeze
- Laravel Breeze
- PHPUnit
- DomPDF
- Docker / docker-compose
- GitHub Actions
- Laravel Pint
- Larastan / PHPStan

## Captures D'écran

Les captures peuvent être placées dans `docs/screenshots/`.

Pages prévues :

- dashboard projet ;
- fiche bien ;
- mode visite mobile ;
- comparateur ;
- rapport PDF.

## Installation Locale

Pré-requis :

- PHP 8.3 ou plus ;
- Composer ;
- Node.js et npm ;
- MySQL ou MariaDB.

```bash
composer install
npm install
cp .env.example .env
php artisan key:generate
php artisan storage:link
php artisan migrate --seed
npm run build
php artisan serve
```

Application locale :

```text
http://127.0.0.1:8000
```

## Lancer Avec Docker

```bash
cp .env.example .env
docker compose up --build
docker compose exec app php artisan key:generate
docker compose exec app php artisan storage:link
docker compose exec app php artisan migrate --seed
```

Application :

```text
http://127.0.0.1:8000
```

Vite :

```text
http://127.0.0.1:5173
```

## Base De Données

Les tables principales :

- `projects`
- `properties`
- `visit_checklist_questions`
- `property_checklist_answers`
- `property_alerts`
- tables Breeze / Laravel : users, sessions, cache, jobs

Les tests utilisent SQLite en mémoire via `phpunit.xml`. L'application locale et Docker sont configurés pour MySQL/MariaDB.

## Compte Démo

```text
Email: demo@immoradar.test
Mot de passe: password
```

## Inscription Et Anti-Spam

L'inscription peut être protégée par un code d'accès privé :

```env
REGISTRATION_ACCESS_CODE=un-code-prive
```

Quand cette variable est définie, le formulaire d'inscription demande le code. La V1 ajoute aussi :

- rate limit sur la route `POST /register` ;
- honeypot invisible pour bloquer les bots basiques ;
- délai minimum avant soumission du formulaire.

Ce choix est volontairement simple : pas de CAPTCHA externe, pas de service tiers, pas de friction inutile pour une instance personnelle.

## GitHub Pages

GitHub Pages ne peut pas héberger directement l'application Laravel complète, car elle nécessite PHP, une base de données, des sessions et du stockage.

Le dossier `docs/` contient donc une page statique de présentation publiable ici :

```text
https://quentinvg.github.io/immoradar/
```

Pour utiliser l'application complète, lance-la localement ou déploie-la sur un hébergement compatible Laravel.

## Déploiement Public

La checklist de déploiement est disponible dans [`docs/DEPLOYMENT.md`](docs/DEPLOYMENT.md).

À retenir avant mise en ligne :

- `APP_DEBUG=false` ;
- `REGISTRATION_ACCESS_CODE` obligatoire ;
- compte démo en lecture seule ;
- base de données sauvegardée ;
- hébergement compatible Laravel, par exemple Laravel Cloud, Railway ou VPS.

Le seeder crée un projet :

```text
Achat résidence principale autour de Valence
```

avec quatre biens de démonstration :

- Appartement T3 Bourg-lès-Valence ;
- Maison Montoison ;
- Appartement Guilherand-Granges ;
- Maison Alixan.

## Tests

```bash
php artisan test
```

Les tests couvrent notamment :

- calcul de mensualité de crédit ;
- calcul de coût réel mensuel ;
- scoring budget respecté ;
- scoring garage obligatoire manquant ;
- alerte DPE mauvais ;
- alerte charges inconnues ;
- verdict coup de cœur risqué ;
- accès interdit aux projets d'un autre utilisateur ;
- validation upload photo ;
- génération rapport PDF ;
- rendu des écrans principaux.

## Qualité Code

Formatage :

```bash
vendor/bin/pint
```

Vérification du formatage :

```bash
vendor/bin/pint --test
```

Analyse statique :

```bash
vendor/bin/phpstan analyse --memory-limit=1G
```

Suite complète :

```bash
composer quality
```

## Choix Techniques

- Controllers minces : orchestration HTTP seulement.
- Services métier dédiés :
  - `PropertyCostCalculator`
  - `PropertyScoringService`
  - `PropertyAlertService`
  - `PropertyVerdictService`
  - `ProjectSummaryService`
- Form Requests pour la validation.
- Policies pour isoler strictement les données utilisateur.
- Un seul upload photo pour garder la V1 simple.
- Saisie manuelle des biens pour éviter scraping, dépendances externes et complexité inutile.
- PDF via DomPDF pour une génération simple côté Laravel.

## Limites Assumées

- Les calculs financiers sont indicatifs.
- Aucun calcul fiscal avancé.
- Pas de comparaison automatique avec les prix du marché.
- Pas d'import automatique d'annonces.
- Pas de carte ni calcul de trajet en temps réel.
- Pas de galerie photo.
- Pas de partage public ou lecture seule.
- Les critères de scoring sont simples, lisibles et testables plutôt que prétendument exhaustifs.

## Roadmap

Fonctionnalités volontairement documentées mais exclues de la V1 :

- extraction automatique depuis un texte d'annonce ;
- carte et estimation trajet ;
- partage lecture seule à un proche ;
- ressenti à chaud/froid avancé ;
- simulation de négociation ;
- documents PDF diagnostics / PV AG ;
- mode duel avancé ;
- critères personnalisables avancés ;
- notifications ;
- application mobile native.

## Présentation Portfolio

Ce projet montre :

- un produit utile et compréhensible ;
- une architecture Laravel maintenable ;
- une séparation nette entre UI, validation, sécurité et logique métier ;
- des tests sur les règles critiques ;
- un README exploitable par une personne qui découvre le repo ;
- une V1 finie plutôt qu'une liste de fonctionnalités incomplètes.
