<!--
Copyright (c) 2025 Elvis Yerel Roman Concha

This file is part of an open source project licensed under the
"NON-COMMERCIAL USE LICENSE - OPEN SOURCE PROJECT" (Effective Date: 2025-08-03).

You may use, study, modify, and redistribute this file for personal,
educational, or non-commercial research purposes only.

Commercial use is strictly prohibited without prior written consent
from the author.

Combining this software with any project licensed for commercial use
(such as AGPL) is not permitted without explicit authorization.

This software supports OAuth 2.0 and OpenID Connect.

Author Contact: yerel9212@yahoo.es

SPDX-License-Identifier: LicenseRef-NC-Open-Source-Project
-->

# 🧑‍💻 Development Guide (DEV)

This guide describes **exclusively the development environment**, designed to work locally in a safe, reproducible, and predictable way.

The project runs **100% on Docker**, which means:

-   You do **not** need to install PHP, Composer, Node.js, Nginx, or extensions on your operating system.
-   The entire stack (PHP, Nginx, Supervisor, Horizon, Node, etc.) lives inside containers.
-   The environment is identical for all developers.

> 🎯 **Goal**: any developer should be able to clone the repository and have the system running in minutes, without complex manual configuration.

---

## 🌱 Branches

-   **main** → Stable branch (production)
-   **dev** → Active development branch (**use this one for development**)

> ⚠️ Everything described in this guide assumes you are working on the `dev` branch.

---

## ✅ Prerequisites

You only need the following installed on your machine:

-   Docker ≥ 24
-   Docker Compose (official plugin)
-   Git

Optional (recommended) tools:

-   VS Code / PhpStorm
-   DBeaver / TablePlus (for database access)

---

## 📦 Clone the Project

```sh
git clone https://github.com/elyerrlabs/oauth2-passport-server.git
cd oauth2-passport-server
git checkout dev
```

---

## ⚙️ Environment Configuration

### 1. `.env` file

Copy the example file:

```sh
cp .env.example .env
```

Recommended minimal example for development:

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

> ℹ️ `APP_KEY` is generated automatically on first startup if left empty.

---

## 🐳 Development Deployment

The project includes a script that fully automates the DEV deployment.

### What does `deploy-dev.sh` do?

This script:

1. Detects your host UID and GID to avoid permission issues.
2. Validates that critical `.env` variables are defined.
3. Stops any running containers.
4. Builds the required Docker images.
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

-   Web application:

    -   👉 [http://localhost:8001](http://localhost:8001)

-   PostgreSQL (host access):

    -   Host: `127.0.0.1`
    -   Port: `5435`
    -   User: `admin`
    -   Password: `admin`

---

## 🔧 Container Access (when needed)

In the normal workflow, **you do not need to manually enter the container**, as most tasks are performed using the `ops` helper.

That said, there are **two access modes**, depending on what you need to do:

---

### 🔴 Root access (exceptional use)

This mode **should only be used in rare cases**, for example:

-   Installing system packages (apk / apt)
-   Testing container-level configurations
-   Low-level debugging

⚠️ **Important warning**:
If you modify project files as `root`, they will be owned by root and **will not be editable from the host**.

👉 Use this access **only for internal container tasks**, never for editing project code.

```sh
./opsr bash
```

---

### 🟢 Proper access for development (recommended)

To work with the code, run Artisan, Composer, or NPM, **you must use the host user (UID/GID)**.

During deployment (`deploy-dev.sh`), a local helper called `ops` is automatically generated and already handles this correctly.

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

### 🌍 (Optional) Create a global alias manually

If you want to use `ops` **from anywhere on your system**, you can create a global shell alias.

Example for **bash / zsh**:

```sh
alias ops='docker exec -it --user $(id -u):$(id -g) ops-dev-app-1'
```

To make it persistent, add that line to:

-   `~/.bashrc`
-   `~/.zshrc`

Then reload your shell:

```sh
source ~/.bashrc
# or
source ~/.zshrc
```

From then on, you can run:

```sh
ops php artisan
ops npm run watch
ops sh
```

> ℹ️ This setup is **optional**. The local `./ops` helper already covers the recommended workflow without modifying your system.

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

> 💡 Ideal for frontend development with hot-reload.

---

## 💳 Recurring Payments and Background Processes

The following services are already **managed by Supervisor** inside the container:

-   Laravel Horizon
-   Queue workers
-   Recurring payments

Recurring payment command:

```sh
php artisan payment:charge-recurring
```

👉 **This runs automatically**, you do not need to trigger it manually.

### Check Supervisor status

```sh
./opsr supervisorctl status
```

---

## 🧠 Important Notes

### 🔐 Token request during `composer install`

In some cases, when running `composer install`, Composer may request a **GitHub Access Token** while downloading dependencies.

This usually happens when:

-   Anonymous GitHub download limits are reached.
-   Composer downloads dependencies hosted on GitHub (for example, indirect dependencies like `symfony/mailgun-mailer`).

👉 **If you are prompted for a token**:

1. Generate an access token in your GitHub account (no special permissions required, public read-only is enough).
2. Copy the generated token.
3. Paste it directly into the terminal when Composer asks for it.
4. Press **Enter** to continue.

> ℹ️ Composer will store the token inside the container to avoid asking again.
>
> ❓ It is not entirely clear why some dependencies like Mailgun require this, but it is normal Composer behavior when interacting with GitHub.

---

-   ❌ Do not run `php artisan` or `npm` on the host.
-   ✅ Everything must run inside the container.
-   🔄 Code changes are reflected automatically.
-   🧪 The DEV environment is intended for testing and development, not production.

---

## 🚀 Recommended Workflow

1. Clone the repository
2. Configure `.env`
3. Run `./deploy-dev.sh`
4. Open [http://localhost:8001](http://localhost:8001)
5. Start coding 🚀

---

If something goes wrong, check the logs:

```sh
docker logs -f ops-dev-app-1
```
