# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Project overview

Share.app is a Laravel web app for residents of an international-exchange share house. It lets
residents see each other's profiles (to spark conversation) and manage/share house "reminds"
(recurring notices). Primary users are house residents in their 20s-40s.

Stack: PHP 8.1 / Laravel 9 backend, Blade + Tailwind frontend (Vite), MySQL, Docker (built for an
Ubuntu host — see README.md for a note that dev performance improved after moving the Docker
project off Windows). External APIs: OpenWeatherMap, LINE Messaging API, OpenAI (`openai-php/client`).
Auth is Laravel Breeze.

The actual Laravel application lives under `root/` — not the repo root. Run all `artisan`/`composer`/
`npm` commands from `root/` (or `docker compose exec web <cmd>` since the container's working dir is
`/var/www/root`).

## Development environment

The app runs in Docker (`docker-compose.yml` at repo root, PHP 8.1-Apache image built from
`docker/php/Dockerfile`, MySQL 8.0, phpMyAdmin). Services: `web`, `db`, `phpmyadmin`.

```bash
docker compose up -d                 # start web/db/phpmyadmin
docker compose exec web bash         # shell into the app container (cwd: /var/www/root)
```

Inside the container (or locally from `root/` with PHP 8.1+ / Composer installed):

```bash
composer install
npm install && npm run dev           # or: npm run build
php artisan migrate
php artisan serve                    # if not relying on the Apache container
```

### Tests

PHPUnit, config at `root/phpunit.xml` (suites: `Unit`, `Feature`).

```bash
cd root
php artisan test                     # or: vendor/bin/phpunit
php artisan test --filter=TestName   # single test
php artisan test tests/Feature/ProfileTest.php
```

Test env config (`phpunit.xml`) sets `APP_ENV=testing`, `CACHE_DRIVER=array`, `SESSION_DRIVER=array`,
`QUEUE_CONNECTION=sync`; the sqlite in-memory DB lines are commented out, so tests currently run
against whatever `DB_*` connection is configured in `.env.testing`/`.env`.

Feature test coverage today is essentially Breeze's generated auth tests (`tests/Feature/Auth/*`) plus
`ProfileTest`; the app's own domain logic (Resident/Remind/AI/Weather services) has no test coverage
yet (tracked as a TODO in README.md).

## Architecture

Standard Laravel MVC with a **Controller → FormRequest → Service → Model** pattern used consistently
for domain features. Controllers are kept thin: they validate via a `FormRequest`, delegate the actual
work to a `Service` class injected via constructor DI, and return a view/redirect/JSON response.

- `root/app/Http/Controllers/` — one controller per resource (`ResidentController`, `RemindController`,
  `HouseRuleQAController`, `HomeController`, `JobController`, `ProfileController`, plus Breeze's `Auth/`).
- `root/app/Http/Requests/` — validation lives here (`StoreResidentRequest`, `StoreRemindRequest`,
  `StoreJobRequest`, `UpdateJobRequest`), not inline in controllers.
- `root/app/Services/` — business logic, namespaced by domain:
  - `Resident/` — `ResidentService` (CRUD), `ResidentImageService` (profile/photo uploads),
    `ResidentCount` (current-resident count for the home dashboard)
  - `Remind/` — `RemindService` (CRUD + the "N reminds in one category triggers a LINE notification"
    behavior described in the README)
  - `Weather/` — `WeatherService` (OpenWeatherMap integration for the home page)
  - `AI/` — `HouseRuleAIService` (OpenAI-backed house-rule Q&A)
  - `Line/` — `LineNotifyService` (LINE Messaging API notifications)

  Note: `HouseRuleQAController` currently imports `App\Services\HouseRuleAIService`, but the class is
  namespaced `App\Services\AI\HouseRuleAIService` — check this if that controller/service is touched.

- `root/app/Models/` — `User` (hasOne `Resident`), `Resident` (belongsTo `User`, hasMany
  `ResidentImage`), `ResidentImage`, `Remind`, `Job`. A resident is 1:1 with the user account that
  created it, and access to editing a resident is restricted to its owning user (`user_id !==
  auth()->id()` checks in `ResidentController@edit`/`update`).

- Routes (`root/routes/web.php`): everything domain-specific is nested under an `auth`-protected
  `admin` prefix/name group (`admin.residents.*`, `admin.reminds.*`, `admin.house_qa.*`,
  `admin.index`), despite the app being resident-facing rather than an admin panel — this naming is
  historical, not a permissions boundary. Breeze's own routes come from `routes/auth.php`.

- Views (`root/resources/views/`): Blade templates under `admin/` mirror the route groups above
  (`admin/residents`, `admin/reminds`, `admin/house_rule_qa`, `admin/home`, `admin/calender`,
  `admin/news`, `admin/circle information`). Shared layout/components in `layouts/` and `components/`.

## Conventions in this codebase

- Controllers use constructor property promotion for service injection (`private ResidentService
  $residentService`) — no facade/static calls to services.
- FormRequests own validation; controllers call `$request->validated()` and strip file fields
  (`image`, `photos`) before passing plain data arrays to the service layer, passing
  `UploadedFile`s separately.
- Code comments throughout are in Japanese and fairly dense (explaining Laravel mechanics like DI,
  `abort()`, route model binding) — match that style/language when editing existing files rather than
  switching to English or terser comments.
