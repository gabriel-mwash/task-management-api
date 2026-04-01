# Task Management API (Laravel)

## Overview

A simple Task Management application built with **Laravel (PHP)** and **MySQL**. The system allows users to:

- Create tasks
- View and filter tasks
- Update task status with strict progression rules
- Delete completed tasks
- Generate a daily report of tasks (bonus feature)

A simple web interface is included using **Blade, vanilla JavaScript, and custom CSS**.

---

## Tech Stack

- PHP 8.3 (Laravel 13)
- MySQL
- Blade (templating)
- Vanilla JavaScript
- CSS

---

## Database

- **Database used:** MySQL
- Migrations included in `database/migrations/`
- included also mysql dump file `task_manager.sql`
---

## Running Locally

### 1. Clone the repository

```bash
git clone git@github.com:gabriel-mwash/task-management-api.git
cd task-management-api
```

### 2. Install dependencies

```bash
composer install
```

### 3. Configure environment

```bash
cp .env.example .env
```

Update the following in `.env`:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=task_manager
DB_USERNAME=root
DB_PASSWORD=your_password
```

### 4. Generate app key

```bash
php artisan key:generate
```

### 5. Run migrations and seeders

```bash
php artisan migrate
php artisan db:seed --class=TaskSeeder
```

### 6. Start the server

```bash
php artisan serve
```

Visit `http://localhost:8000` in your browser.

---

## Testing the API Locally (cURL)

Make sure your local server is running (`php artisan serve`) before testing.

**Base URL:** `http://localhost:8000`

---

### 1. Create a task

```bash
curl -X POST http://localhost:8000/api/tasks \
  -H "Accept: application/json" \
  -H "Content-Type: application/json" \
  -d '{
    "title": "Fix login bug",
    "due_date": "2026-04-10",
    "priority": "high"
  }'
```

> **Rules:**
> - `title` cannot duplicate a task with the same `due_date`
> - `priority` must be `low`, `medium`, or `high`
> - `due_date` must be today or a future date

---

### 2. List all tasks

```bash
curl -X GET http://localhost:8000/api/tasks \
  -H "Accept: application/json"
```

Filter by status:

```bash
curl -X GET "http://localhost:8000/api/tasks?status=pending" \
  -H "Accept: application/json"
```

> Tasks are sorted by priority (`high → low`) then by `due_date` ascending.

---

### 3. Update task status

```bash
curl -X PATCH http://localhost:8000/api/tasks/1/status \
  -H "Accept: application/json" \
  -H "Content-Type: application/json" \
  -d '{
    "status": "in_progress"
  }'
```

> **Status progression:** `pending → in_progress → done`
> Cannot skip or revert status.

---

### 4. Delete a task

```bash
curl -X DELETE http://localhost:8000/api/tasks/1 \
  -H "Accept: application/json"
```

> Only tasks with status `done` can be deleted. Otherwise returns `403 Forbidden`.

---

### 5. Daily report (bonus)

```bash
curl -X GET "http://localhost:8000/api/tasks/report?date=2026-04-01" \
  -H "Accept: application/json"
```

---

## Task Status Progression

Tasks follow a strict status flow:

```
pending → in_progress → done
```

> Skipping or reverting a status is not allowed and will return a validation error.

---

## Deploying to Railway

