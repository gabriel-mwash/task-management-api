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
- SQL dump included: `task_manager.sql`

---

## Running Locally

### 1. Clone the repository

```bash
git clone <your-repo-url>
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

## Deploying to Railway

[Railway](https://railway.com) is a cloud platform that makes it easy to deploy Laravel apps with a MySQL database.

### Prerequisites
- A [Railway](https://railway.com) account connected to GitHub
- Your project pushed to a GitHub repository

---

### Step 1 — Create a Railway Project

1. Go to [railway.com](https://railway.com) → **New Project**
2. Select **"Deploy from GitHub repo"**
3. Select your repository → click **"Deploy Now"**

---

### Step 2 — Add MySQL Database

1. Inside your project dashboard click **"New"**
2. Select **"Database"** → **"Add MySQL"**
3. Railway will automatically provision a MySQL instance

You should now see two services:
```
📦 Laravel App
🗄️  MySQL
```

---

### Step 3 — Configure the Builder

1. Click your **Laravel App service** → **Settings**
2. Under the **Build** section set the builder to **Nixpacks**

> **What is `nixpacks.toml`?**
> It's a configuration file placed in the project root that tells Railway exactly how to build and start your app — which PHP version to use, which extensions to install, how to run composer, and how to start the server. Without it, Railway guesses and often gets it wrong.

The project includes a `nixpacks.toml` that handles all of this automatically.

---

### Step 4 — Generate a Public Domain

1. Click your **Laravel App service** → **Settings**
2. Scroll to **Networking** → click **"Generate Domain"**

Copy the generated URL (e.g. `https://your-app.up.railway.app`).

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

- `APP_KEY` — run locally: `php artisan key:generate --show`
- `APP_URL` — your Railway domain from Step 4
- `DB_PASSWORD` — copy `MYSQLPASSWORD` from your MySQL service → Variables tab

Click **Save** — Railway will redeploy automatically.

---

### Step 6 — Verify

Go to **Deployments** → click the latest → **View Logs**. Look for:

```
Running migrations...
Seeding TaskSeeder...
Server started on 0.0.0.0:PORT
```

Visit your Railway URL to confirm everything is working. ✅

---

## Example API Requests

Base URL: `https://your-app.up.railway.app`

### Get all tasks
```http
GET /api/tasks
```

### Create a task
```http
POST /api/tasks
Content-Type: application/json

{
    "title": "Fix login bug",
    "description": "Users can't log in on mobile",
    "status": "pending"
}
```

### Get a single task
```http
GET /api/tasks/{id}
```

### Update a task
```http
PUT /api/tasks/{id}
Content-Type: application/json

{
    "status": "in_progress"
}
```

### Delete a task
```http
DELETE /api/tasks/{id}
```

---

## Task Status Progression

Tasks follow a strict status flow:

```
pending → in_progress → completed
```

Only completed tasks can be deleted.
