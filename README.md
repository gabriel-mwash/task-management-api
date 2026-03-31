# Task Management API (Laravel)

## Overview

This is a simple Task Management application built using **Laravel (PHP)** and **MySQL**.

The system allows users to:

* Create tasks
* View and filter tasks
* Update task status with strict progression rules
* Delete completed tasks
* Generate a daily report of tasks (bonus feature)

A simple web interface is included using **Blade, vanilla JavaScript, and custom CSS**.

---

## Tech Stack

* PHP (Laravel)
* MySQL
* Blade (templating)
* Vanilla JavaScript
* CSS

---

## Database

* **Database used:** MySQL
* SQL dump included: `task_manager.sql`

---

## Setup Instructions (Local)

### 1. Clone the repository

```bash
git clone <your-repo-url>
cd task-manager
```

### 2. Install dependencies

```bash
composer install
```

### 3. Configure environment

Copy the example environment file:

```bash
cp .env.example .env
```

Update the following in `.env`:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=task_manager
DB_USERNAME=your_username
DB_PASSWORD=your_password
```

---

### 4. Generate application key

```bash
php artisan key:generate
```

---

### 5. Run migrations

```bash
php artisan migrate
```

---

### 6. (Optional) Seed the database with sample tasks

```bash
php artisan db:seed
```

OR reset and seed in one step:

```bash
php artisan migrate:fresh --seed
```

---

### 7. Start the application

```bash
php artisan serve
```

Open in browser:

```text
http://127.0.0.1:8000
```

---

## API Endpoints

### Create Task

```bash
POST /api/tasks
```

Example:

```bash
curl -X POST http://127.0.0.1:8000/api/tasks \
-H "Content-Type: application/json" \
-H "Accept: application/json" \
-d '{
  "title": "Example Task",
  "due_date": "2026-04-01",
  "priority": "high"
}'
```

---

### List Tasks

```bash
GET /api/tasks
GET /api/tasks?status=pending
```

---

### Update Task Status

```bash
PATCH /api/tasks/{id}/status
```

Example:

```bash
curl -X PATCH http://127.0.0.1:8000/api/tasks/1/status \
-H "Content-Type: application/json" \
-H "Accept: application/json" \
-d '{"status":"in_progress"}'
```

---

### Delete Task

```bash
DELETE /api/tasks/{id}
```

Note:

* Only tasks with status `done` can be deleted

---

### Daily Report (Bonus)

```bash
GET /api/tasks/report?date=YYYY-MM-DD
```

Example:

```bash
curl "http://127.0.0.1:8000/api/tasks/report?date=2026-04-01" \
-H "Accept: application/json"
```

---

## Business Rules

* Task titles cannot be duplicated for the same due date
* Due date cannot be in the past
* Status progression is strictly enforced:

  ```
  pending → in_progress → done
  ```
* Tasks cannot skip or revert status
* Only completed (`done`) tasks can be deleted

---

## Deployment (Railway)

### Steps

1. Push project to GitHub
2. Create a Railway account
3. Create a new project from GitHub repo
4. Add a MySQL service
5. Configure environment variables in Railway:

```env
APP_ENV=production
APP_DEBUG=false
APP_URL=https://your-app-url

DB_CONNECTION=mysql
DB_HOST=...
DB_PORT=3306
DB_DATABASE=...
DB_USERNAME=...
DB_PASSWORD=...
```

6. Run migrations:

```bash
php artisan migrate
```

---

## Live Demo

(Replace after deployment)

```text
https://your-live-url
```

---

## Notes

* Built using Laravel without any CMS
* No JavaScript framework was used
* Interface uses Blade and vanilla JavaScript
* Seeders are included for quick setup of sample data
* API follows REST principles and returns JSON responses

---

## Author

Gabriel Mwashighadi Mtui
