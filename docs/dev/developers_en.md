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

# 🧑‍💻 Development Guide (DEV)

This guide describes **exclusively the development environment**, designed to work locally in a secure, reproducible way.

The project runs **100% on Docker**, which means:

- You do not need to install PHP, Composer, Node.js, Nginx, or extensions on your operating system.
- The entire stack (PHP, Nginx, Supervisor, Horizon, Node, etc.) runs inside containers.
- The environment is identical for all developers.

> 🎯 **Goal**: allow any developer to clone the repository and get the system running in minutes, without complex manual configuration.

---

## 🌱 Branches

- **main** → Stable branch (production)
- **dev** → Active development branch (**use this one for development**)

> ⚠️ Everything described in this guide assumes you are working on the `dev` branch.

---

## ✅ Prerequisites

You only need the following installed on your machine:

- Docker ≥ 24
- Docker Compose (official plugin)
- Git

Optional (recommended) tools:

- VS Code / PhpStorm
- DBeaver / TablePlus (for database access)

---

## 📦 Clone the Project

```sh
git clone https://github.com/elyerrlabs/oauth2-passport-server.git
cd oauth2-passport-server
git checkout dev
```

---

## ⚙️ Environment Configuration

### 1. `.env` File

Copy the example file:

```sh
cp .env.example .env
```

Minimal recommended configuration for development:

```env
APP_ENV=dev
APP_KEY=
APP_DEBUG=true
APP_URL=http://localhost:8001
SCHEMA_HTTPS=http

# LOGS
LOG_CHANNEL=daily
LOG_DEPRECATIONS_CHANNEL=null
LOG_LEVEL=debug

# DATABASE
DB_CONNECTION=pgsql
DB_HOST=db
DB_PORT=5432
DB_DATABASE=oauth2
DB_USERNAME=admin
DB_PASSWORD=admin
```

> ℹ️ `DB_HOST=db` matches the service name defined in `docker-compose-dev.yml`.

> ℹ️ `APP_KEY` is automatically generated during the first startup if left empty.

---

## 🧩 System Configuration (Redis, Captcha, etc.)

The system is designed so that **all key configurations are fully parameterized** and managed from a **centralized configuration area** within the application.

From this section, you can easily configure:

- Redis
- Captcha
- External services
- Internal system parameters

All settings are **decoupled from the codebase**, allowing the system behavior to be adjusted without modifying internal logic or source code.

---

### 🔴 Redis

The system **uses Redis as a core component** to handle internal functionalities such as:

- Cache
- Sessions
- Queues
- Other mechanisms depending on the active module

#### Redis in the development environment

In the development environment, **a Redis instance is already running inside the Dev Container**, connected through an **internal Docker network**.

This means **there is no need to install Redis on the host machine**.

#### Correct Redis configuration

To ensure Redis works correctly inside the containerized environment:

1. Navigate to **Configuration → Redis** within the application.
2. Make sure that **all references to `127.0.0.1` are replaced with `redis`**.

> ⚠️ Inside Docker, `127.0.0.1` refers to the current container.
> The correct host value is **`redis`**, which corresponds to the Redis service name defined in Docker.

Example:

```text
Host: redis
Port: 6379
```

#### Queue configuration and Horizon

To properly enable queue processing using Redis:

1. Go to **Configuration → Queues**.
2. Change the **queue driver from `database` to `redis`**.
3. Save the configuration.

With this setup, **Horizon will dispatch and process all queues using Redis**, making the system fully operational for background job handling.

---

> 💡 For development environments, keeping Redis inside the container ensures a reproducible, isolated, and production-like setup.

---

## 🐳 Development Deployment

The project includes a script that fully automates the DEV deployment.

### What does `deploy-dev.sh` do?

This script:

1. Detects your host UID and GID to avoid permission issues.
2. Validates that critical `.env` variables are defined.
3. Stops existing containers.
4. Builds the required images.
5. Starts all services using Docker Compose.
6. Installs Composer and NPM dependencies inside the container.
7. Keeps services running under Supervisor.

### Start the services

```sh
./deploy-dev.sh
```

---

## 🌐 Available Services

Once the environment is running:

- Web application:
    - 👉 [http://localhost:8001](http://localhost:8001)

- PostgreSQL (accessible from the host):
    - Host: `127.0.0.1`
    - Port: `5435`
    - Username: `admin`
    - Password: `admin`

---

## 🔧 Container Access (when needed)

In the normal workflow, **you do not need to manually enter the container**, as most tasks are performed using the `ops` helper.

However, there are **two access modes**, depending on what you need to do:

---

### 🔴 Root access (exceptional use)

This mode **should only be used for specific cases**, such as:

- Installing system packages (apk / apt)
- Testing container-level configuration
- Low-level debugging

⚠️ **Important warning**:
If you modify project files as `root`, they will be owned by root and **cannot be edited from the host**.

👉 Use this access **only for internal container tasks**, never to edit project code.

```sh
./opsr bash
```

---

### 🟢 Correct access for development (recommended)

To work with the code, run Artisan, Composer, or NPM, you **must use the host user (UID/GID)**.

During deployment (`deploy-dev.sh`), a local helper called `ops` is automatically generated and handles this correctly.

```sh
./ops bash
```

You can also run commands directly:

```sh
./ops <command>
```

Examples:

```sh
./ops php artisan
./ops composer install
./ops npm run watch
```

---

## 🛠️ Useful Development Commands

### List available Artisan commands

```sh
./ops php artisan
```

### Create system users

```sh
./ops php artisan settings:create-user
```

### Install PHP dependencies (Composer)

```sh
./ops composer install
```

> ⚠️ This is usually already handled by `deploy-dev.sh`.

### Install JavaScript dependencies

```sh
./ops npm install
```

### Compile assets and watch for changes

```sh
./ops npm run watch
```

> 💡 Ideal for frontend development with hot reload.

---

## 💳 Recurring Payments and Background Processes

The following services are already **managed by Supervisor** inside the container:

- Laravel Horizon
- Queue workers
- Recurring payments

Recurring payments command:

```sh
php artisan payment:charge-recurring
```

👉 **This runs automatically**, you do not need to execute it manually.

### Check Supervisor status

```sh
./opsr supervisorctl status
```

---

## 🧠 Important Notes

- ❌ Do not run `php artisan` or `npm` on the host.
- ✅ Everything must be executed inside the container.
- 🔄 Code changes are reflected automatically.
- 🧪 The DEV environment is intended for testing and development, not production.

---

## 🚀 Recommended Workflow

1. Clone the repository
2. Configure `.env`
3. Run `./deploy-dev.sh`
4. Open [http://localhost:8001](http://localhost:8001)
5. Start coding 🚀

---

### Module Creation

- [Read documentation](./module/modules_es.md)
