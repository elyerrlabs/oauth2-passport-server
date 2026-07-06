<!--
OAuth2 Passport Server — a centralized, modular authorization server
implementing OAuth 2.0 and OpenID Connect specifications.

Copyright (c) 2026 Elvis Yerel Roman Concha

This program is free software: you can redistribute it and/or modify
it under the terms of the GNU Affero General Public License as published by
the Free Software Foundation, either version 3 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU Affero General Public License for more details.

You should have received a copy of the GNU Affero General Public License
along with this program. If not, see <https://www.gnu.org/licenses/>.

Author: Elvis Yerel Roman Concha
Contact: yerel9212@yahoo.es

SPDX-License-Identifier: AGPL-3.0-or-later
-->

# OAuth2 Passport Server

**Production Deployment Guide**

---

## 🧑‍💻 Overview

This document explains how to deploy the **OAuth2 Passport Server** in a **production environment** using **Docker**, **Docker Compose**, and **Nginx** as a reverse proxy.

The system is designed to be **modular, configurable, and production-ready**, with all critical services managed from a **centralized configuration system** within the application.

---

## ✅ Prerequisites

Before getting started, make sure you have:

- [Docker](https://docs.docker.com/get-docker/)
- [Docker Compose](https://docs.docker.com/compose/install/)
- [Nginx](https://nginx.org/) (used as a reverse proxy)
- A **running Redis instance** (required for queues and Horizon)

---

## 🌿 Branch Strategy

- **main** — _Stable_  
  Reflects the latest stable production-ready version.

- **vx.x.x** — _Releases_ (recommended)  
  Tags following semantic versioning for stable releases.

- **staging** — _Testing_  
  Contains the latest changes for testing purposes.  
  Used to validate updates in a staging environment and detect issues before deploying to real production environments.  
  **Should not be used in production.**

- **dev** — _Development_  
  Contains the latest development changes and experimental features.  
  **Should not be used in production.**

---

## ⚙️ System Configuration (Redis, Captcha, etc.)

The system is designed so that **all key configurations are fully parameterized** and managed from a **centralized configuration area** inside the application.

From this section you can configure:

- Redis
- CAPTCHA providers
- External services
- Internal system parameters

All configurations are **decoupled from the source code**, allowing the system behavior to be adjusted without modifying internal logic.

---

## 🚀 Deployment Configuration

### 1️⃣ Clone the Repository

```bash
git clone -b main --single-branch git@github.com:elyerr/oauth2-passport-server.git
cd oauth2-passport-server
```

---

### 2️⃣ Environment Configuration

Create the production environment file.

Keep in mind that this `.env` file is only required during the initial project startup.  
After the system starts, it creates an `env` volume and stores a copy of the original environment file there.

The application will always prioritize the environment file stored in the volume.  
If the volume copy is deleted, the system falls back to the original `.env` file.

Therefore, if you want to modify environment variables after deployment, it is recommended to edit the `.env` file directly from inside the container.

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

> ⚠️ Leave `APP_KEY` empty. The system will automatically generate it during the first startup.

---

## 🐳 Application Deployment (Production)

Run the deployment script:

- Production

```bash
./production --deploy
```

- Staging

```bash
./staging --deploy
```

This script is responsible for:

- Building containers
- Starting services
- Initializing system configurations
- Preparing the application for production

---

## 🚀 Container Execution Script

| Command                    | Description                                                        |
| -------------------------- | ------------------------------------------------------------------ |
| `./dev --deploy`           | Deploy the development environment.                                |
| `./dev --stop`             | Stop the development containers.                                   |
| `./dev --root bash`        | Open a root shell in the development container.                    |
| `./dev bash`               | Open a shell with the development environment user.                |
| `./staging --deploy`       | Deploy the staging environment.                                    |
| `./staging --stop`         | Stop the staging containers.                                       |
| `./staging --root bash`    | Open a root shell in the staging container.                        |
| `./staging bash`           | Open a shell with the staging environment user.                    |
| `./production --deploy`    | Deploy the production environment.                                 |
| `./production --stop`      | Stop the production containers.                                    |
| `./production --root bash` | Open a root shell in the production container.                     |
| `./production bash`        | Open a shell with the production environment user.                 |

### Root Access

Use the `--root` option to execute commands as the root user inside the container:

```bash
./dev --root bash
./staging --root bash
./production --root bash
```

These scripts were created to simplify command execution inside the application container without manually typing `docker exec`.

The script executes commands as the `www-data` user, ensuring proper permissions inside the environment.

---

## 👤 Initial User Setup

After deploying the application in production, you must create the first administrator user:

```bash
./production php artisan settings:create-user
```

---

## 📦 Module Commands

The system includes several Artisan commands for module management:

| Command          | Description                                             |
| ---------------- | ------------------------------------------------------- |
| `module:install` | Installs a third-party module                           |
| `module:delete`  | Removes an Elymod module and its assets symbolic link   |
| `module:db:seed` | Executes database seeders for a specific module         |
| `module:make`    | Creates a new module inside the `third-party` directory |

⚠️ **Important (Production)**  
The `module:make` command is intended **for development purposes only**.  
It is not recommended to run it in production environments since it is designed for creating and developing new modules rather than operational usage.

---

## ✅ Production Recommendation

In production environments, you will normally only need to execute commands like:

```bash
./production php artisan r:l
./production php artisan list
./production php artisan module:install
./production php artisan module:delete
./production php artisan module:db:seed
```

---

## 🔄 Updating to a New Version

To update the system in production:

```bash
git pull origin main
./production --deploy
```

---

## 🔴 Redis and Queue Processing (Horizon)

Redis is a **mandatory production component** for:

- Queues
- Background jobs
- Asynchronous processing

> ⚠️ In production, Redis is **NOT started through Docker**.  
> This is intentional because users may already have:
>
> - A dedicated Redis server
> - A shared Redis instance
> - A managed Redis cloud provider

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

1. Open the **Administration Panel**
2. Navigate to **Settings → Redis**
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

1. Navigate to **Settings → Queues**
2. Change the queue driver from `database` to `redis`
3. Save the configuration

---

### Horizon Behavior

Once Redis and queues are configured:

- **Laravel Horizon starts automatically**
- All queues will be dispatched and processed using Redis
- No additional Docker or code configuration is required

This enables:

- Background jobs
- Asynchronous processing
- Efficient task execution

> 💡 Without Redis properly configured, Horizon will not be able to process queues.
>
> Note: if everything is configured correctly and Horizon does not immediately detect the changes, wait around 30 minutes.  
> If it still does not restart automatically, the simplest solution is restarting the container using:
>
> ```bash
> ./production --deploy
> ```
>
> or:
>
> ```bash
> ./staging --deploy
> ```
>
> depending on the environment.  
> This refreshes all configurations and services.

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

Privacy-focused CAPTCHA alternative:

https://dashboard.hcaptcha.com/signup

### Cloudflare Turnstile

Verification without traditional CAPTCHA:

https://dash.cloudflare.com/

---

## 📄 License

This project is licensed under the **GNU Affero General Public License (AGPL)**.

Any modification, deployment, or network use of this software **must comply with the AGPL terms**, including the obligation to make the source code of modified versions available to users interacting with the system over a network.

See the `LICENSE` file for more details.

---

© 2026 Elvis Yerel Roman Concha  
Released under the **GNU Affero General Public License (AGPL)**
