# Déploiement ImmoRadar

GitHub Pages ne peut héberger que la page statique du dossier `docs/`. L'application complète nécessite PHP, une base de données, des sessions, du stockage et l'exécution de commandes Laravel.

## Verdict Coût

Le moins cher possible dépend de ce que tu acceptes comme compromis.

### 0 € durable mais moins direct : Koyeb Free + Supabase Free

Pour viser 0 €/mois :

- Koyeb Free pour le conteneur Laravel ;
- Supabase Free pour PostgreSQL ;
- `DB_CONNECTION=pgsql` et `DB_URL` avec l'URL Postgres Supabase.

C'est le moins cher, mais pas le plus confortable :

- les uploads locaux restent éphémères si tu ne branches pas un stockage objet ;
- il faut configurer deux services ;
- il n'y a pas de garantie sérieuse pour une app que tu utilises tous les jours ;
- les limites gratuites peuvent changer.

### 0 € rapide mais temporaire : Render Free

Render Free avec le `render.yaml` fourni est l'option la plus simple pour une démo portfolio rapide depuis GitHub, mais ce n'est pas une vraie production :

- le web service gratuit peut dormir après inactivité ;
- le filesystem est éphémère, donc les uploads locaux ne sont pas durables ;
- la base Render Postgres gratuite expire après 30 jours ;
- il n'y a pas de sauvegarde sur la base gratuite.

### Petit coût réaliste : Railway

Railway est aujourd'hui le meilleur compromis simplicité/prix pour une petite app utilisée vraiment :

- essai gratuit 30 jours avec crédits ;
- puis plan Free à 1 $/mois selon les limites actuelles ;
- Hobby à 5 $ minimum si tu veux plus de marge.

Pour t'en servir réellement sans te prendre la tête, je choisirais Railway avant Render Free.

## Option 0 € Durable : Koyeb + Supabase

Le repo est prêt grâce au Dockerfile. Sur Koyeb :

1. Créer un service depuis le repo GitHub.
2. Choisir le déploiement Dockerfile.
3. Choisir l'instance Free.
4. Créer un projet Supabase Free.
5. Copier l'URL de connexion Postgres dans `DB_URL`.

Variables minimales :

```env
APP_NAME=ImmoRadar
APP_ENV=production
APP_DEBUG=false
APP_URL=https://ton-service.koyeb.app
APP_KEY=base64:...
REGISTRATION_ACCESS_CODE=un-code-prive
DEMO_LOGIN_ENABLED=true
DEPLOY_SEED_DEMO=true
DB_CONNECTION=pgsql
DB_URL=postgresql://...
LOG_CHANNEL=stderr
QUEUE_CONNECTION=database
SESSION_DRIVER=database
CACHE_STORE=database
FILESYSTEM_DISK=public
```

## Option 0 € Pour Démo : Render Free

Le repo contient `render.yaml`. Depuis Render :

1. New > Blueprint.
2. Connecter `QuentinVG/immoradar`.
3. Laisser Render lire `render.yaml`.
4. Définir les secrets :

```env
APP_KEY=base64:...
REGISTRATION_ACCESS_CODE=un-code-prive
```

Générer la clé localement :

```bash
php artisan key:generate --show
```

Après le premier déploiement, remplacer `APP_URL` par l'URL Render réelle, par exemple :

```env
APP_URL=https://immoradar.onrender.com
```

Le conteneur exécute automatiquement :

```bash
php artisan migrate --force
php artisan db:seed --force
```

Le seed est activé par :

```env
DEPLOY_SEED_DEMO=true
```

À désactiver si tu veux utiliser l'app pour tes propres données sans reseed automatique.

## Option Recommandée Pour Usage Réel : Railway

Railway est plus simple pour une petite app Laravel persistante. Le plan Free démarre par un essai 30 jours, puis le coût plancher annoncé est de 1 $/mois. Le plan Hobby coûte 5 $ minimum et inclut 5 $ de crédit d'usage. Le repo est prêt via Dockerfile.

Étapes :

1. Créer un projet Railway depuis le repo GitHub.
2. Ajouter une base PostgreSQL.
3. Définir les variables :

```env
APP_NAME=ImmoRadar
APP_ENV=production
APP_DEBUG=false
APP_URL=https://ton-service.up.railway.app
APP_KEY=base64:...
REGISTRATION_ACCESS_CODE=un-code-prive
DEMO_LOGIN_ENABLED=true
DEPLOY_SEED_DEMO=true
DB_CONNECTION=pgsql
DB_URL=${{Postgres.DATABASE_URL}}
LOG_CHANNEL=stderr
QUEUE_CONNECTION=database
SESSION_DRIVER=database
CACHE_STORE=database
FILESYSTEM_DISK=public
```

Railway détectera le Dockerfile et lancera le conteneur.

## Option Laravel Native : Laravel Cloud

Laravel Cloud est plus propre côté Laravel, mais pas forcément le moins cher une fois la base et l'usage pris en compte. À choisir si tu veux moins bricoler l'infra.

Variables minimales :

```env
APP_NAME=ImmoRadar
APP_ENV=production
APP_DEBUG=false
APP_URL=https://ton-domaine.example
APP_KEY=base64:...
REGISTRATION_ACCESS_CODE=un-code-prive
DEMO_LOGIN_ENABLED=true
DB_CONNECTION=mysql
FILESYSTEM_DISK=public
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
- Prévoir une sauvegarde régulière de la base si l'app est utilisée réellement.
- Vérifier les logs après chaque déploiement.

## Démo Publique

Le compte démo est accessible en un clic si :

```env
DEMO_LOGIN_ENABLED=true
```

Compte seedé :

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

Ne remplace le lien GitHub Pages par une “démo live” que lorsque l'app Laravel est réellement déployée.
