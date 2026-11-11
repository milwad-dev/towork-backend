# ToWork API v1

Base URL: `{APP_URL}/api/v1` (Docker default: `http://localhost:8000/api/v1`).

## Conventions

- Send `Accept: application/json`. Authenticated routes need `Authorization: Bearer <token>` (Laravel Sanctum personal access token).
- Single resources: `{ "data": { ... } }`.
- Paginated lists: `{ "data": [...], "links": {...}, "meta": { "current_page", "last_page", "per_page", "total", ... } }`.
- Validation errors: `422 { "message": "...", "errors": { "field": ["..."] } }`.
- `401 { "message": "Unauthenticated." }`, `403/404 { "message": "..." }`, `429` when rate-limited.
- Dates: `due_date` is `YYYY-MM-DD`, `due_time` is `HH:MM` (24h), timestamps are ISO-8601 strings (UTC).
- You can only see and change your own tasks, categories, labels and subtasks. Anything else returns `404`.
- Send `X-Timezone: <IANA name>` (e.g. `Europe/Berlin`) so "today", "overdue" and stats use the user's local day. Default: UTC.

## Objects

### User
```json
{ "id": 1, "name": "Jane", "email": "jane@example.com", "phone": null,
  "email_verified_at": null, "created_at": "2026-11-11T10:00:00.000000Z" }
```

### Category (a "project" or list)
```json
{ "id": 1, "name": "Work", "color": "indigo", "icon": "briefcase",
  "tasks_count": 12, "open_tasks_count": 5, "created_at": "..." }
```
`color` is one of: `slate, red, orange, amber, green, teal, sky, blue, indigo, violet, pink, rose`.
`icon` is an optional free string (max 50), meant for a Lucide icon name such as `briefcase`, `home` or `book`.

### Label
```json
{ "id": 1, "name": "urgent", "color": "red", "tasks_count": 3 }
```

### Subtask
```json
{ "id": 1, "task_id": 7, "title": "Buy milk", "is_completed": false, "position": 0 }
```

### Task
```json
{
  "id": 7,
  "title": "Ship v1",
  "description": "Markdown/plain text or null",
  "status": "todo",              // "todo" | "in_progress" | "done"
  "priority": 2,                 // 1 = low, 2 = medium, 3 = high, 4 = urgent
  "due_date": "2026-11-20",      // or null
  "due_time": "14:30",           // or null
  "remind_at": "2026-11-20T13:30:00.000000Z", // or null (reminder e-mail)
  "completed_at": null,
  "position": 0,                 // order inside its status column (Kanban)
  "is_overdue": false,
  "category_id": 1,
  "category": { "id": 1, "name": "Work", "color": "indigo", "icon": "briefcase" },
  "labels": [{ "id": 1, "name": "urgent", "color": "red" }],
  "subtasks": [ /* Subtask[] */ ],
  "subtasks_count": 3,
  "completed_subtasks_count": 1,
  "created_at": "...", "updated_at": "..."
}
```

## Auth  (rate limited)

| Method | Path | Body | Response |
|---|---|---|---|
| POST | `/auth/register` | `name, email, phone?, password, password_confirmation` | `201 { data: { user, token } }`. A verification code is e-mailed. |
| POST | `/auth/login` | `login` (email **or** phone), `password` | `200 { data: { user, token } }`. Bad credentials: `422`, `errors.login`. |
| POST | `/auth/logout` 🔒 | – | `204` (revokes the current token) |
| POST | `/auth/forgot-password` | `email` | `200 { message }` (always 200; e-mails a 6-digit code if the account exists) |
| POST | `/auth/reset-password/check` | `email, code` | `200 { message }` or `422 errors.code` |
| POST | `/auth/reset-password` | `email, code, password, password_confirmation` | `200 { message }` or `422` |
| POST | `/auth/email/verification-notification` 🔒 | – | `200 { message }` (sends a new 6-digit code), `409` if already verified |
| POST | `/auth/email/verify` 🔒 | `code` | `200 { data: User }` or `422 errors.code` |

