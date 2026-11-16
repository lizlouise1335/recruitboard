# RecruitBoard

[![CI](https://github.com/lizlouise1335/recruitboard/actions/workflows/ci.yml/badge.svg)](https://github.com/lizlouise1335/recruitboard/actions/workflows/ci.yml)

A small athlete-recruiting directory, built to practice the full path from code to production. Coaches can browse high-school athletes, filter by sport and grad year, search live as they type, and open a profile.

The app itself is small on purpose. The point is everything around it: tests, type checking, a container image, and a CI pipeline that has to pass before anything reaches `main`.

**Live demo:** https://recruitboard.onrender.com

## Stack

| Layer | Tool |
|---|---|
| Backend | PHP 8.4, Laravel 13, Eloquent ORM |
| Database | SQLite |
| Frontend | Blade templates, Tailwind CSS 4, TypeScript (live search) |
| Build | Vite |
| Quality | PHPUnit feature tests, Laravel Pint (code style), `tsc --noEmit` |
| Container | Multi-stage Docker image (Node → Composer → PHP + Apache) |
| CI | GitHub Actions |
| Hosting | Render (Docker web service) |

## Features

- **Athlete list** at `/athletes`, with filters for name, sport and grad year
- **Live search** in TypeScript: it calls the JSON API as you type and redraws the list without reloading the page
- **Profile pages** at `/athletes/{id}`, which return a 404 for unknown IDs
- **JSON API** at `/api/athletes`, which takes the same `q`, `sport` and `grad_year` filters
- **Health check** at `/api/health`, used by CI and the host to confirm the app is up
- **Version footer** showing the commit that's running, so you can tell which build is live

## How it fits together

```
 push / pull request
        │
        ▼
 ┌──────────────── GitHub Actions (ci.yml) ────────────────┐
 │  PHP tests ──────┐                                      │
 │  (Pint + PHPUnit)├──► Docker image ──► smoke test        │
 │  TypeScript ─────┘    (build, cached)  (health, data,   │
 │  (tsc + vite build)                     commit hash)    │
 └──────────────────────────────────────────────────────────┘
        │  all green + PR approved (branch ruleset on main)
        ▼
      main ──► Deploy job ──► Render deploy hook ──► wait until the
               (push only)                           live site shows
                                                     this commit
```

- **Two parallel jobs** check the PHP and TypeScript sides at the same time.
- **The Docker job** runs only if both pass (`needs`). It builds the real production image, starts it, and checks that `/api/health` responds, that the seeded athletes come back, and that the footer shows the commit being tested.
- **Caching:** Composer, npm and Docker layer caches are keyed on the lock files, so a PHP-only change doesn't reinstall JavaScript packages.
- **The deploy job** runs only on pushes to `main` (after a PR merges). It calls Render's deploy hook, then checks `/athletes` until the footer shows the new commit hash. The job goes green only once that version is actually live, not as soon as Render accepts the request.
- **Secrets vs. variables:** the deploy hook URL is a secret (anyone holding it can trigger deploys). `PROD_URL` is a plain variable, because it's public anyway.
- **Least privilege:** the workflow token is read-only (`permissions: contents: read`).

## Run it locally

You'll need PHP 8.4, Composer and Node 24.

```bash
git clone https://github.com/lizlouise1335/recruitboard.git
cd recruitboard

composer install
npm install

cp .env.example .env
php artisan key:generate

touch database/database.sqlite
php artisan migrate --seed     # creates the tables and loads 10 demo athletes

npm run build                  # or "npm run dev" in a second terminal for hot reload
php artisan serve              # http://localhost:8000
```

## Run it in Docker

This builds the same image CI tests:

```bash
docker build -t recruitboard .
docker run --rm -p 8080:80 \
  -e APP_KEY="base64:$(openssl rand -base64 32)" \
  recruitboard
# open http://localhost:8080
```

Each time it starts, the container runs migrations and the seeder. Both are safe to repeat, because the seeder uses `updateOrCreate`. The SQLite file lives inside the container, so any data you add is gone when the container is removed.

## Checks

These are the same checks CI runs:

```bash
php artisan test        # feature tests
vendor/bin/pint --test  # code style (drop --test to auto-fix)
npm run typecheck       # TypeScript only
npm run verify          # typecheck + production build
```

The tests cover the health endpoint, the athlete list, API filtering by sport, profile pages, and the 404 for a missing athlete.

## Project layout

```
app/Http/Controllers/AthleteController.php   list, profile and JSON search
app/Models/Athlete.php                       Eloquent model + casts
database/migrations/                         athletes table
database/seeders/AthleteSeeder.php           10 demo athletes (idempotent)
database/factories/AthleteFactory.php        fake athletes for tests
resources/views/                             Blade layout, list, profile
resources/js/app.ts                          TypeScript live search
routes/web.php                               every URL in the app
tests/Feature/                               HTTP-level tests
Dockerfile, docker/entrypoint.sh             production image + startup
.github/workflows/ci.yml                     the pipeline, including deploy
```

## Roadmap

- [x] Laravel scaffold, athletes table, seed data
- [x] Feature tests
- [x] TypeScript live search
- [x] Docker image
- [x] CI pipeline with a branch ruleset on `main`
- [x] Deploy to Render through a deploy hook after CI passes
