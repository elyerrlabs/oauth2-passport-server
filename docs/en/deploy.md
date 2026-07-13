# OAuth2 Passport Server

**Production Deployment Guide**

---

## 🧑‍💻 Overview

This document describes how to deploy the **OAuth2 Passport Server** in a **production environment** using **Docker**, **Docker Compose**, and **Nginx** as a reverse proxy.

The system is designed to be **modular, configurable, and production-ready**, with all critical services managed from a **centralized configuration** within the application.

---

## ✅ Prerequisites

Before you begin, make sure you have:

- [Docker](https://docs.docker.com/get-docker/)
- [Docker Compose](https://docs.docker.com/compose/install/)
- [Nginx](https://nginx.org/) (used as a reverse proxy)
- A **running Redis instance** (required for queues and Horizon)

---

## 🌿 Branch Strategy

- **main** — _Stable_
  Reflects the latest stable version ready for production.

- **vx.x.x** — _Versions_ (recommended)
  Tags following semantic versioning for stable releases.

- **staging** — _Testing_
  Contains the latest changes for testing. Used to review changes in a testing environment and detect errors before moving to real production environments and fixing any issues.
  **Should not be used in production.**

- **dev** — _Development_
  Contains the latest changes for testing.
  **Should not be used in production.**

---

## ⚙️ System Configuration (Redis, Captcha, etc.)

The system is designed so that **all key configurations are fully parameterizable** and managed from a **central configuration zone** within the application.

From this section you can configure:

- Redis
- CAPTCHA providers
- External services
- Internal system parameters

All configurations are **decoupled from the code**, allowing you to adapt the system's behavior without modifying the internal logic.

---

## 🚀 Deployment Setup

### 1️⃣ Clone the Repository

```bash
git clone -b main --single-branch git@github.com:elyerr/oauth2-passport-server.git
cd oauth2-passport-server
```

---

### 2️⃣ Environment Configuration

Create the environment file for production: keep in mind that this `.env` file will only be needed once to start the project. After starting the system, it will create an `env` volume and store a copy of the original. It will always prioritize the volume's copy, and if it is deleted, it will look for the original. So, if you want to make a change to the `.env` after starting the project, you should modify it from within the container and edit what you need to edit.

```bash
cp .env.example .env
```

Edit `.env` and adjust the production values:

```env
APP_ENV=production
APP_KEY=
APP_DEBUG=false
APP_URL=https://<your-domain.com>
APP_URL_SCHEME=https

# Logs
LOG_CHANNEL=daily
LOG_DEPRECATIONS_CHANNEL=null
LOG_LEVEL=debug

# Database
DB_CONNECTION=pgsql
DB_HOST=db
DB_PORT=5432
DB_DATABASE=oauth2
DB_USERNAME=<username>
DB_PASSWORD=<very-secure-password>
```

> ⚠️ Leave `APP_KEY` empty. The system will generate it automatically on first boot.

---

## 🐳 Application Deployment (Production)

Run the deployment with:

- for production

```bash
./production --deploy
```

- for staging

```bash
./staging --deploy
```

This script handles:

- Building containers
- Starting services
- Initializing system configurations
- Preparing the application for production

---

## 🚀 Container Execution Scripts

| Command                    | Description                                             |
| -------------------------- | ------------------------------------------------------- |
| `./dev --deploy`           | Deploys the development environment.                    |
| `./dev --stop`             | Stops the development containers.                       |
| `./dev --root bash`        | Opens a root terminal in the development container.     |
| `./dev bash`               | Opens a terminal with the development environment user. |
| `./staging --deploy`       | Deploys the staging environment.                        |
| `./staging --stop`         | Stops the staging containers.                           |
| `./staging --root bash`    | Opens a root terminal in the staging container.         |
| `./staging bash`           | Opens a terminal with the staging environment user.     |
| `./production --deploy`    | Deploys the production environment.                     |
| `./production --stop`      | Stops the production containers.                        |
| `./production --root bash` | Opens a root terminal in the production container.      |
| `./production bash`        | Opens a terminal with the production environment user.  |

### Root Access

Use the `--root` option to run commands as the root user inside the container:

```bash
./dev --root bash
./staging --root bash
./production --root bash
```

These scripts have been created to make it easier to run commands inside the application container without having to manually write `docker exec`.

The script runs commands as the `www-data` user, ensuring proper permissions in the environment.

---

## 👤 First User Setup

After deploying to production, you must create the first admin user:

```bash
./production php artisan settings:create-user
```

---

## 📦 Module Commands

The system includes several Artisan commands for module management:

| Command          | Description                                             |
| ---------------- | ------------------------------------------------------- |
| `module:install` | Installs a third-party module                           |
| `module:delete`  | Deletes an Elymod module and its assets symlink         |
| `module:db:seed` | Runs the database seeders for a specific module         |
| `module:make`    | Creates a new module inside the `third-party` directory |

⚠️ **Important (Production)**
The `module:make` command is designed **for development only**.
It is not recommended to run it in production environments, as it is intended for creating and developing new modules, not for operational use.

---

## ✅ Recommendation for Production

In production environments, you will typically only need to run commands using the following approach:

```bash
./production php artisan r:l
./production php artisan list
./production php artisan module:install
./production php artisan module:delete
./production php artisan module:db:seed
```

---

## 🔄 Updating to a New Version

It is recommended to **always update using a stable version (tag)** instead of the `main` branch.

The `main` branch contains the latest changes to the project and, although they are usually stable, they may include new features or breaking changes. Updating directly from `main` might require additional migrations or changes to your project configuration.

The recommended practice is to update **from one stable version to the next**, always reviewing the release notes before proceeding.

For example, if you are currently using version `8.0.0`, first update to `8.0.1` and then to `9.0.0`, instead of jumping directly to the latest available version.

### 1. Download the latest repository references

```bash
git fetch --all --tags
```

### 2. Switch to the desired version

```bash
git checkout v9.0.0
```

### 3. Deploy the update

```bash
./production --deploy
```

> **Important:** Avoid using `git pull origin main` on production servers. Whenever possible, deploy a stable version identified by a **tag**, as this ensures that the running code corresponds exactly to a published version and makes it easier to revert to a previous version if necessary.

---

## 🔴 Redis and Queue Processing (Horizon)

Redis is a **mandatory component in production** for using:

- Queues
- Background jobs
- Asynchronous processes

> ⚠️ In production, **Redis is NOT started via Docker**.
> This is intentional, as the user may have:
>
> - A dedicated Redis server
> - A shared Redis instance
> - Redis managed by an external provider

---

### Redis Requirements

Make sure you have:

- A running Redis instance
- Network access from the application container
- Host, port, and credentials (if applicable)

Redis can be hosted on:

- The same server
- An internal network
- A managed cloud provider

---

### Redis Configuration

1. Access the **Admin Panel**.
2. Go to **Settings → Redis**.
3. Configure the connection values:

```text
Host: <redis-server-host>
Port: 6379
Username: <optional>
Password: <optional>
```

Save the changes.

---

### Queue Configuration

To enable queue processing:

1. Go to **Settings → Queues**.
2. Change the **queue driver** from `database` to `redis`.
3. Save the configuration.

---

### How Horizon Works

Once Redis and queues are configured:

- **Laravel Horizon starts automatically**
- All queues will be dispatched and processed using Redis
- No additional configuration is required in Docker or code

This enables:

- Background jobs
- Asynchronous processes
- Efficient task execution

> 💡 Without Redis properly configured, Horizon will not be able to process queues.
> Note: if everything is configured correctly and Horizon does not detect the changes immediately, wait about 30 minutes. If it still doesn't restart, the simplest way is to restart the container with `./production --deploy` or `./staging --deploy`, as appropriate. This will refresh all changes.

---

## 🌐 Nginx Configuration (Example)

```nginx
server {
    listen 80;
    server_name <your-domain.com>;

    location / {
        proxy_pass http://127.0.0.1:8001;
        proxy_http_version 1.1;

        proxy_set_header Host $host;
        proxy_set_header X-Real-IP $remote_addr;
        proxy_set_header X-Forwarded-For $proxy_add_x_forwarded_for;
        proxy_set_header X-Forwarded-Proto $scheme;
        proxy_set_header X-Forwarded-Host $http_x_forwarded_host;
        proxy_set_header X-Forwarded-Port $http_x_forwarded_port;

        proxy_read_timeout 720s;
        proxy_connect_timeout 720s;
        proxy_send_timeout 720s;

        proxy_redirect off;
    }
}
```

---

## 🔐 Regenerate OAuth2 Keys

```bash
php artisan passport:keys --force
```

---

## 🛡 CAPTCHA Providers

### hCaptcha

Privacy-focused alternative
[https://dashboard.hcaptcha.com/signup](https://dashboard.hcaptcha.com/signup)

### Cloudflare Turnstile

CAPTCHA-free verification
[https://dash.cloudflare.com/](https://dash.cloudflare.com/)
