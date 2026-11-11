# ToWork: Backend (API)

ToWork is a free, open-source todo app built with [Laravel 13](https://laravel.com). This repository holds the REST API. The Vue frontend lives in [towork-vue](https://github.com/milwad-dev/towork-vue).

The project is also meant as a learning resource: it uses a modular architecture, services and repositories, policies, form requests, API resources, queues, the scheduler and test-driven development with Pest.

## Features

- 🔐 **Auth** with [Sanctum](https://laravel.com/docs/sanctum) tokens: register, log in with e-mail **or** phone, log out, forgot or reset password, and e-mail verification. Verification and reset codes are 6 digits, stored hashed, expire, and lock after too many wrong attempts.
- ✅ **Tasks** with title, description, status (`todo` / `in_progress` / `done`), 4 priority levels, due date and time, and e-mail reminders.
- 🗂️ **Categories** (projects) with a colour and an icon, plus **labels** (tags) attached to tasks.
- ☑️ **Subtasks** (checklists) with progress counters.
- 🧲 **Kanban** ordering API for drag and drop between columns.
- 🔎 **Search, filters and sorting**: text, status, priority, category, label, due windows (today, overdue, upcoming, this week), calendar date ranges and pagination.
- 📊 **Dashboard stats**: totals, completion rate, a 14-day activity chart, per-category progress and a completion streak. These are timezone aware through the `X-Timezone` header.
- 👤 **Profile**: update details, change password, delete account.
- 🛡️ **Ownership enforced by policies**: another user's data always returns `404`. Auth endpoints are rate limited.
- 📬 Queued e-mails, scheduled reminders, Mailpit for local e-mail testing.
- 🐳 **Docker** stack: nginx, PHP-FPM 8.4, MySQL 8.4, Redis, queue worker, scheduler and Mailpit.
- 🧪 About 55 Pest feature tests, Pint code style and GitHub Actions CI.

## Quick start with Docker

```bash
git clone https://github.com/milwad-dev/towork-backend.git
cd towork-backend
docker compose up -d --build
```

| What | URL |
|---|---|
| API | http://localhost:8000/api/v1 |
| Health check | http://localhost:8000/up |
| Mailpit (e-mails, verification codes) | http://localhost:8025 |

On first start the container generates an `APP_KEY`, runs the migrations and seeds a demo account:

- **E-mail:** `demo@towork.test`
- **Password:** `password123`

Every setting can be overridden with `TOWORK_*` variables, for example `TOWORK_APP_PORT=9000 docker compose up -d`. The full list is in [`.env.docker.example`](.env.docker.example). The `TOWORK_` prefix keeps your local Laravel `.env` from leaking into the containers.

Useful commands:

```bash
docker compose logs -f app queue          # follow logs
docker compose exec app php artisan tinker
docker compose down                       # stop (add -v to delete the data)
```

## Local development (without Docker)

Requires PHP 8.4+ and Composer. SQLite is used by default.

```bash
composer install
cp .env.example .env
php artisan key:generate
touch database/database.sqlite
php artisan migrate --seed
php artisan serve                 # http://localhost:8000
php artisan queue:work            # in a second terminal (e-mails)
php artisan schedule:work         # optional: reminders
```

With `MAIL_MAILER=log`, e-mails (and their codes) are written to `storage/logs/laravel.log`.

## API

The full reference is in [`docs/api-v1.md`](docs/api-v1.md). Interactive docs are served at `/request-docs` when `APP_ENV=local`.

```bash
# Log in and list today's tasks
TOKEN=$(curl -s -X POST localhost:8000/api/v1/auth/login \
  -H 'Accept: application/json' -H 'Content-Type: application/json' \
  -d '{"login":"demo@towork.test","password":"password123"}' | jq -r .data.token)

curl -s "localhost:8000/api/v1/tasks?due=today" \
  -H "Authorization: Bearer $TOKEN" -H 'Accept: application/json' -H 'X-Timezone: Europe/Berlin'
```

## Architecture

Every feature is a module under `Modules/`, with its own routes, migrations, models, requests, resources, services, repositories, policies and tests:

```
Modules/
├── Common      base controller, ModuleServiceProvider, ownership policy, colour palette, timezone middleware
├── User        user model, profile endpoints (/me)
├── Auth        register / login / logout, password reset and e-mail verification codes
├── Category    categories (projects)
├── Label       labels (tags)
├── Task        tasks, subtasks, Kanban reorder, reminders (tasks:send-reminders)
└── Dashboard   statistics (/stats)
```

Create a new module skeleton with:

```bash
php artisan make:module Comment
```

## Testing

```bash
composer test        # Pest
composer lint        # Pint
```

## Contributors

This project exists thanks to all the people who contribute.

<a href="https://github.com/milwad-dev/towork-backend/graphs/contributors">
    <img src="https://opencollective.com/towork-backend/contributors.svg?width=890&button=false" alt="contributors">
</a>

## Issues

If you have a problem, please open an [issue](https://github.com/milwad-dev/towork-backend/issues).

## License

ToWork is open-source software and will always be free under the [MIT License](LICENSE).
