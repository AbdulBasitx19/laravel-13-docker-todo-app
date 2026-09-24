# 🐳 Dockerized Laravel Todo List App with Live Tunneling (ngrok)

A complete, modern **Laravel Todo List Application** powered by **Docker Compose**, **jQuery AJAX**, **MySQL**, **phpMyAdmin**, and **ngrok** for instant public sharing.

<Image alt="Docker Multi-Container Architecture" caption="Containerized Application Services Architecture" src="image_agent_tag_11221437047392550653" />

---

## 📌 Features

* **Zero Local Dependencies:** Runs completely inside isolated Docker containers (No local PHP, Apache, or MySQL required on host OS).
* **Interactive Single-Page Feeling:** AJAX-driven CRUD operations (Add, Mark Complete/Undo, Delete) without page reloads.
* **Integrated Database GUI:** Built-in phpMyAdmin interface for database inspection.
* **Instant Live Tunneling:** Automated `ngrok` container service to share local environment globally via dynamic secure HTTPS URL.
* **Persistent Data & Live Sync:** Docker Volumes ensure database persistence while bind mounts keep code changes instantly synced.

---

## 🛠️ Architecture & Services Stack

This multi-container infrastructure is orchestrated via `docker-compose.yml`:

| Service Name | Container Name | Base Image / Source | Exposed Port | Purpose |
| :--- | :--- | :--- | :--- | :--- |
| **app** | `todo_task_app` | Custom `Dockerfile` (`php:8.3-apache`) | `8000` | Laravel 11/12 application web server |
| **db** | `todo_task_db` | `mysql:latest` | `3306` | Primary relational database |
| **phpmyadmin**| `todo_task_phpmyadmin`| `phpmyadmin/phpmyadmin:latest` | `8888` | Web-based database management interface |
| **ngrok** | `todo_task_ngrok` | `ngrok/ngrok:latest` | `4040` | Live web tunnel for public sharing |

---

## 🚀 Quick Setup & Installation Guide

### Prerequisites
- [Docker Desktop](https://www.docker.com/products/docker-desktop/) (Windows/Mac) or Docker Engine + Docker Compose (Linux / WSL2)
- [Git](https://git-scm.com/)

---

### Step 1: Clone the Repository
```bash
git clone [https://github.com/AbdulBasitx19/laravel-13-docker-todo-app.git](https://github.com/AbdulBasitx19/laravel-13-docker-todo-app.git)
cd laravel-13-docker-todo-app
```

###  Step 2: Configure Environment Variables
Create the root .env file for Docker services (specifically for ngrok):

``` bash
touch .env
```

Add your ngrok Authtoken into the root .env file:
NGROK_AUTHTOKEN=your_actual_ngrok_authtoken_here

(Get your token from dashboard.ngrok.com)
Next, set up the Laravel application's .env file inside the src/ directory:
``` bash
cp src/.env.example src/.env
```
## Ensure database credentials inside src/.env match the Docker MySQL setup:
DB_CONNECTION=mysql
DB_HOST=db
DB_PORT=3306
DB_DATABASE=todo_task_db
DB_USERNAME=todo_task_user
DB_PASSWORD=userpassword

### Step 3: Build & Start Containers
Run the following command from the project root:
``` bash
docker compose up -d --build
```

Verify that all 4 containers are running:
``` bash
docker compose ps
```

### Step 4: Initial Application Setup
Run Composer installation, Generate App Key, and run Database Migrations inside the app container:
``` bash
# Install PHP dependencies
docker compose exec app composer install

# Generate Laravel Application Key
docker compose exec app php artisan key:generate

# Fix Linux File Permissions (WSL/Ubuntu specific)
sudo chown -R $USER:$USER src/

# Run Database Migrations
docker compose exec app php artisan migrate
```

### 🔗 Application Access URLs
Once all containers are running, you can access the services using the following URLs:

Laravel Web App (Local): http://localhost:8000

phpMyAdmin (Database GUI): http://localhost:8888

Server: db

Username: todo_task_user (or root)

Password: userpassword (or rootpassword)

ngrok Web Dashboard: http://localhost:4040

Open this dashboard to view your active Public Live URL (e.g., https://xxxx.ngrok-free.app).


### ⚡ Useful Docker Commands
Action                          Command
Start Containers                docker compose up -d
Stop Containers                 docker compose down
View Container Status           docker compose ps
View Live Logs                  docker compose logs -f
Execute Artisan Commands        docker compose exec app php artisan <command>
Access Container Shell          docker compose exec app bash

### 📁 Project Structure
├── Dockerfile              # Custom PHP 8.3 + Apache container configuration
├── docker-compose.yml      # Multi-container orchestration specification
├── .env                    # Root environment file (stores NGROK_AUTHTOKEN)
├── .gitignore              # Ignores environment files and vendor directories
└── src/                    # Complete Laravel Application Source Code
    ├── app/
    │   ├── Http/Controllers/TaskController.php # AJAX API Controller
    │   └── Models/Task.php                     # Task Model
    ├── database/migrations/                     # Database Schema
    ├── routes/web.php                           # Application Routes
    └── resources/views/tasks/index.blade.php   # Responsive jQuery AJAX Frontend
