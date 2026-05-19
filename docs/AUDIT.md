# Audit ImmoRadar

Date : 2026-05-19

## Audit Fonctionnel

Statut : validé.

- Authentification Breeze opérationnelle.
- Projets immobiliers CRUD.
- Biens immobiliers CRUD avec photo principale.
- Calcul du coût réel mensuel.
- Checklist de visite mobile-friendly.
- Inscription protégée par code d'accès optionnel, honeypot et rate limit.
- Scores expliqués : compatibilité, solidité, projection, vigilance.
- Alertes automatiques.
- Verdict automatique.
- Comparateur projet.
- Rapport PDF par bien.
- Rapport PDF projet.
- Seeders de démonstration.

Smoke test HTTP authentifié validé :

- `/projects`
- `/projects/1`
- `/projects/1/properties`
- `/projects/1/compare`
- `/projects/1/properties/1/visit`
- `/projects/1/properties/1/report`
- `/projects/1/report`

## Audit UX

Statut : validé pour une V1.

- La fiche bien commence par le verdict, puis le coût, les scores, les alertes et les détails.
- Le dashboard projet met en avant le meilleur bien, les risques et les informations à compléter.
- Le mode visite utilise de gros choix rapides adaptés mobile.
- Le mode visite rappelle clairement que les réponses sont modifiables puis enregistrées avec le bouton en bas.
- L'interface a été retravaillée pour être moins administrative : couleurs par usage, actions prioritaires, cartes plus lisibles.
- Le vocabulaire reste simple et non marketing.

Limite assumée : la vue duel avancée n'est pas développée en V1. Elle est documentée en roadmap.

## Audit Sécurité

Statut : validé.

- Accès aux projets et biens protégé par policies.
- Les données sont isolées par utilisateur.
- Validation via Form Requests.
- Upload photo limité aux images `jpg`, `jpeg`, `png`, `webp`, 4 Mo maximum.
- Stockage local Laravel via disque public.
- CSRF géré par Blade/Laravel.
- Test d'accès interdit à un projet d'un autre utilisateur.
- Rate limit dédié sur `POST /register`.
- Code d'accès privé possible via `REGISTRATION_ACCESS_CODE`.
- Honeypot invisible et délai minimum de soumission sur l'inscription.

## Audit Technique

Statut : validé.

- Controllers minces.
- Règles métier placées dans des services dédiés.
- Migrations explicites.
- Seeders réalistes.
- Vues Blade sans logique métier lourde.
- Larastan niveau 5 à 0 erreur.
- Pint à 0 erreur.

Corrections effectuées pendant audit :

- typage Eloquent renforcé pour Larastan ;
- configuration PHPStan simplifiée ;
- flux Breeze de vérification email corrigé ;
- `composer.lock` synchronisé ;
- `.env.example` aligné avec ImmoRadar et MySQL ;
- lien storage public généré localement.
- refonte UI des pages clés : login, register, projets, dashboard projet, biens, fiche bien, comparaison, mode visite.
- page statique GitHub Pages ajoutée dans `docs/index.html`.

## Audit Tests

Statut : validé.

Résultat final :

- PHPUnit : 36 tests passés, 92 assertions.
- Pint : passé.
- PHPStan/Larastan : 0 erreur.
- Build Vite : passé.
- Composer validate : passé.

## Audit Portfolio

Statut : validé.

- README complet.
- Compte démo disponible.
- Données démo réalistes.
- Docker et GitHub Actions présents.
- Roadmap documentée sans surcharger la V1.
- Produit compréhensible rapidement par une personne qui découvre le repo.
- Page GitHub Pages statique prévue pour présenter ImmoRadar : `https://quentinvg.github.io/immoradar/`.

## Réaudit

Après corrections, aucun blocage restant n'a été détecté.

Limites restantes assumées :

- calculs financiers indicatifs ;
- pas d'import automatique d'annonces ;
- pas de carte ou calcul de trajet ;
- pas de galerie photo ;
- pas de mode duel avancé ;
- Docker non exécuté localement sur cette machine, car Docker n'est pas installé.
- GitHub Pages ne peut pas héberger l'application Laravel complète ; la page `docs/` sert de vitrine statique.
