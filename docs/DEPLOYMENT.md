# Déploiement ImmoRadar

GitHub Pages ne peut héberger que la page statique du dossier `docs/`. L'application complète nécessite PHP, une base de données, des sessions, du stockage et l'exécution de commandes Laravel.

## Option Recommandée

Pour une mise en ligne simple, choisir Laravel Cloud ou Railway.

### Laravel Cloud

Adapté à Laravel, peu d'infrastructure à gérer.

Variables à définir :

```env
APP_NAME=ImmoRadar
APP_ENV=production
APP_DEBUG=false
APP_URL=https://ton-domaine.example
APP_KEY=base64:...
REGISTRATION_ACCESS_CODE=un-code-prive
DB_CONNECTION=mysql
FILESYSTEM_DISK=public
LOG_CHANNEL=stderr
QUEUE_CONNECTION=database
SESSION_DRIVER=database
CACHE_STORE=database
```

Commandes de déploiement :

```bash
composer install --no-dev --optimize-autoloader
npm ci
npm run build
php artisan migrate --force
php artisan storage:link
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

### Railway

Railway peut déployer Laravel depuis GitHub et fournir une base de données. Prévoir une URL publique uniquement pour le service web.

Variables à définir :

```env
APP_ENV=production
APP_DEBUG=false
APP_URL=https://ton-service.up.railway.app
APP_KEY=base64:...
REGISTRATION_ACCESS_CODE=un-code-prive
LOG_CHANNEL=stderr
QUEUE_CONNECTION=database
SESSION_DRIVER=database
CACHE_STORE=database
```

## Sécurité Avant Mise En Ligne

- Garder `REGISTRATION_ACCESS_CODE` obligatoire.
- Ne jamais mettre `APP_DEBUG=true` en public.
- Utiliser un mot de passe fort pour la base.
- Garder le compte `demo@immoradar.test` en lecture seule.
- Vérifier que les uploads restent limités aux images.
- Faire tourner `php artisan migrate --force` au déploiement.
- Prévoir une sauvegarde régulière de la base.
- Vérifier les logs après chaque déploiement.

## Démo Publique

Le compte démo est utile pour un portfolio, mais il doit rester non destructif.

Compte :

```text
demo@immoradar.test
password
```

Ce compte peut lire les projets et biens de démonstration, mais les écritures sont bloquées par `PreventDemoWrites`.

## Après Déploiement

Mettre à jour le portfolio avec :

- URL de l'application complète ;
- URL GitHub Pages de présentation ;
- URL du dépôt GitHub.

Ne remplacer le lien GitHub Pages par une “démo live” que lorsque l'app Laravel est réellement déployée.
