# Gestion des tâches (todo_mvc)

Application web Laravel pour **ajouter**, **modifier** et **supprimer** des tâches. Chaque tâche a un titre, une description et un statut (en cours / terminée).

## Architecture MVC

| Couche | Fichiers |
|--------|----------|
| **Modèle** | `app/Models/Tache.php` |
| **Vue** | `resources/views/taches/`, `resources/views/layouts/app.blade.php` |
| **Contrôleur** | `app/Http/Controllers/TacheController.php` |
| **Routes** | `routes/web.php` |

## Prérequis

- PHP 8.2+
- Composer
- MySQL

## Installation en local

```bash
composer install
cp .env.example .env
php artisan key:generate
# Configurer DB_* dans .env
php artisan migrate
php artisan serve
```

Ouvrir http://127.0.0.1:8000/taches

## Déploiement (Render + Docker + PostgreSQL)

1. Créer une base **PostgreSQL** sur Render.
2. Web Service **Docker** (voir `Dockerfile`).
3. Variables : `DB_CONNECTION=pgsql`, `DATABASE_URL` (fourni par Render), `APP_KEY`, `APP_URL`.
4. Shell : `php artisan migrate --force`.

Guide complet : [DEPLOY.md](DEPLOY.md). Blueprint optionnel : `render.yaml`.

## Licence

Projet pédagogique — Laravel est sous [licence MIT](https://opensource.org/licenses/MIT).
