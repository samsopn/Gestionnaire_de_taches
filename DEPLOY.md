# Déploiement Render (Docker)

## Fichiers ajoutés

- `Dockerfile` — image PHP 8.2 + Laravel
- `.dockerignore` — accélère le build

## Render (Web Service)

| Paramètre | Valeur |
|-----------|--------|
| Runtime | **Docker** |
| Dockerfile Path | `./Dockerfile` |
| Docker Context | `.` |
| Health Check Path | `/up` (optionnel) |

## Variables d'environnement (Environment)

```
APP_ENV=production
APP_DEBUG=false
APP_KEY=base64:...          # php artisan key:generate --show
APP_URL=https://VOTRE-SERVICE.onrender.com

DB_CONNECTION=mysql
DB_HOST=...
DB_PORT=3306
DB_DATABASE=...
DB_USERNAME=...
DB_PASSWORD=...

SESSION_DRIVER=database
CACHE_STORE=database
QUEUE_CONNECTION=database
```

## Après le premier déploiement

Shell Render :

```bash
php artisan migrate --force
```

## Test local (optionnel)

```bash
docker build -t todo-mvc .
docker run -p 8000:10000 -e PORT=10000 --env-file .env todo-mvc
```

Ouvrir http://localhost:8000/taches
