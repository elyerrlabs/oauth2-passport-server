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

This document describes how to deploy the **OAuth2 Passport Server** in a **production environment** using **Docker**, **Docker Compose**, and **Nginx** as a reverse proxy.

The system is designed to be **modular, configurable, and production-ready**, with all critical services managed via centralized configuration inside the application.

---

## ✅ Prerequisites

Before starting, make sure you have the following installed:

* [Docker](https://docs.docker.com/get-docker/)
* [Docker Compose](https://docs.docker.com/compose/install/)
* [Nginx](https://nginx.org/) (used as a reverse proxy)
* A running **Redis instance** (required for queues and Horizon)

---

## 🌿 Branching Strategy

* **main** — *Stable*
  Reflects the latest stable production-ready version.

* **vx.x.x** — *Release Tags*
  Semantic versioning tags for stable releases.

* **dev** — *Development*
  Contains the latest experimental changes.
  **Not intended for production use.**

---

## ⚙️ System Configuration (Redis, Captcha, etc.)

The system is designed so that **all key configurations are fully parameterized** and managed from a **centralized configuration area** within the application.

From this section, you can configure:

* Redis
* CAPTCHA providers
* External services
* Internal system parameters

All configurations are **decoupled from the codebase**, allowing system behavior to be adjusted without modifying internal logic.

---

## 🚀 Deployment Configuration

### 1️⃣ Clone the Repository

```bash
git clone git@github.com:elyerr/oauth2-passport-server.git
cd oauth2-passport-server
```

---

### 2️⃣ Environment Configuration

Create your production environment file:

```bash
cp .env.example .env
```

Edit `.env` and configure production values:

```env
APP_ENV=production
APP_KEY=
APP_DEBUG=false
APP_URL=https://<your-domain.com>
FRONTEND_URL="${APP_URL}"
ASSET_URL="${APP_URL}"
SCHEMA_HTTPS=https

# Logging
LOG_CHANNEL=daily
LOG_DEPRECATIONS_CHANNEL=null
LOG_LEVEL=debug

# Database
DB_CONNECTION=pgsql
DB_HOST=db
DB_PORT=5432
DB_DATABASE=oauth2
DB_USERNAME=<set-username>
DB_PASSWORD=<strong-password>
```

> ⚠️ Leave `APP_KEY` empty. It will be generated automatically on first startup.

---

## 🐳 Application Deployment (Production)

Deploy the application using:

```bash
./deploy-prod.sh
```

This script will:

* Build containers
* Start services
* Initialize system settings
* Prepare the application for production use

---

## 👤 Initial User Setup

After deployment, create the first administrative user:

```bash
docker exec -it ops-app-1 php artisan settings:create-user
```

---

## 🔄 Updating to a New Version

To update the application in production:

```bash
git pull origin main
./deploy-prod.sh
```

---

## 🔴 Redis & Queue Processing (Horizon)

Redis is a **mandatory component in production** when using background jobs, queues, or asynchronous processing.

> ⚠️ **Redis is NOT provided via Docker in production**.
> This is intentional, as users may already have:
>
> * A dedicated Redis server
> * A shared Redis instance
> * A managed Redis service

---

### Redis Requirements

Ensure you have:

* A running Redis instance
* Network access from the application container
* Host, port, and credentials (if applicable)

Redis may be hosted on:

* The same server
* A private internal network
* A managed cloud provider

---

### Redis Configuration

1. Go to **Admin Panel → Configuration → Redis**
2. Configure the Redis connection:

```text
Host: <redis-host>
Port: 6379
Password: <optional>
```

Save the configuration.

---

### Queue Configuration

To enable background job processing:

1. Navigate to **Configuration → Queues**
2. Change the queue driver from `database` to `redis`
3. Save the changes

---

### Horizon Behavior

Once Redis and queues are configured:

* **Laravel Horizon starts operating automatically**
* All queues are dispatched and processed via Redis
* No additional Docker or code configuration is required

This enables:

* Background jobs
* Asynchronous processing
* High-performance task execution

> 💡 Redis is required for Horizon to operate correctly.
> Without Redis, queues will not be processed.

---

## 🌐 Nginx Reverse Proxy (Example)

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

## 🔐 OAuth2 Key Regeneration

```bash
php artisan passport:keys --force
```

---

## 💳 Payment Methods

### Stripe

* **Webhook (POST):** `https://domain.com/webhook/stripe`
* Supported events:

  * `checkout.session.completed`
  * `payment_intent.payment_failed`
  * `checkout.session.expired`
  * `charge.succeeded`
  * `charge.refund.updated`

---

### Offline Payments

* Manual/offline payment methods supported
* Auto-renewal is disabled for offline payments

---

## 🛡 CAPTCHA Providers

### hCaptcha

Privacy-focused CAPTCHA alternative
[https://dashboard.hcaptcha.com/signup](https://dashboard.hcaptcha.com/signup)

### Cloudflare Turnstile

CAPTCHA-free user verification
[https://dash.cloudflare.com/](https://dash.cloudflare.com/)

---

## 📄 License

This project is licensed under the **GNU Affero General Public License (AGPL)**.

Any modification, deployment, or network use of this software **must comply with the terms of the AGPL**, including making the source code of modified versions available to users who interact with the software over a network.

See the `LICENSE` file for full details.

---

© 2025 Elvis Yerel Roman Concha
Released under the **GNU Affero General Public License (AGPL)**