[Railway](https://railway.com) is a cloud platform that makes it easy to deploy Laravel apps with a managed MySQL database. It detects your project automatically from GitHub and handles building and running the app.

### Prerequisites
- A [Railway](https://railway.com) account connected to GitHub
- Your project pushed to a GitHub repository

---

### Step 1 — Create a Railway Project

1. Go to [railway.com](https://railway.com) → **New Project**
2. Select **"Deploy from GitHub repo"**
3. Find and select your repository → click **"Deploy Now"**

> The first deploy may fail — that is expected since environment variables haven't been set yet.

---

### Step 2 — Add MySQL Database

1. Inside your project dashboard click **"New"**
2. Select **"Database"** → **"Add MySQL"**
3. Wait ~30 seconds for Railway to provision it

You should now see two services in your project:
```
📦 Laravel App
🗄️  MySQL
```

---

### Step 3 — Configure the Builder

1. Click your **Laravel App service** → **Settings**
2. Under the **Build** section set the builder to **Nixpacks**

> **What is `nixpacks.toml`?**
> It is a configuration file in the project root that tells Railway exactly how to build and run your app — which PHP version and extensions to install, how to run Composer, and how to start the server. The project includes this file so Railway builds correctly without any manual configuration.

---

### Step 4 — Generate a Public Domain

1. Click your **Laravel App service** → **Settings**
2. Scroll to **Networking** → click **"Generate Domain"**

Copy the generated URL — you will need it for `APP_URL`.

---

### Step 5 — Set Environment Variables

Click your **Laravel App service** → **Variables** → **Raw Editor** and paste:

```env
APP_NAME=TaskManagementAPI
APP_ENV=production
APP_KEY=base64:your-generated-key-here
APP_DEBUG=false
APP_URL=https://your-app.up.railway.app

LOG_CHANNEL=stderr

DB_CONNECTION=mysql
DB_HOST=mysql.railway.internal
DB_PORT=3306
DB_DATABASE=railway
DB_USERNAME=root
DB_PASSWORD=your-mysql-password

NIXPACKS_PHP_VERSION=8.3
```

**Getting the values:**

- `APP_KEY` — run this locally and copy the output:
```bash
php artisan key:generate --show
```
- `APP_URL` — paste your Railway domain from Step 4
- `DB_PASSWORD` — go to your **MySQL service → Variables tab** and copy the `MYSQLPASSWORD` value

Click **Save** — Railway will redeploy automatically.

---

### Step 6 — Verify Deployment

Go to **Deployments** → click the latest deployment → **View Logs**.

Look for these success indicators:
```
Running migrations...
Seeding TaskSeeder...
Server started on 0.0.0.0:PORT
```

Visit your Railway URL to confirm the app is running. ✅

---

## Testing the API on Railway (cURL)

Replace `https://your-app.up.railway.app` with your actual Railway URL.

---

### 1. Create a task

```bash
curl -X POST https://your-app.up.railway.app/api/tasks \
  -H "Accept: application/json" \
  -H "Content-Type: application/json" \
  -d '{
    "title": "Fix login bug",
    "due_date": "2026-04-10",
    "priority": "high"
  }'
```

---

### 2. List all tasks

```bash
curl -X GET https://your-app.up.railway.app/api/tasks \
  -H "Accept: application/json"
```

Filter by status:

```bash
curl -X GET "https://your-app.up.railway.app/api/tasks?status=pending" \
  -H "Accept: application/json"
```

---

### 3. Update task status

```bash
curl -X PATCH https://your-app.up.railway.app/api/tasks/1/status \
  -H "Accept: application/json" \
  -H "Content-Type: application/json" \
  -d '{
    "status": "in_progress"
  }'
```

---

### 4. Delete a task

```bash
curl -X DELETE https://your-app.up.railway.app/api/tasks/1 \
  -H "Accept: application/json"
```

---

### 5. Daily report (bonus)

```bash
curl -X GET "https://your-app.up.railway.app/api/tasks/report?date=2026-04-01" \
  -H "Accept: application/json"
```

---

## API Response Examples

### Successful task creation (`201 Created`)

```json
{
    "id": 1,
    "title": "Fix login bug",
    "due_date": "2026-04-10",
    "priority": "high",
    "status": "pending",
    "created_at": "2026-04-01T10:00:00.000000Z",
    "updated_at": "2026-04-01T10:00:00.000000Z"
}
```

### List tasks response (`200 OK`)

```json
[
    {
        "id": 1,
        "title": "Fix login bug",
        "due_date": "2026-04-10",
        "priority": "high",
        "status": "pending",
        "created_at": "2026-04-01T10:00:00.000000Z",
        "updated_at": "2026-04-01T10:00:00.000000Z"
    }
]
```

### No tasks found (`200 OK`)

```json
{
    "message": "No tasks found.",
    "data": []
}
```

### Daily report response (`200 OK`)

```json
{
    "date": "2026-04-01",
    "summary": {
        "high": {"pending": 2, "in_progress": 1, "done": 0},
        "medium": {"pending": 1, "in_progress": 0, "done": 3},
        "low": {"pending": 0, "in_progress": 0, "done": 1}
    }
}
```

### Validation error (`422 Unprocessable Entity`)

```json
{
    "message": "The title has already been taken for this due date.",
    "errors": {
        "title": ["The title has already been taken for this due date."]
    }
}
```

### Invalid status transition (`422 Unprocessable Entity`)

```json
{
    "message": "Invalid status transition. Tasks must follow: pending → in_progress → done"
}
```

### Delete non-done task (`403 Forbidden`)

```json
{
    "message": "Only tasks with status 'done' can be deleted."
}
```

### Task not found (`404 Not Found`)

```json
{
    "message": "Task not found."
}
```

---

## Environment Variables Reference

| Variable | Description |
|----------|-------------|
| `APP_NAME` | Application name |
| `APP_ENV` | Set to `production` on Railway |
| `APP_KEY` | Laravel encryption key — generate with `php artisan key:generate --show` |
| `APP_DEBUG` | Set to `false` in production |
| `APP_URL` | Your Railway public URL |
| `LOG_CHANNEL` | Set to `stderr` so logs appear in Railway console |
| `DB_CONNECTION` | Set to `mysql` |
| `DB_HOST` | Set to `mysql.railway.internal` on Railway |
| `DB_PORT` | Set to `3306` |
| `DB_DATABASE` | Set to `railway` on Railway |
| `DB_USERNAME` | Set to `root` on Railway |
| `DB_PASSWORD` | Copy from MySQL service variables on Railway |
| `NIXPACKS_PHP_VERSION` | Set to `8.3` to force correct PHP version |
