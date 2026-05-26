# Déployer todo_mvc sur Render (guide détaillé)

Ce guide décrit **étape par étape** le déploiement de votre application Laravel sur [Render](https://render.com), avec une base de données accessible depuis le cloud.

---

## Vue d'ensemble

```text
GitHub (code)  →  Render Web Service (PHP/Laravel)  →  Base MySQL ou PostgreSQL (cloud)
                         ↑
                   Variables d'environnement (.env de prod)
```

| Composant | Rôle |
|-----------|------|
| **GitHub** | Héberge le code source (sans le fichier `.env`) |
| **Render Web Service** | Exécute PHP, lance Laravel, expose l’URL publique `https://....onrender.com` |
| **Base distante** | Stocke la table `taches` (et tables Laravel : sessions, cache, etc.) |

> **Important :** `DB_HOST=127.0.0.1` dans votre `.env` local pointe vers **votre PC**. En production, `DB_HOST` doit être l’**hôte fourni par le service de base de données** (Render ou un autre).

---

## Prérequis

1. Compte [Render](https://render.com) (gratuit avec carte ou offre étudiante selon période).
2. Compte **GitHub** avec le projet poussé (`git push`).
3. Fichier **`.env` non versionné** (déjà dans `.gitignore`).
4. PHP **8.2+** et Laravel **12** (déjà dans `composer.json`).

Générer une clé pour la production (à faire une fois, copier le résultat) :

```bash
php artisan key:generate --show
```

Vous collerez cette valeur dans `APP_KEY` sur Render.

---

## Partie A — Créer la base de données

Render propose surtout **PostgreSQL** en service managé. Votre projet utilise **MySQL** en local ; vous avez **deux options** :

### Option 1 — PostgreSQL sur Render (recommandé, tout sur Render)

1. Dashboard Render → **New +** → **PostgreSQL**.
2. Nom : `todo-mvc-db`, région proche de vous, plan **Free**.
3. Après création, noter dans l’onglet **Info** :
   - **Hostname** → `DB_HOST`
   - **Port** → `DB_PORT`
   - **Database** → `DB_DATABASE`
   - **Username** → `DB_USERNAME`
   - **Password** → `DB_PASSWORD`

Dans les variables Laravel :

```env
DB_CONNECTION=pgsql
DB_HOST=<hostname Render>
DB_PORT=5432
DB_DATABASE=<nom>
DB_USERNAME=<user>
DB_PASSWORD=<mot de passe>
```

> Laravel supporte PostgreSQL nativement ; vos migrations `taches` fonctionnent telles quelles.

### Option 2 — Garder MySQL (base externe)

Créer une MySQL gratuite ailleurs (ex. [Railway](https://railway.app), [Aiven](https://aiven.io), plan étudiant, etc.) et utiliser :

```env
DB_CONNECTION=mysql
DB_HOST=<hôte distant, pas 127.0.0.1>
DB_PORT=3306
DB_DATABASE=todo_mvc
DB_USERNAME=...
DB_PASSWORD=...
```

Puis seulement créer le **Web Service** sur Render (Partie B).

---

## Partie B — Créer le Web Service (application Laravel)

### 1. Nouveau service

1. **New +** → **Web Service**.
2. Connecter le dépôt GitHub `todo_mvc`.
3. Choisir la branche (`main` ou `master`).

### 2. Paramètres du service

| Champ | Valeur recommandée |
|-------|-------------------|
| **Name** | `todo-mvc` (ou autre) |
| **Region** | Même région que la base (ex. Frankfurt) |
| **Runtime** | **PHP** (ou **Docker** si vous ajoutez un Dockerfile plus tard) |
| **Branch** | `main` |
| **Root Directory** | *(laisser vide — racine du repo)* |

### 3. Commandes de build et de démarrage

**Build Command** (exécutée à chaque déploiement) :

```bash
composer install --no-dev --optimize-autoloader && php artisan config:cache && php artisan route:cache && php artisan view:cache
```

**Start Command** :

```bash
php artisan serve --host=0.0.0.0 --port=$PORT
```

> Render injecte la variable `$PORT`. Laravel écoute ainsi sur le bon port.

**Alternative plus « production »** (si l’environnement PHP Render inclut Apache) :

```bash
heroku-php-apache2 public/
```

Dans ce cas, la racine web est bien le dossier `public/` (standard Laravel).

### 4. Plan

Choisir **Free** pour un projet scolaire. Limites typiques :

- Mise en veille après inactivité (~15 min).
- Premier chargement lent (« cold start »).
- Ressources CPU/RAM limitées.

---

## Partie C — Variables d'environnement (le cœur de l’étape 4)

Dans le Web Service → **Environment** → ajouter **chaque** variable (équivalent du `.env`, mais sur le panel Render).

### Variables obligatoires

| Variable | Exemple / remarque |
|----------|-------------------|
| `APP_NAME` | `Gestion des Tâches` |
| `APP_ENV` | `production` |
| `APP_KEY` | `base64:...` (nouvelle clé, voir `key:generate --show`) |
| `APP_DEBUG` | `false` |
| `APP_URL` | `https://todo-mvc-xxxx.onrender.com` *(URL exacte après 1er déploiement)* |
| `DB_CONNECTION` | `pgsql` ou `mysql` |
| `DB_HOST` | Hôte fourni par la base cloud |
| `DB_PORT` | `5432` (PostgreSQL) ou `3306` (MySQL) |
| `DB_DATABASE` | Nom de la base |
| `DB_USERNAME` | Utilisateur |
| `DB_PASSWORD` | Mot de passe |

### Variables déjà utilisées dans votre projet

| Variable | Valeur prod conseillée |
|----------|------------------------|
| `SESSION_DRIVER` | `database` |
| `CACHE_STORE` | `database` |
| `QUEUE_CONNECTION` | `database` |
| `LOG_LEVEL` | `error` ou `warning` |

Ne **copiez pas** tout votre `.env` local : surtout pas `DB_HOST=127.0.0.1`.

### Lier une base Render PostgreSQL en un clic

Si la base est dans le **même compte** Render :

1. **Environment** → **Add from database** (ou lien vers la base créée en Partie A).
2. Render peut préremplir `DATABASE_URL` ; Laravel 12 accepte souvent `DATABASE_URL` si vous configurez `config/database.php` — sinon mappez manuellement vers `DB_HOST`, etc.

---

## Partie D — Migrations (créer la table `taches` en prod)

La base cloud est **vide** au départ. Il faut lancer les migrations **une fois** (ou à chaque changement de schéma).

### Méthode 1 — Shell Render (simple)

1. Web Service → **Shell** (si disponible sur votre plan).
2. Exécuter :

```bash
php artisan migrate --force
```

`--force` est requis car `APP_ENV=production`.

### Méthode 2 — Commande dans le Build (pratique)

Ajouter à la fin du **Build Command** :

```bash
php artisan migrate --force
```

> La base doit déjà être joignable pendant le build (variables `DB_*` correctes).

### Vérification

Après migration, la table **`taches`** doit exister dans la base distante (idem en local après `php artisan migrate`).

---

## Partie E — Premier déploiement et URL publique

1. **Create Web Service** / **Deploy**.
2. Attendre le build (logs : `composer install`, caches, migrations).
3. Statut **Live** → cliquer l’URL : `https://<nom>.onrender.com`.
4. Mettre à jour `APP_URL` avec cette URL exacte → **Save** → redéployer si nécessaire.

Routes de votre app :

| URL | Effet |
|-----|--------|
| `/` | Redirection vers `/taches` (`routes/web.php`) |
| `/taches` | Liste des tâches |
| `/taches/create` | Nouvelle tâche |

---

## Partie F — Schéma du flux « étape 4 » (utilisation Render)

```text
1. Vous poussez du code sur GitHub
        ↓
2. Render détecte le push → lance le Build Command
        ↓
3. composer install installe Laravel dans vendor/
        ↓
4. Les variables Environment sont injectées (remplacent .env)
        ↓
5. migrate --force crée users, cache, jobs, taches...
        ↓
6. Start Command lance php artisan serve sur $PORT
        ↓
7. Internet → https://votre-app.onrender.com → TacheController → Modèle Tache → table taches
```

---

## Dépannage

| Problème | Cause probable | Action |
|----------|----------------|--------|
| 500 Internal Server Error | `APP_KEY` vide | Générer et coller `APP_KEY` |
| SQLSTATE connection refused | `DB_HOST` encore en `127.0.0.1` | Utiliser l’hôte cloud |
| Table doesn't exist | Migrations non lancées | `php artisan migrate --force` |
| Page 404 sur `/` seulement | Normal si pas de redirect | Votre projet redirige `/` → `/taches` |
| Très lent au 1er clic | Plan gratuit endormi | Attendre 30–60 s |
| Assets / CSS cassés | Peu probable chez vous | CSS inline dans `layouts/app.blade.php` |

Consulter les logs : Web Service → **Logs** (erreurs PHP, SQL, Laravel).

---

## Sécurité avant démo publique

- `APP_DEBUG=false` obligatoire.
- Ne jamais committer `.env`.
- Pas d’authentification dans votre app : **tout visiteur peut modifier/supprimer** les tâches — acceptable pour un TP, à mentionner en présentation.

---

## Checklist finale

- [ ] Code sur GitHub, `.env` ignoré
- [ ] Base cloud créée (PostgreSQL Render ou MySQL externe)
- [ ] Web Service PHP créé, build + start configurés
- [ ] Variables `APP_*` et `DB_*` renseignées
- [ ] `APP_URL` = URL Render réelle
- [ ] `php artisan migrate --force` exécuté
- [ ] Test : créer une tâche sur l’URL publique
- [ ] Vérifier la ligne dans la table `taches` (console base ou client SQL)

---

## Résumé en une phrase

**Render exécute votre repo Laravel comme un serveur PHP permanent : le build installe les dépendances, les variables d’environnement remplacent le `.env` local pour pointer vers une base distante, les migrations créent `taches`, et l’URL `*.onrender.com` rend l’app accessible à tous.**