Codes expire after 60 minutes. Passwords need at least 8 characters, including letters and numbers.

## Profile 🔒

| Method | Path | Body | Response |
|---|---|---|---|
| GET | `/me` | – | `{ data: User }` |
| PUT | `/me` | `name, email, phone?` | `{ data: User }` (changing the e-mail clears `email_verified_at`) |
| PUT | `/me/password` | `current_password, password, password_confirmation` | `204` |
| DELETE | `/me` | `password` | `204` (deletes the account and all its data) |

## Categories 🔒

| Method | Path | Body | Response |
|---|---|---|---|
| GET | `/categories` | – | `{ data: Category[] }` (all, not paginated, ordered by name) |
| POST | `/categories` | `name, color?, icon?` | `201 { data: Category }` |
| GET | `/categories/{id}` | – | `{ data: Category }` |
| PUT | `/categories/{id}` | `name?, color?, icon?` | `{ data: Category }` |
| DELETE | `/categories/{id}` | – | `204` (its tasks become uncategorized) |

`name` must be unique per user.

## Labels 🔒

These work the same way as categories at `/labels`, with fields `name` and `color`. Deleting a label detaches it from its tasks.

## Tasks 🔒

### `GET /tasks`
Query parameters (all optional):

| Param | Values |
|---|---|
| `search` | text matched against title and description |
| `status` | comma list of `todo,in_progress,done` |
| `priority` | comma list of `1..4` |
| `category_id` | an id, or `none` for uncategorized |
| `label_id` | an id |
| `due` | `today`, `overdue` (not done, before today), `upcoming` (after today), `week` (today through +6 days), `scheduled` (any due date), `none` (no due date) |
| `date_from`, `date_to` | `YYYY-MM-DD`, inclusive range on `due_date` (calendar view) |
| `sort` | `position` (default), `created_at`, `due_date`, `priority`, `title` |
| `direction` | `asc` / `desc` (default `asc`, except `created_at`, which defaults to `desc`) |
| `per_page` | 1–200, default 50 |
| `page` | page number |

Returns a paginated `Task[]`. Tasks with no due date sort last when sorting by `due_date`.

### Others

| Method | Path | Body | Response |
|---|---|---|---|
| POST | `/tasks` | `title` (required), `description?, status?, priority?, category_id?, label_ids?: int[], due_date?, due_time?, remind_at?, subtasks?: string[]` | `201 { data: Task }`, appended to the end of its status column |
| GET | `/tasks/{id}` | – | `{ data: Task }` |
| PATCH/PUT | `/tasks/{id}` | any subset of the store fields (`label_ids` replaces the whole set) | `{ data: Task }`. Setting status to `done` sets `completed_at`. |
| DELETE | `/tasks/{id}` | – | `204` |
| PATCH | `/tasks/{id}/toggle` | – | `{ data: Task }`: `done` ↔ `todo` |
| POST | `/tasks/reorder` | `tasks: [{ id, status, position }]` | `204` (Kanban drag and drop; send every task in the affected columns) |
| POST | `/tasks/{id}/subtasks` | `title` | `201 { data: Subtask }` |
| PATCH | `/subtasks/{id}` | `title?, is_completed?` | `{ data: Subtask }` |
| DELETE | `/subtasks/{id}` | – | `204` |

## Stats 🔒  `GET /stats`

```json
{ "data": {
  "totals": { "all": 40, "todo": 20, "in_progress": 5, "done": 15,
              "overdue": 3, "due_today": 4, "upcoming": 9 },
  "completion_rate": 38,
  "by_priority": { "1": 10, "2": 15, "3": 10, "4": 5 },   // open tasks only
  "by_category": [ { "id": 1, "name": "Work", "color": "indigo", "total": 12, "done": 7 } ],
  "activity": [ { "date": "2026-10-29", "created": 2, "completed": 1 } ],  // the last 14 days, oldest first
  "streak": 3   // consecutive days, ending today or yesterday, with at least one completed task
} }
```

## Health
`GET /up` returns 200 when the app is running. It sits outside the `/api/v1` prefix and is used by Docker health checks.
