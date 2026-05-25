# Déploiement Render (Docker + PostgreSQL)

## 1. Créer la base PostgreSQL sur Render

1. Dashboard Render → **New +** → **PostgreSQL**.
2. Nom : ex. `todo-mvc-db`, région proche du Web Service, plan **Free**.
3. Créer la base → noter qu’elle fournit `DATABASE_URL` (URL de connexion).

## 2. Créer le Web Service (Docker)

1. **New +** → **Web Service** → repo GitHub `todo_mvc`.
2. **Runtime** : Docker.
3. **Dockerfile Path** : `./Dockerfile`.
4. **Health Check Path** : `/up` (optionnel).

## 3. Lier PostgreSQL au Web Service

Dans le Web Service → **Environment** :

**Option A — Lier la base (recommandé)**  
- **Add from Render Postgres** / lier `todo-mvc-db` → Render injecte `DATABASE_URL`.

Puis ajouter manuellement :

| Variable | Valeur |
|----------|--------|
| `DB_CONNECTION` | `pgsql` |
| `DB_SSLMODE` | `require` |

Laravel lit `DATABASE_URL` via `config/database.php` (`DB_URL` ou `DATABASE_URL`).

**Option B — Variables une par une**  
Si Render expose host/user/password :

| Variable | Exemple |
|----------|---------|
| `DB_CONNECTION` | `pgsql` |
| `DB_HOST` | `dpg-xxxxx-a.frankfurt-postgres.render.com` |
| `DB_PORT` | `5432` |
| `DB_DATABASE` | `todo_mvc_xxxx` |
| `DB_USERNAME` | `todo_mvc_xxxx_user` |
| `DB_PASSWORD` | (secret) |
| `DB_SSLMODE` | `require` |

## 4. Autres variables obligatoires

```
APP_ENV=production
APP_DEBUG=false
APP_KEY=base64:...     # php artisan key:generate --show (nouvelle clé prod)
APP_URL=https://VOTRE-SERVICE.onrender.com

SESSION_DRIVER=database
CACHE_STORE=database
QUEUE_CONNECTION=database
```

## 5. Premier déploiement

1. **Push** GitHub → Render build l’image Docker (PHP + `pdo_pgsql`).
2. Quand le service est **Live**, ouvrir **Shell** :

```bash
php artisan migrate --force
```

3. Tester : `https://VOTRE-SERVICE.onrender.com/taches`

## 6. Local vs production

| Environnement | Base |
|---------------|------|
| **Local** (`.env`) | MySQL ou SQLite — inchangé |
| **Render** | PostgreSQL (`DB_CONNECTION=pgsql`) |

Le code applicatif (modèle `Tache`, migrations) reste le même.

## 7. Blueprint optionnel (`render.yaml`)

Un fichier `render.yaml` à la racine peut créer la base + le Web Service en une fois (voir fichier dans le repo).

## 8. Dépannage

| Erreur | Solution |
|--------|----------|
| `could not find driver` | Rebuild Docker (extension `pdo_pgsql` dans Dockerfile) |
| Connexion refusée | `DB_SSLMODE=require`, vérifier `DATABASE_URL` / liaison Postgres |
| Table `taches` absente | `php artisan migrate --force` dans le Shell |
| 500 / APP_KEY | Définir `APP_KEY` dans Environment |
