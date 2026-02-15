# OAuth2 Passport Server

**Production Deployment Guide**

---

## 🧑‍💻 Overview

This document explains how to deploy the **OAuth2 Passport Server** in a **production environment** using **Docker**, **Docker Compose**, and **Nginx** as a reverse proxy.

The system is designed to be **modular, configurable, and production-ready**, with all critical services managed from a **centralized configuration** within the application.

---

## ✅ Prerequisites

Before starting, make sure you have:

- [Docker](https://docs.docker.com/get-docker/)
- [Docker Compose](https://docs.docker.com/compose/install/)
- [Nginx](https://nginx.org/) (used as a reverse proxy)
- A **running Redis instance** (required for queues and Horizon)

---

## 🌿 Branch Strategy

- **main** — _Stable_
  Reflects the latest production-ready stable version.

- **vx.x.x** — _Versions_
  Tags following semantic versioning for stable releases.

- **dev** — _Development_
  Contains the latest changes for testing.
  **Must not be used in production.**

---

## ⚙️ System Configuration (Redis, Captcha, etc.)

The system is designed so that **all key configurations are fully parameterizable** and managed from a **central configuration area** within the application.

From this section you can configure:

- Redis
- CAPTCHA providers
- External services
- Internal system parameters

All configurations are **decoupled from the code**, allowing system behavior to be adjusted without modifying internal logic.

---

## 🚀 Deployment Setup

### 1️⃣ Clone the Repository

```bash
git clone git@github.com:elyerr/oauth2-passport-server.git
cd oauth2-passport-server
```

---

### 2️⃣ Environment Configuration

Create the production environment file:

```bash
cp .env.example .env
```

Edit `.env` and adjust production values:

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
DB_USERNAME=<user>
DB_PASSWORD=<very-secure-password>
```

> ⚠️ Leave `APP_KEY` empty. The system will generate it automatically on first startup.

---

## 🐳 Application Deployment (Production)

Run the deployment with:

```bash
./deploy-prod.sh
```

This script will:

- Build containers
- Start services
- Initialize system configuration
- Prepare the application for production

---

## 🚀 Container Execution Script (`./run`)

The `./run` script was created to simplify running commands inside the application container without manually typing `docker exec`.

The script executes commands as the `www-data` user, ensuring proper permissions within the environment.

```bash
#!/bin/bash
docker exec -it --user www-data:www-data ops-app-1 "$@"
```

### Usage

Example for running Artisan commands:

```bash
./run php artisan migrate
```

---

## 👤 First User Setup

After deploying to production, you must create the first administrator user:

```bash
./run php artisan settings:create-user
```

---

## 📦 Module Commands

The system includes several Artisan commands for module management:

| Command          | Description                                              |
| ---------------- | -------------------------------------------------------- |
| `module:install` | Install a third-party module                             |
| `module:delete`  | Delete an Elymod module and its published assets symlink |
| `module:db:seed` | Run database seeders for a specific module               |
| `module:make`    | Create a new module inside the `third-party` directory   |

⚠️ **Important (Production)**
The `module:make` command is intended **for development only**.
It is not recommended to run this command in production environments, as it is designed for creating and developing new modules rather than operational use.

---

## ✅ Production Recommendation

In production environments you will typically only need to execute commands using the following approach:

```bash
./run php artisan r:l
./run php artisan list
./run php artisan module:install
./run php artisan module:delete
./run php artisan module:db:seed
```

---

## 🔄 Updating to a New Version

To update the system in production:

```bash
git pull origin main
./deploy-prod.sh
```

---

## 🔴 Redis and Queue Processing (Horizon)

Redis is a **mandatory production component** for:

- Queues
- Background jobs
- Asynchronous processing

> ⚠️ In production, **Redis is NOT started via Docker**.
> This is intentional, since the user may have:
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

### Horizon Behavior

Once Redis and queues are configured:

- **Laravel Horizon starts working automatically**
- All queues will be dispatched and processed using Redis
- No additional Docker or code configuration is required

This enables:

- Background jobs
- Asynchronous processes
- Efficient task execution

> 💡 Without Redis properly configured, Horizon will not be able to process queues.

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

## 💳 Payment Methods

### Stripe

- **Webhook (POST):** `https://domain.com/webhook/stripe`
- Supported events:
    - `checkout.session.completed`
    - `payment_intent.payment_failed`
    - `checkout.session.expired`
    - `charge.succeeded`
    - `charge.refund.updated`

---

### Offline Payments

- Compatible with manual payments
- Automatic renewal is disabled for offline payments

---

## 🛡 CAPTCHA Providers

### hCaptcha

Privacy-focused alternative
https://dashboard.hcaptcha.com/signup

### Cloudflare Turnstile

Verification without traditional CAPTCHA
https://dash.cloudflare.com/

---

## 📄 License

This project is licensed under the **GNU Affero General Public License (AGPL)**.

Any modification, deployment, or network use of this software **must comply with the AGPL terms**, including the obligation to provide the source code of modified versions to users interacting with the system over a network.

See the `LICENSE` file for more details.

---

© 2025 Elvis Yerel Roman Concha
Published under the **GNU Affero General Public License (AGPL)**
